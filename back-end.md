# Back-End Documentation
- **Nama:** [Risky Adi Setyawan]
- **Peran:** Back-End
- **Kontribusi:** Merancang arsitektur database, membuat endpoint API, dan mengelola logika server serta integrasi data.

---

## Progress Backend

### Issue #6 — Auth API (`/api/login`, `/api/logout`) — SELESAI

**Skema & migrasi**
- `2026_11_22_050002_create_personal_access_tokens_table.php` (bawaan Sanctum)
- `2026_11_22_060000_add_role_and_status_to_users_table.php` → kolom `role`
  (`owner` / `kasir`, default `kasir`) dan `is_active` pada tabel `users`.
- Dependency baru: `laravel/sanctum ^4.5` → setiap anggota menjalankan
  `composer install` sebelum mencoba.

**Endpoint**

| Method | Path | Auth | Body | Hasil |
| --- | --- | --- | --- | --- |
| POST | `/api/login` | bebas | `email`, `password`, `remember?` | `200` + Bearer token |
| POST | `/api/logout` | Bearer | – | `200`, token dihapus |
| GET | `/api/me` | Bearer | – | `200` + data user sesi |

Envelope JSON seragam untuk semua kasus (sukses maupun gagal):

```json
{ "success": true, "message": "Login berhasil.", "data": {} }
{ "success": false, "message": "Email atau password salah.", "errors": {} }
```

`data` pada `/api/login`:

```json
{
  "user": { "id": 1, "name": "Owner An-Nur", "email": "owner@annur.test",
            "role": "owner", "is_active": true, "redirect_to": "/owner/dashboard" },
  "token": "1|xxxx...",
  "token_type": "Bearer",
  "expires_at": "2026-11-23T05:18:31+00:00"
}
```

Kode status: `200` sukses · `401` kredensial salah / token mati · `403` akun
nonaktif · `422` validasi · `429` terlalu sering login (5x per menit per IP+email).

**Aturan yang perlu diketahui FE**
- Header request ber-autentikasi: `Authorization: Bearer <token>`.
- Simpan token setelah login (lihat `resources/views/auth/login.blade.php`),
  lalu pakai di semua panggilan `/api/*` berikutnya.
- `redirect_to` sudah menentukan halaman tujuan sesuai role, FE tidak perlu
  mengecek `role` sendiri.
- Pesan error berbahasa Indonesia dan siap tampil: pakai field `message`,
  atau `errors.<field>[0]` untuk menandai input tertentu.
- Email dinormalisasi (trim + huruf kecil) di server, FE tidak perlu memotongnya.
- Umur token 1 hari; `remember: true` memperpanjang jadi 30 hari.

**Akun seed untuk uji coba** (`php artisan db:seed`)
- `owner@annur.test` / `password` (role owner)
- `kasir@annur.test` / `password` (role kasir)

**Verifikasi**
- Unit/feature: `tests/Feature/Api/AuthApiTest.php` → 15 test, semua hijau.
  Jalankan: `php artisan test --filter=AuthApiTest`
  (bila PHP CLI tidak punya ekstensi `mbstring`: `php -d extension=mbstring vendor/phpunit/phpunit/phpunit`).
- Uji HTTP nyata lewat `php -S` untuk skenario: login benar, `/api/me`, logout,
  `/api/me` setelah logout, password salah, akun nonaktif, body kosong,
  tanpa token, email ber-spasi/huruf besar. Semua sesuai tabel di atas.

### Perbaikan bug yang ditemukan sekalian
- `resources/views/owner/detail_cabang.balde.php` → salah ekstensi sehingga tidak
  pernah terbaca Blade; diganti jadi `detail_cabang.blade.php`.
- `resources/views/auth/login.blade.php` masih POST ke `dashboardK.php` (file
  mockup yang tidak ada). Form sekarang memanggil `POST /api/login` lewat `fetch`,
  menampilkan pesan error API, dan mengarahkan ke `redirect_to`.
- `routes/web.php` belum punya route untuk halaman FE. Ditambah `/login` dan
  `/owner/dashboard`.

### Hutang FE (belum disentuh, ranahnya Issue #4/#14)
Link navigasi di `resources/views/owner/*` masih menunjuk file statis mockup
(`dasboard.php`, `kelola_produk.php`, `total_omset.php`, `kelola_akun.php`,
`kelola_barcode.php`, `notifikasi.php`, `tambah_produk.php`, `tambah_akun.php`,
`edit_produk.php`, `dashboardK.php`). Route Laravel-nya belum ada, jadi semua
link itu mati. Perlu diganti saat integrasi.
Catatan penamaan: `dasboard.blade.php` dan `dashboardK.php` seharusnya `dashboard`.

### Lanjutan Tahap 1 (belum dikerjakan)
- Issue #7 — CRUD Cabang `/api/branches` (tabel `branches` dibuat di sini,
  lalu `users.branch_id` ditambahkan sebagai foreign key).
- Issue #8 — CRUD Produk & Varian `/api/products`, `/api/product-variants`.
- Issue #9 — CRUD User `/api/users` (kolom `role` & `is_active` sudah siap).
