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

### Issue #7 — CRUD Cabang (`/api/branches`) — SELESAI

**Skema & migrasi**
- `2026_11_22_070000_create_branches_table.php` → tabel `branches`
  (`name` unik, `address`, `phone` nullable, `is_active` default true).
- `2026_11_22_071000_add_branch_id_to_users_table.php` → `users.branch_id`
  nullable + foreign key ke `branches` (`nullOnDelete`). Owner tanpa cabang,
  Kasir terikat satu cabang.

**Endpoint** (semua butuh `Authorization: Bearer`)

| Method | Path | Akses | Keterangan |
| --- | --- | --- | --- |
| GET | `/api/branches` | Owner & Kasir | daftar + paging |
| POST | `/api/branches` | Owner | tambah, `201` |
| GET | `/api/branches/{id}` | Owner & Kasir | detail |
| PUT | `/api/branches/{id}` | Owner | ganti penuh (`name` + `address` wajib) |
| PATCH | `/api/branches/{id}` | Owner | ubah sebagian |
| DELETE | `/api/branches/{id}` | Owner | hapus, `?force=1` bila masih ada user |

Query daftar: `page`, `per_page` (1–100, default 15), `search` (nama/alamat),
`is_active` (0/1), `with_inactive=1`. Default hanya cabang aktif.

`data` pada daftar:

```json
{ "branches": [ { "id": 1, "name": "An-Nur Pusat", "address": "...", "phone": "...",
                  "is_active": true, "users_count": 1, "created_at": "...", "updated_at": "..." } ],
  "meta": { "current_page": 1, "last_page": 1, "per_page": 15, "total": 2 } }
```

Kode status: `200` sukses · `201` dibuat · `401` tanpa/ token mati ·
`403` Kasir coba menulis · `404` id tidak ada · `409` cabang masih dipakai user ·
`422` validasi (pesan Indonesia).

**Aturan yang perlu diketahui FE**
- Kasir hanya boleh `GET`. Tombol tambah/ubah/hapus disembunyikan untuk role kasir.
- `is_active: false` menyembunyikan cabang dari daftar default (soft-hide, data tetap ada).
- Hapus cabang yang masih punya user ditolak `409`; FE bisa menawarkan
  `DELETE /api/branches/{id}?force=1` (user-nya dilepas, `branch_id` jadi `null`).
- `branch_id` dan `branch` (objek) ikut di `UserResource` saat relasinya di-load.

**Verifikasi**
- `tests/Feature/Api/BranchApiTest.php` → 15 test hijau.
  Jalankan: `php artisan test --filter=BranchApiTest`
- Uji HTTP nyata (`php artisan serve` + curl): login owner, list, search, create,
  duplikat nama, validasi kosong, show, PUT, PATCH, 404, delete, hapus dengan
  `force`, serta kasir ditolak `403`. Semua sesuai tabel di atas.
- Seeder menambah 2 cabang contoh; `kasir@annur.test` terikat ke An-Nur Pusat.

### Lanjutan Tahap 1 (belum dikerjakan)
- Issue #8 — CRUD Produk & Varian `/api/products`, `/api/product-variants`.
- Issue #9 — CRUD User `/api/users` (kolom `role` & `is_active` sudah siap).
