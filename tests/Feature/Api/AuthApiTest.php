<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\PersonalAccessToken;
use Tests\TestCase;

/**
 * Issue #6 — Auth API: POST /api/login & POST /api/logout (+ GET /api/me).
 */
class AuthApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Email/password contoh yang dibuat factory (password = 'password').
     *
     * @return array{0: string, 1: string}
     */
    private function credentials(string $role = User::ROLE_OWNER): array
    {
        $user = User::factory()->{$role}()->create();

        return [$user->email, 'password'];
    }

    public function test_login_berhasil_dan_mengembalikan_token_bearer(): void
    {
        [$email, $password] = $this->credentials(User::ROLE_OWNER);

        $response = $this->postJson('/api/login', [
            'email' => $email,
            'password' => $password,
        ]);

        $response->assertOk()
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'user' => ['id', 'name', 'email', 'role', 'is_active', 'redirect_to'],
                    'token',
                    'token_type',
                    'expires_at',
                ],
            ])
            ->assertJson([
                'success' => true,
                'message' => 'Login berhasil.',
                'data' => [
                    'user' => ['email' => $email, 'role' => 'owner', 'redirect_to' => '/owner/dashboard'],
                    'token_type' => 'Bearer',
                ],
            ]);

        $this->assertDatabaseCount('personal_access_tokens', 1);

        // Password tidak boleh ikut terbawa di response.
        $this->assertStringNotContainsString('password', $response->getContent());
    }

    public function test_login_kasir_diarahkan_ke_halaman_kasir(): void
    {
        [$email, $password] = $this->credentials(User::ROLE_KASIR);

        $this->postJson('/api/login', ['email' => $email, 'password' => $password])
            ->assertOk()
            ->assertJsonPath('data.user.role', 'kasir')
            ->assertJsonPath('data.user.redirect_to', '/kasir/dashboard');
    }

    public function test_email_dan_password_normalisasi_spasi_serta_huruf_besar(): void
    {
        [$email, $password] = $this->credentials();

        $this->postJson('/api/login', [
            'email' => '  '.strtoupper($email).' ',
            'password' => $password,
        ])->assertOk()->assertJsonPath('success', true);
    }

    public function test_validasi_menolak_input_kosong(): void
    {
        $response = $this->postJson('/api/login', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrorFor('email')
            ->assertJsonValidationErrorFor('password')
            ->assertJsonPath('success', false);
    }

    public function test_validasi_menolak_format_email_salah(): void
    {
        $this->postJson('/api/login', ['email' => 'bukan-email', 'password' => 'password'])
            ->assertStatus(422)
            ->assertJsonPath('errors.email.0', 'Format email tidak valid.');
    }

    public function test_password_salah_dibalas_unauthorized(): void
    {
        [$email] = $this->credentials();

        $this->postJson('/api/login', ['email' => $email, 'password' => 'salahsekali'])
            ->assertStatus(401)
            ->assertJsonPath('success', false)
            ->assertJsonPath('message', 'Email atau password salah.');
    }

    public function test_email_terdaftar_dan_email_asing_menjawab_sama(): void
    {
        [$email] = $this->credentials();

        $pesanEmailSalah = $this->postJson('/api/login', ['email' => $email, 'password' => 'salahsekali'])
            ->json('message');
        $pesanEmailAsing = $this->postJson('/api/login', ['email' => 'asing@annur.test', 'password' => 'salahsekali'])
            ->json('message');

        $this->assertSame($pesanEmailSalah, $pesanEmailAsing, 'Balasan tidak boleh membocorkan akun mana yang ada.');
    }

    public function test_akun_nonaktif_ditolak_dengan_403(): void
    {
        $user = User::factory()->nonaktif()->create();

        $this->postJson('/api/login', ['email' => $user->email, 'password' => 'password'])
            ->assertStatus(403)
            ->assertJsonPath('success', false)
            ->assertJsonPath('message', 'Akun ini sedang dinonaktifkan. Hubungi Owner.');

        $this->assertDatabaseCount('personal_access_tokens', 0);
    }

    public function test_remember_true_memperpanjang_umur_token(): void
    {
        [$email, $password] = $this->credentials();

        $tanpaRemember = $this->postJson('/api/login', ['email' => $email, 'password' => $password])->json('data.expires_at');
        $denganRemember = $this->postJson('/api/login', [
            'email' => $email,
            'password' => $password,
            'remember' => true,
        ])->json('data.expires_at');

        $this->assertLessThan(strtotime($denganRemember), strtotime($tanpaRemember));
    }

    public function test_me_memakai_token_yang_diberikan_login(): void
    {
        [$email, $password] = $this->credentials();

        $token = $this->postJson('/api/login', ['email' => $email, 'password' => $password])->json('data.token');

        $this->withToken($token)
            ->getJson('/api/me')
            ->assertOk()
            ->assertJsonPath('data.user.email', $email);
    }

    public function test_me_ditolak_tanpa_token(): void
    {
        $this->getJson('/api/me')
            ->assertStatus(401)
            ->assertJsonPath('success', false)
            ->assertJsonPath('message', 'Sesi tidak valid. Login ulang untuk melanjutkan.');
    }

    public function test_logout_menghapus_token_yang_dipakai(): void
    {
        [$email, $password] = $this->credentials();

        $token = $this->postJson('/api/login', ['email' => $email, 'password' => $password])->json('data.token');

        $this->withToken($token)
            ->postJson('/api/logout')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('message', 'Logout berhasil.');

        $this->assertNull(PersonalAccessToken::findToken($token));
    }

    /**
     * Token mati memang tidak bisa dipakai lagi.
     *
     * Dites terpisah: kalau dicek langsung setelah logout di satu test yang sama,
     * guard auth Laravel masih menyimpan user dari request sebelumnya, jadi
     * /api/me bisa balas 200 walau token sudah dihapus.
     */
    public function test_token_yang_sudah_dihapus_ditolak_oleh_me(): void
    {
        $user = User::factory()->owner()->create();
        $token = $user->createToken('uji')->plainTextToken;

        PersonalAccessToken::findToken($token)->delete();

        $this->withToken($token)
            ->getJson('/api/me')
            ->assertStatus(401)
            ->assertJsonPath('success', false);
    }

    public function test_logout_tidak_memengaruhi_device_lain(): void
    {
        [$email, $password] = $this->credentials();

        $tokenLaptop = $this->postJson('/api/login', ['email' => $email, 'password' => $password])->json('data.token');
        $tokenHP = $this->postJson('/api/login', ['email' => $email, 'password' => $password])->json('data.token');

        $this->withToken($tokenLaptop)->postJson('/api/logout')->assertOk();

        $this->assertNull(PersonalAccessToken::findToken($tokenLaptop));
        $this->assertNotNull(PersonalAccessToken::findToken($tokenHP));
    }

    public function test_login_dibatasi_setelah_lima_percobaan_gagal(): void
    {
        [$email] = $this->credentials();

        for ($percobaan = 1; $percobaan <= 5; $percobaan++) {
            $this->postJson('/api/login', ['email' => $email, 'password' => 'salahsekali'])
                ->assertStatus(401);
        }

        $this->postJson('/api/login', ['email' => $email, 'password' => 'salahsekali'])
            ->assertStatus(429)
            ->assertJsonPath('success', false)
            ->assertJsonPath('message', 'Terlalu banyak percobaan login. Coba lagi dalam 1 menit.');
    }
}
