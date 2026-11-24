<?php
// Halaman Kelola Produk Owner - Toko Sepatu An-Nur
// Data diambil dari API /api/products dan /api/product-variants (Issue #8)
// memakai Bearer token di localStorage.
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Produk - Toko Sepatu AN-NUR</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Custom Gold & White Luxury CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}?v=<?php echo time(); ?>">
</head>
<body class="min-vh-100 m-0">
    <div class="w-100 min-vh-100 d-flex flex-column bg-transparent">

        <!-- HEADER -->
        <header class="app-header d-flex justify-content-between align-items-center px-4 py-2 border-bottom sticky-top" style="background-color: rgba(255, 255, 255, 0.92); backdrop-filter: blur(10px);">
            <div class="d-flex align-items-center gap-3">
                <button class="wf-btn-light p-0 d-flex align-items-center justify-content-center shadow-sm"
                        type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarOwner" aria-controls="sidebarOwner"
                        style="width: 42px; height: 42px;">
                    <i class="bi bi-list fs-4"></i>
                </button>
                <div class="d-flex align-items-center gap-2">
                    <div class="d-flex align-items-center justify-content-center overflow-hidden" style="width: 44px; height: 44px;">
                        <img src="{{ asset('aset/logo.png') }}" alt="Logo AN-NUR" class="w-100 h-100 object-fit-contain">
                    </div>
                    <div class="lh-xs">
                        <span class="text-uppercase fw-semibold text-muted d-block" style="font-size: 10px; letter-spacing: 1px;">Toko Sepatu</span>
                        <strong class="fw-bold text-dark" style="font-size: 20px; letter-spacing: -0.5px;">AN-NUR</strong>
                    </div>
                </div>
            </div>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('owner.dashboard') }}" class="wf-btn-light p-0 d-flex align-items-center justify-content-center text-decoration-none shadow-sm" style="width: 42px; height: 42px;" title="Kembali ke Dashboard">
                    <i class="bi bi-house-door fs-5"></i>
                </a>
                <div class="vr mx-1 my-auto" style="height: 24px;"></div>
                <div class="wf-box px-3 py-2 bg-white text-dark fw-bold shadow-sm rounded-3 border" style="font-size: 12px; border-color: var(--gold-border) !important;">
                    <i class="bi bi-person-fill me-1 text-warning"></i> <span id="namaUser">Owner</span>
                </div>
            </div>
        </header>

        <!-- MAIN CONTENT -->
        <main class="content-wrapper flex-grow-1 p-3 p-md-4 container-fluid" style="max-width: 1400px;">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 mb-4">
                <div>
                    <h3 class="fw-bold mb-1" style="font-size: 26px; color: var(--text-dark);">Kelola Produk</h3>
                    <p class="text-muted mb-0 small">Atur produk beserta varian (ukuran / warna), harga, dan stoknya</p>
                </div>
                <button type="button" id="btnTambahProduk" class="btn wf-btn-dark px-3 py-2 fw-bold shadow-sm d-flex align-items-center gap-1" style="font-size: 13px;">
                    <i class="bi bi-plus-lg"></i> Tambah Produk
                </button>
            </div>

            <!-- Pesan status -->
            <div id="alertBox" class="alert py-2 px-3 mb-3 d-none" style="font-size: 13px;" role="alert"></div>

            <!-- Pencarian -->
            <div class="wf-box-solid p-3 mb-3 bg-white shadow-sm rounded-4 d-flex flex-column flex-sm-row gap-2 align-items-sm-center">
                <div class="d-flex align-items-center px-3 py-1 bg-white flex-grow-1 wf-box-solid">
                    <i class="bi bi-search text-muted me-2"></i>
                    <input type="text" id="inputCari" class="form-control wf-input-custom py-2 border-0 shadow-none bg-transparent" placeholder="Cari nama produk, deskripsi, atau kategori..." style="font-size: 13px;">
                </div>
                <div class="form-check form-switch mb-0 ms-sm-2">
                    <input class="form-check-input" type="checkbox" id="cekNonaktif" checked>
                    <label class="form-check-label fw-bold" for="cekNonaktif" style="font-size: 12px;">Tampilkan nonaktif</label>
                </div>
            </div>

            <!-- Daftar produk (diisi lewat JS dari /api/products) -->
            <div id="daftarProduk" class="mb-4">
                <div class="wf-box-solid p-4 bg-white shadow-sm rounded-4 text-center text-muted">
                    <span class="spinner-border spinner-border-sm me-2"></span> Memuat data produk...
                </div>
            </div>

            <div class="d-grid mt-2 mb-5">
                <a href="{{ route('owner.dashboard') }}" class="btn wf-btn-dark py-3 text-decoration-none text-center fw-bold shadow-sm rounded-4" style="font-size: 14px;">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Dashboard
                </a>
            </div>
        </main>
    </div>

    <!-- MODAL TAMBAH / EDIT PRODUK -->
    <div class="modal fade" id="modalProduk" tabindex="-1" aria-labelledby="modalProdukLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow">
                <form id="formProduk" novalidate>
                    <div class="modal-header border-bottom" style="border-color: var(--gold-border) !important;">
                        <h5 class="modal-title fw-bold" id="modalProdukLabel" style="font-size: 17px;">Tambah Produk</h5>
                        <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="alertProduk" class="alert alert-danger py-2 px-3 mb-3 d-none" style="font-size: 13px;" role="alert"></div>
                        <input type="hidden" id="produkId">

                        <div class="mb-3">
                            <label for="namaProduk" class="form-label fw-bold text-uppercase" style="font-size: 11px;">Nama Produk</label>
                            <input type="text" id="namaProduk" class="form-control wf-box-solid py-2 px-3 shadow-none" maxlength="120" placeholder="Contoh: Sepatu Sneakers Casual" required style="font-size: 14px;">
                        </div>

                        <div class="mb-3">
                            <label for="kategoriProduk" class="form-label fw-bold text-uppercase" style="font-size: 11px;">Kategori <span class="text-muted">(sepatu / sandal / topi / dasi)</span></label>
                            <input type="text" id="kategoriProduk" class="form-control wf-box-solid py-2 px-3 shadow-none" maxlength="50" placeholder="sepatu" style="font-size: 14px;">
                        </div>

                        <div class="mb-3">
                            <label for="deskripsiProduk" class="form-label fw-bold text-uppercase" style="font-size: 11px;">Deskripsi <span class="text-muted">(opsional)</span></label>
                            <textarea id="deskripsiProduk" class="form-control wf-box-solid py-2 px-3 shadow-none" rows="3" maxlength="2000" placeholder="Deskripsi singkat produk" style="font-size: 14px;"></textarea>
                        </div>

                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="aktifProduk" checked>
                            <label class="form-check-label fw-bold" for="aktifProduk" style="font-size: 13px;">Produk aktif</label>
                        </div>
                    </div>
                    <div class="modal-footer border-top" style="border-color: var(--gold-border) !important;">
                        <button type="button" class="btn wf-btn-light px-3 py-2 fw-bold shadow-none" data-bs-dismiss="modal" style="font-size: 13px;">Batal</button>
                        <button type="submit" id="btnSimpanProduk" class="btn wf-btn-dark px-3 py-2 fw-bold shadow-sm" style="font-size: 13px;">
                            <i class="bi bi-save-fill me-1"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL TAMBAH / EDIT VARIAN -->
    <div class="modal fade" id="modalVarian" tabindex="-1" aria-labelledby="modalVarianLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow">
                <form id="formVarian" novalidate>
                    <div class="modal-header border-bottom" style="border-color: var(--gold-border) !important;">
                        <h5 class="modal-title fw-bold" id="modalVarianLabel" style="font-size: 17px;">Tambah Varian</h5>
                        <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="alertVarian" class="alert alert-danger py-2 px-3 mb-3 d-none" style="font-size: 13px;" role="alert"></div>
                        <input type="hidden" id="varianId">
                        <input type="hidden" id="varianProductId">

                        <div class="mb-3">
                            <label class="form-label fw-bold text-uppercase" style="font-size: 11px;">Produk</label>
                            <div id="varianNamaProduk" class="wf-box-solid py-2 px-3 text-dark fw-bold" style="font-size: 14px;">-</div>
                        </div>

                        <div class="mb-3">
                            <label for="skuVarian" class="form-label fw-bold text-uppercase" style="font-size: 11px;">SKU / Barcode</label>
                            <input type="text" id="skuVarian" class="form-control wf-box-solid py-2 px-3 shadow-none" maxlength="50" placeholder="Contoh: SPH-001-42-HITAM" required style="font-size: 14px;">
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label for="ukuranVarian" class="form-label fw-bold text-uppercase" style="font-size: 11px;">Ukuran</label>
                                <input type="text" id="ukuranVarian" class="form-control wf-box-solid py-2 px-3 shadow-none" maxlength="20" placeholder="42" style="font-size: 14px;">
                            </div>
                            <div class="col-6">
                                <label for="warnaVarian" class="form-label fw-bold text-uppercase" style="font-size: 11px;">Warna</label>
                                <input type="text" id="warnaVarian" class="form-control wf-box-solid py-2 px-3 shadow-none" maxlength="50" placeholder="Hitam" style="font-size: 14px;">
                            </div>
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label for="hargaVarian" class="form-label fw-bold text-uppercase" style="font-size: 11px;">Harga (Rp)</label>
                                <input type="number" id="hargaVarian" class="form-control wf-box-solid py-2 px-3 shadow-none" min="0" step="1" placeholder="250000" required style="font-size: 14px;">
                            </div>
                            <div class="col-6">
                                <label for="stokVarian" class="form-label fw-bold text-uppercase" style="font-size: 11px;">Stok</label>
                                <input type="number" id="stokVarian" class="form-control wf-box-solid py-2 px-3 shadow-none" min="0" step="1" placeholder="0" required style="font-size: 14px;">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="cabangVarian" class="form-label fw-bold text-uppercase" style="font-size: 11px;">Cabang <span class="text-muted">(kosong = stok umum)</span></label>
                            <select id="cabangVarian" class="form-select wf-box-solid py-2 px-3 shadow-none" style="font-size: 14px;">
                                <option value="">Stok umum (tanpa cabang)</option>
                            </select>
                        </div>

                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="aktifVarian" checked>
                            <label class="form-check-label fw-bold" for="aktifVarian" style="font-size: 13px;">Varian aktif</label>
                        </div>
                    </div>
                    <div class="modal-footer border-top" style="border-color: var(--gold-border) !important;">
                        <button type="button" class="btn wf-btn-light px-3 py-2 fw-bold shadow-none" data-bs-dismiss="modal" style="font-size: 13px;">Batal</button>
                        <button type="submit" id="btnSimpanVarian" class="btn wf-btn-dark px-3 py-2 fw-bold shadow-sm" style="font-size: 13px;">
                            <i class="bi bi-save-fill me-1"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- SIDEBAR OWNER -->
    <div class="offcanvas offcanvas-start offcanvas-sidebar bg-white" tabindex="-1" id="sidebarOwner" aria-labelledby="sidebarOwnerLabel">
        <div class="offcanvas-header border-bottom px-4 py-3" style="border-color: var(--gold-border) !important;">
            <div class="d-flex align-items-center gap-2">
                <div class="d-flex align-items-center justify-content-center overflow-hidden" style="width: 40px; height: 40px;">
                    <img src="{{ asset('aset/logo.png') }}" alt="Logo AN-NUR" class="w-100 h-100 object-fit-contain">
                </div>
                <h5 class="offcanvas-title fw-bold mb-0 text-dark" id="sidebarOwnerLabel" style="font-size: 18px; letter-spacing: 0.5px;">AN-NUR (OWNER)</h5>
            </div>
            <button type="button" class="btn-close text-reset shadow-none" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body px-3 py-4 d-flex flex-column justify-content-between">
            <div class="nav flex-column">
                <a href="{{ route('owner.dashboard') }}" class="nav-link-wf">
                    <i class="bi bi-grid-1x2-fill fs-5"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('owner.produk') }}" class="nav-link-wf active">
                    <i class="bi bi-box-seam fs-5"></i>
                    <span>Kelola Produk</span>
                </a>
                <a href="kelola_akun.php" class="nav-link-wf">
                    <i class="bi bi-people-fill fs-5"></i>
                    <span>Kelola Akun</span>
                </a>
                <a href="{{ route('owner.cabang') }}" class="nav-link-wf">
                    <i class="bi bi-shop fs-5"></i>
                    <span>Kelola Cabang</span>
                </a>
                <a href="kelola_barcode.php" class="nav-link-wf">
                    <i class="bi bi-upc-scan fs-5"></i>
                    <span>Kelola Barcode</span>
                </a>
                <a href="total_omset.php" class="nav-link-wf">
                    <i class="bi bi-wallet2 fs-5"></i>
                    <span>Total Omset</span>
                </a>
            </div>

            <div class="pt-3 border-top" style="border-color: var(--gold-border) !important;">
                <div class="p-2.5 rounded-3 d-flex align-items-center gap-3" style="background-color: var(--gold-soft-bg); border: 1px solid var(--gold-border);">
                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white" style="width: 38px; height: 38px; background: linear-gradient(135deg, #D4AF37 0%, #C59B27 100%);">
                        <i class="bi bi-person-fill fs-6"></i>
                    </div>
                    <div class="lh-sm">
                        <strong class="d-block fw-bold text-dark" style="font-size: 13px;">Owner Toko</strong>
                        <small class="text-muted" style="font-size: 11px;">Pusat Kediri</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    (function () {
        'use strict';

        // ---------- Sesi ----------
        const token = localStorage.getItem('annur_token');
        if (!token) {
            window.location.href = '/login';
            return;
        }

        const user = JSON.parse(localStorage.getItem('annur_user') || '{}');
        if (user && user.name) {
            document.querySelector('#namaUser').textContent = user.name;
        }

        // Tulis/hapus produk & varian hanya boleh Owner (backend balas 403 untuk Kasir).
        const isOwner = (user.role || '').toLowerCase() === 'owner';
        if (!isOwner) {
            document.querySelector('#btnTambahProduk').classList.add('d-none');
        }

        // ---------- Util ----------
        const daftarProdukEl = document.querySelector('#daftarProduk');
        const alertBox = document.querySelector('#alertBox');
        const inputCari = document.querySelector('#inputCari');
        const cekNonaktif = document.querySelector('#cekNonaktif');

        const modalProduk = new bootstrap.Modal(document.querySelector('#modalProduk'));
        const formProduk = document.querySelector('#formProduk');
        const alertProduk = document.querySelector('#alertProduk');

        const modalVarian = new bootstrap.Modal(document.querySelector('#modalVarian'));
        const formVarian = document.querySelector('#formVarian');
        const alertVarian = document.querySelector('#alertVarian');

        function escapeHtml(teks) {
            return String(teks === null || teks === undefined ? '' : teks)
                .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;').replace(/'/g, '&#039;');
        }

        function rupiah(angka) {
            return 'Rp ' + Number(angka || 0).toLocaleString('id-ID');
        }

        function tampilPesan(pesan, tipe) {
            alertBox.className = 'alert py-2 px-3 mb-3 alert-' + (tipe || 'success');
            alertBox.textContent = pesan;
            alertBox.classList.remove('d-none');
            setTimeout(function () { alertBox.classList.add('d-none'); }, 4000);
        }

        // Wrapper fetch: kirim Bearer token, tangani 401 (token habis).
        async function api(path, options) {
            const opsi = Object.assign({ method: 'GET', headers: {} }, options || {});
            opsi.headers = Object.assign({
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'Authorization': 'Bearer ' + token,
            }, opsi.headers || {});

            if (opsi.body && typeof opsi.body !== 'string') {
                opsi.headers['Content-Type'] = 'application/json';
                opsi.body = JSON.stringify(opsi.body);
            }

            const respons = await fetch('/api' + path, opsi);

            if (respons.status === 401) {
                localStorage.removeItem('annur_token');
                localStorage.removeItem('annur_user');
                window.location.href = '/login';
                throw new Error('Sesi berakhir.');
            }

            let hasil = {};
            try { hasil = await respons.json(); } catch (e) { hasil = {}; }

            if (!respons.ok || hasil.success !== true) {
                const error = new Error(hasil.message || 'Terjadi kesalahan. Coba lagi.');
                error.status = respons.status;
                error.errors = hasil.errors || {};
                throw error;
            }

            return hasil;
        }

        function pesanValidasi(error) {
            if (error.errors) {
                const detail = Object.values(error.errors).flat();
                if (detail.length) { return detail.join(' '); }
            }
            return error.message;
        }

        // ---------- Data ----------
        let daftarProduk = [];

        function varianOf(produk) {
            return Array.isArray(produk.variants) ? produk.variants : [];
        }

        function totalStok(produk) {
            return varianOf(produk).reduce(function (t, v) {
                return t + (v.is_active === false ? 0 : Number(v.stock || 0));
            }, 0);
        }

        function rangeHarga(produk) {
            const harga = varianOf(produk).map(function (v) { return Number(v.price || 0); });
            if (!harga.length) { return '-'; }
            const min = Math.min.apply(null, harga);
            const maks = Math.max.apply(null, harga);
            return min === maks ? rupiah(min) : rupiah(min) + ' - ' + rupiah(maks);
        }

        // ---------- Render varian ----------
        function renderVarian(produk) {
            const varian = varianOf(produk);

            if (!varian.length) {
                return '<div class="text-muted small py-2">Belum ada varian. Klik "Varian" agar produk ini bisa dijual.</div>';
            }

            const baris = varian.map(function (v) {
                const aktif = !(v.is_active === false);
                const label = [v.size, v.color].filter(Boolean).join(' / ') || '-';
                const cabang = v.branch ? ' · ' + escapeHtml(v.branch.name) : '';
                const jumlah = Number(v.stock || 0);
                const badgeStok = '<span class="badge text-white fw-bold" style="background-color: '
                    + (jumlah <= 5 ? '#dc3545' : '#198754') + '; font-size: 11px; border-radius: 8px;">' + jumlah + '</span>';

                const aksi = isOwner
                    ? '<div class="d-flex gap-1 justify-content-end">'
                        + '<button type="button" class="btn btn-sm wf-btn-light px-2 py-1 shadow-none" data-aksi="edit-varian" data-produk="' + produk.id + '" data-id="' + v.id + '" title="Edit varian"><i class="bi bi-pencil-square"></i></button>'
                        + '<button type="button" class="btn btn-sm wf-btn-light px-2 py-1 shadow-none text-danger" data-aksi="hapus-varian" data-produk="' + produk.id + '" data-id="' + v.id + '" title="Hapus varian"><i class="bi bi-trash"></i></button>'
                      + '</div>'
                    : '<span class="text-muted">-</span>';

                return '<tr style="font-size: 13px;' + (aktif ? '' : ' opacity: 0.5;') + '">'
                    + '<td class="ps-3 py-2"><span class="fw-bold text-dark">' + escapeHtml(v.sku) + '</span>' + (aktif ? '' : ' <small class="text-muted">(nonaktif)</small>') + '</td>'
                    + '<td class="py-2 text-muted">' + escapeHtml(label) + cabang + '</td>'
                    + '<td class="py-2 text-dark fw-bold">' + rupiah(v.price) + '</td>'
                    + '<td class="py-2 text-center">' + badgeStok + '</td>'
                    + '<td class="py-2 pe-3 text-end">' + aksi + '</td>'
                    + '</tr>';
            }).join('');

            return '<div class="table-responsive bg-white rounded-3 border" style="border-color: var(--gold-border) !important;">'
                + '<table class="table table-sm align-middle mb-0">'
                + '<thead><tr style="background-color: var(--gold-soft-bg); font-size: 11px;">'
                + '<th class="ps-3 py-2 fw-bold text-uppercase">SKU</th>'
                + '<th class="py-2 fw-bold text-uppercase">Ukuran / Warna</th>'
                + '<th class="py-2 fw-bold text-uppercase">Harga</th>'
                + '<th class="py-2 fw-bold text-uppercase text-center">Stok</th>'
                + '<th class="py-2 pe-3 fw-bold text-uppercase text-end">Aksi</th>'
                + '</tr></thead><tbody>' + baris + '</tbody></table></div>';
        }

        // ---------- Render produk ----------
        function renderProduk(produk) {
            const aktif = !(produk.is_active === false);
            const badgeProduk = aktif
                ? '<span class="badge text-white fw-bold" style="background-color: #198754; font-size: 11px; border-radius: 8px;">Aktif</span>'
                : '<span class="badge text-white fw-bold" style="background-color: #6c757d; font-size: 11px; border-radius: 8px;">Nonaktif</span>';

            const tombolAksi = isOwner
                ? '<div class="d-flex flex-wrap gap-2">'
                    + '<button type="button" class="btn btn-sm wf-btn-light py-1 px-3 fw-bold text-dark d-flex align-items-center gap-1 shadow-none" data-aksi="tambah-varian" data-produk="' + produk.id + '" style="font-size: 12px;"><i class="bi bi-plus-lg text-success"></i> Varian</button>'
                    + '<button type="button" class="btn btn-sm wf-btn-light py-1 px-3 fw-bold text-dark d-flex align-items-center gap-1 shadow-none" data-aksi="edit-produk" data-produk="' + produk.id + '" style="font-size: 12px;"><i class="bi bi-pencil-fill text-warning"></i> Edit</button>'
                    + '<button type="button" class="btn btn-sm py-1 px-3 fw-bold d-flex align-items-center gap-1 shadow-none text-white" data-aksi="hapus-produk" data-produk="' + produk.id + '" style="font-size: 12px; background-color: #dc3545; border: none; border-radius: 10px;"><i class="bi bi-trash-fill"></i> Hapus</button>'
                  + '</div>'
                : '';

            return '<div class="wf-box-solid p-4 mb-3 bg-white shadow-sm rounded-4' + (aktif ? '' : ' opacity-50') + '">'
                + '<div class="d-flex justify-content-between align-items-start mb-2 gap-2">'
                    + '<div>'
                        + '<strong class="d-block text-dark fw-bold" style="font-size: 16px;">' + escapeHtml(produk.name) + '</strong>'
                        + '<small class="text-muted" style="font-size: 13px;">' + escapeHtml(produk.description || '-') + '</small>'
                    + '</div>'
                    + '<div class="d-flex flex-column align-items-end gap-1">'
                        + badgeProduk
                        + '<span class="badge px-3 py-2 text-white fw-bold shadow-sm" style="background-color: var(--text-dark); font-size: 12px; border-radius: 8px;">Stok: ' + totalStok(produk) + '</span>'
                    + '</div>'
                + '</div>'
                + '<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 border-top pt-3 mt-2 mb-3" style="border-color: var(--gold-border) !important; font-size: 14px;">'
                    + '<div class="text-muted">'
                        + '<span class="fw-bold text-dark">' + rangeHarga(produk) + '</span>'
                        + ' <span class="ms-2">| Kategori: ' + escapeHtml(produk.category || '-') + '</span>'
                        + ' <span class="ms-2">| Varian: ' + varianOf(produk).length + '</span>'
                    + '</div>'
                    + tombolAksi
                + '</div>'
                + renderVarian(produk)
                + '</div>';
        }

        // ---------- Muat data ----------
        async function muatProduk() {
            daftarProdukEl.innerHTML = '<div class="wf-box-solid p-4 bg-white shadow-sm rounded-4 text-center text-muted"><span class="spinner-border spinner-border-sm me-2"></span> Memuat data produk...</div>';

            const params = new URLSearchParams({ per_page: '100', with_variants: '1' });
            if (cekNonaktif.checked) { params.set('with_inactive', '1'); }
            const kata = inputCari.value.trim();
            if (kata) { params.set('search', kata); }

            try {
                const hasil = await api('/products?' + params.toString());
                daftarProduk = (hasil.data && hasil.data.products) ? hasil.data.products : [];

                if (!daftarProduk.length) {
                    daftarProdukEl.innerHTML = '<div class="wf-box-solid p-4 bg-white shadow-sm rounded-4 text-center text-muted">Belum ada produk. Klik "Tambah Produk" untuk membuat produk pertama.</div>';
                    return;
                }

                daftarProdukEl.innerHTML = daftarProduk.map(renderProduk).join('');
            } catch (error) {
                daftarProdukEl.innerHTML = '<div class="wf-box-solid p-4 bg-white shadow-sm rounded-4 text-center text-danger">' + escapeHtml(error.message) + '</div>';
            }
        }

        async function muatCabang() {
            let daftarCabang = [];
            try {
                const hasil = await api('/branches?per_page=100&with_inactive=1');
                daftarCabang = (hasil.data && hasil.data.branches) ? hasil.data.branches : [];
            } catch (error) {
                daftarCabang = [];
            }

            document.querySelector('#cabangVarian').innerHTML = '<option value="">Stok umum (tanpa cabang)</option>'
                + daftarCabang.map(function (c) {
                    return '<option value="' + c.id + '">' + escapeHtml(c.name) + '</option>';
                }).join('');
        }

        // ---------- Modal produk ----------
        function cariProduk(id) {
            return daftarProduk.find(function (p) { return String(p.id) === String(id); });
        }

        function bukaModalProduk(produk) {
            alertProduk.classList.add('d-none');
            formProduk.reset();

            if (produk) {
                document.querySelector('#modalProdukLabel').textContent = 'Edit Produk';
                document.querySelector('#produkId').value = produk.id;
                document.querySelector('#namaProduk').value = produk.name || '';
                document.querySelector('#kategoriProduk').value = produk.category || '';
                document.querySelector('#deskripsiProduk').value = produk.description || '';
                document.querySelector('#aktifProduk').checked = !(produk.is_active === false);
            } else {
                document.querySelector('#modalProdukLabel').textContent = 'Tambah Produk';
                document.querySelector('#produkId').value = '';
                document.querySelector('#aktifProduk').checked = true;
            }

            modalProduk.show();
        }

        document.querySelector('#btnTambahProduk').addEventListener('click', function () { bukaModalProduk(null); });

        formProduk.addEventListener('submit', async function (event) {
            event.preventDefault();
            alertProduk.classList.add('d-none');

            const id = document.querySelector('#produkId').value;
            const nama = document.querySelector('#namaProduk').value.trim();

            if (!nama) {
                alertProduk.textContent = 'Nama produk wajib diisi.';
                alertProduk.classList.remove('d-none');
                return;
            }

            const btn = document.querySelector('#btnSimpanProduk');
            const teksAsli = btn.innerHTML;
            btn.disabled = true;
            btn.textContent = 'Menyimpan...';

            // PUT = ganti penuh (name wajib), sesuai ProductRequest backend.
            const body = {
                name: nama,
                category: document.querySelector('#kategoriProduk').value.trim() || null,
                description: document.querySelector('#deskripsiProduk').value.trim() || null,
                is_active: document.querySelector('#aktifProduk').checked,
            };

            try {
                if (id) {
                    await api('/products/' + id, { method: 'PUT', body: body });
                    tampilPesan('Produk berhasil diperbarui.');
                } else {
                    await api('/products', { method: 'POST', body: body });
                    tampilPesan('Produk berhasil ditambahkan.');
                }
                modalProduk.hide();
                await muatProduk();
            } catch (error) {
                alertProduk.textContent = pesanValidasi(error);
                alertProduk.classList.remove('d-none');
            } finally {
                btn.disabled = false;
                btn.innerHTML = teksAsli;
            }
        });

        // ---------- Modal varian ----------
        function cariVarian(produkId, varianId) {
            const produk = cariProduk(produkId);
            if (!produk) { return null; }
            return varianOf(produk).find(function (v) { return String(v.id) === String(varianId); });
        }

        function bukaModalVarian(produk, varian) {
            alertVarian.classList.add('d-none');
            formVarian.reset();

            document.querySelector('#varianProductId').value = produk.id;
            document.querySelector('#varianNamaProduk').textContent = produk.name;
            document.querySelector('#aktifVarian').checked = true;

            if (varian) {
                document.querySelector('#modalVarianLabel').textContent = 'Edit Varian';
                document.querySelector('#varianId').value = varian.id;
                document.querySelector('#skuVarian').value = varian.sku || '';
                document.querySelector('#ukuranVarian').value = varian.size || '';
                document.querySelector('#warnaVarian').value = varian.color || '';
                document.querySelector('#hargaVarian').value = varian.price || 0;
                document.querySelector('#stokVarian').value = varian.stock || 0;
                document.querySelector('#cabangVarian').value = varian.branch_id || '';
                document.querySelector('#aktifVarian').checked = !(varian.is_active === false);
            } else {
                document.querySelector('#modalVarianLabel').textContent = 'Tambah Varian';
                document.querySelector('#varianId').value = '';
            }

            modalVarian.show();
        }

        formVarian.addEventListener('submit', async function (event) {
            event.preventDefault();
            alertVarian.classList.add('d-none');

            const id = document.querySelector('#varianId').value;
            const productId = document.querySelector('#varianProductId').value;
            const sku = document.querySelector('#skuVarian').value.trim();
            const harga = document.querySelector('#hargaVarian').value;
            const stok = document.querySelector('#stokVarian').value;

            if (!sku || harga === '' || stok === '') {
                alertVarian.textContent = 'SKU, harga, dan stok wajib diisi.';
                alertVarian.classList.remove('d-none');
                return;
            }

            const btn = document.querySelector('#btnSimpanVarian');
            const teksAsli = btn.innerHTML;
            btn.disabled = true;
            btn.textContent = 'Menyimpan...';

            const cabangDipilih = document.querySelector('#cabangVarian').value;

            // PUT = ganti penuh (product_id, sku, price, stock wajib), sesuai ProductVariantRequest.
            const body = {
                product_id: Number(productId),
                sku: sku,
                size: document.querySelector('#ukuranVarian').value.trim() || null,
                color: document.querySelector('#warnaVarian').value.trim() || null,
                price: Number(harga),
                stock: Number(stok),
                branch_id: cabangDipilih ? Number(cabangDipilih) : null,
                is_active: document.querySelector('#aktifVarian').checked,
            };

            try {
                if (id) {
                    await api('/product-variants/' + id, { method: 'PUT', body: body });
                    tampilPesan('Varian berhasil diperbarui.');
                } else {
                    await api('/product-variants', { method: 'POST', body: body });
                    tampilPesan('Varian berhasil ditambahkan.');
                }
                modalVarian.hide();
                await muatProduk();
            } catch (error) {
                alertVarian.textContent = pesanValidasi(error);
                alertVarian.classList.remove('d-none');
            } finally {
                btn.disabled = false;
                btn.innerHTML = teksAsli;
            }
        });

        // ---------- Aksi per kartu / per baris varian ----------
        daftarProdukEl.addEventListener('click', async function (event) {
            const tombol = event.target.closest('button[data-aksi]');
            if (!tombol) { return; }

            const aksi = tombol.getAttribute('data-aksi');
            const produkId = tombol.getAttribute('data-produk');
            const varianId = tombol.getAttribute('data-id');
            const produk = cariProduk(produkId);

            if (aksi === 'tambah-varian' || aksi === 'edit-varian') {
                if (!produk) { return; }
                bukaModalVarian(produk, aksi === 'edit-varian' ? cariVarian(produkId, varianId) : null);
                return;
            }

            if (aksi === 'edit-produk') {
                if (produk) { bukaModalProduk(produk); }
                return;
            }

            if (aksi === 'hapus-varian') {
                const varian = cariVarian(produkId, varianId);
                if (!varian) { return; }
                if (!window.confirm('Hapus varian "' + varian.sku + '"?')) { return; }

                try {
                    await api('/product-variants/' + varianId, { method: 'DELETE' });
                    tampilPesan('Varian berhasil dihapus.');
                    await muatProduk();
                } catch (error) {
                    tampilPesan(error.message, 'danger');
                }
                return;
            }

            if (aksi === 'hapus-produk') {
                if (!produk) { return; }
                if (!window.confirm('Hapus produk "' + produk.name + '" beserta variannya?')) { return; }

                try {
                    // ?force=1: backend ikut menghapus varian yang masih menempel.
                    await api('/products/' + produk.id + '?force=1', { method: 'DELETE' });
                    tampilPesan('Produk berhasil dihapus.');
                    await muatProduk();
                } catch (error) {
                    tampilPesan(error.message, 'danger');
                }
            }
        });

        // ---------- Filter ----------
        let timerCari = null;
        inputCari.addEventListener('input', function () {
            clearTimeout(timerCari);
            timerCari = setTimeout(muatProduk, 350);
        });
        cekNonaktif.addEventListener('change', muatProduk);

        // ---------- Init ----------
        muatCabang();
        muatProduk();
    })();
    </script>
</body>
</html>
