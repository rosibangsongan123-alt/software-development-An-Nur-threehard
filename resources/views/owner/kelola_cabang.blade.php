<?php
// Halaman Kelola Cabang Owner - Toko Sepatu An-Nur
// Data diambil dari API /api/branches (Issue #7) memakai Bearer token di localStorage.
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Cabang - Toko Sepatu AN-NUR</title>
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
                    <h3 class="fw-bold mb-1" style="font-size: 26px; color: var(--text-dark);">Kelola Cabang</h3>
                    <p class="text-muted mb-0 small">Daftar cabang toko, alamat, dan statusnya</p>
                </div>
                <button type="button" id="btnTambah" class="btn wf-btn-dark px-3 py-2 fw-bold shadow-sm d-flex align-items-center gap-1" style="font-size: 13px;">
                    <i class="bi bi-plus-lg"></i> Tambah Cabang
                </button>
            </div>

            <!-- Pesan status -->
            <div id="alertBox" class="alert py-2 px-3 mb-3 d-none" style="font-size: 13px;" role="alert"></div>

            <!-- Pencarian -->
            <div class="wf-box-solid p-3 mb-3 bg-white shadow-sm rounded-4 d-flex flex-column flex-sm-row gap-2 align-items-sm-center">
                <div class="d-flex align-items-center px-3 py-1 bg-white flex-grow-1 wf-box-solid">
                    <i class="bi bi-search text-muted me-2"></i>
                    <input type="text" id="inputCari" class="form-control wf-input-custom py-2 border-0 shadow-none bg-transparent" placeholder="Cari nama atau alamat cabang..." style="font-size: 13px;">
                </div>
                <div class="form-check form-switch mb-0 ms-sm-2">
                    <input class="form-check-input" type="checkbox" id="cekNonaktif" checked>
                    <label class="form-check-label fw-bold" for="cekNonaktif" style="font-size: 12px;">Tampilkan nonaktif</label>
                </div>
            </div>

            <!-- Tabel daftar cabang -->
            <div class="wf-box-solid p-0 bg-white shadow-sm rounded-4 overflow-hidden mb-4">
                <div class="table-responsive">
                    <table class="table align-middle mb-0" style="font-size: 13px;">
                        <thead>
                            <tr style="background-color: var(--gold-soft-bg);">
                                <th class="ps-4 py-3 fw-bold text-uppercase" style="font-size: 11px; letter-spacing: 0.5px;">Nama Cabang</th>
                                <th class="py-3 fw-bold text-uppercase" style="font-size: 11px; letter-spacing: 0.5px;">Alamat</th>
                                <th class="py-3 fw-bold text-uppercase" style="font-size: 11px; letter-spacing: 0.5px;">Telepon</th>
                                <th class="py-3 fw-bold text-uppercase text-center" style="font-size: 11px; letter-spacing: 0.5px;">User</th>
                                <th class="py-3 fw-bold text-uppercase text-center" style="font-size: 11px; letter-spacing: 0.5px;">Status</th>
                                <th class="py-3 fw-bold text-uppercase text-end pe-4" style="font-size: 11px; letter-spacing: 0.5px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="isiTabel">
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <span class="spinner-border spinner-border-sm me-2"></span> Memuat data cabang...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="d-grid mt-2 mb-5">
                <a href="{{ route('owner.dashboard') }}" class="btn wf-btn-dark py-3 text-decoration-none text-center fw-bold shadow-sm rounded-4" style="font-size: 14px;">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Dashboard
                </a>
            </div>
        </main>
    </div>

    <!-- MODAL TAMBAH / EDIT CABANG -->
    <div class="modal fade" id="modalCabang" tabindex="-1" aria-labelledby="modalCabangLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow">
                <form id="formCabang" novalidate>
                    <div class="modal-header border-bottom" style="border-color: var(--gold-border) !important;">
                        <h5 class="modal-title fw-bold" id="modalCabangLabel" style="font-size: 17px;">Tambah Cabang</h5>
                        <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="alertModal" class="alert alert-danger py-2 px-3 mb-3 d-none" style="font-size: 13px;" role="alert"></div>
                        <input type="hidden" id="cabangId">

                        <div class="mb-3">
                            <label for="namaCabang" class="form-label fw-bold text-uppercase" style="font-size: 11px;">Nama Cabang</label>
                            <input type="text" id="namaCabang" class="form-control wf-box-solid py-2 px-3 shadow-none" maxlength="100" placeholder="Contoh: Cabang Pare" required style="font-size: 14px;">
                        </div>

                        <div class="mb-3">
                            <label for="alamatCabang" class="form-label fw-bold text-uppercase" style="font-size: 11px;">Alamat</label>
                            <textarea id="alamatCabang" class="form-control wf-box-solid py-2 px-3 shadow-none" rows="3" maxlength="255" placeholder="Alamat lengkap cabang" required style="font-size: 14px;"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="teleponCabang" class="form-label fw-bold text-uppercase" style="font-size: 11px;">Telepon <span class="text-muted">(opsional)</span></label>
                            <input type="text" id="teleponCabang" class="form-control wf-box-solid py-2 px-3 shadow-none" maxlength="20" placeholder="0812xxxxxxx" style="font-size: 14px;">
                        </div>

                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="aktifCabang" checked>
                            <label class="form-check-label fw-bold" for="aktifCabang" style="font-size: 13px;">Cabang aktif</label>
                        </div>
                    </div>
                    <div class="modal-footer border-top" style="border-color: var(--gold-border) !important;">
                        <button type="button" class="btn wf-btn-light px-3 py-2 fw-bold shadow-none" data-bs-dismiss="modal" style="font-size: 13px;">Batal</button>
                        <button type="submit" id="btnSimpan" class="btn wf-btn-dark px-3 py-2 fw-bold shadow-sm" style="font-size: 13px;">
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
                <a href="{{ route('owner.produk') }}" class="nav-link-wf">
                    <i class="bi bi-box-seam fs-5"></i>
                    <span>Kelola Produk</span>
                </a>
                <a href="kelola_akun.php" class="nav-link-wf">
                    <i class="bi bi-people-fill fs-5"></i>
                    <span>Kelola Akun</span>
                </a>
                <a href="{{ route('owner.cabang') }}" class="nav-link-wf active">
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

        const isOwner = (user.role || '').toLowerCase() === 'owner';
        if (!isOwner) {
            document.querySelector('#btnTambah').classList.add('d-none');
        }

        // ---------- Util ----------
        const elTabel = document.querySelector('#isiTabel');
        const alertBox = document.querySelector('#alertBox');
        const inputCari = document.querySelector('#inputCari');
        const cekNonaktif = document.querySelector('#cekNonaktif');
        const modalEl = document.querySelector('#modalCabang');
        const modal = new bootstrap.Modal(modalEl);
        const form = document.querySelector('#formCabang');
        const alertModal = document.querySelector('#alertModal');

        function escapeHtml(teks) {
            return String(teks === null || teks === undefined ? '' : teks)
                .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;').replace(/'/g, '&#039;');
        }

        function tampilPesan(pesan, tipe) {
            alertBox.className = 'alert py-2 px-3 mb-3 alert-' + (tipe || 'success');
            alertBox.textContent = pesan;
            alertBox.classList.remove('d-none');
            setTimeout(function () { alertBox.classList.add('d-none'); }, 4000);
        }

        function tampilPesanModal(pesan) {
            alertModal.textContent = pesan;
            alertModal.classList.remove('d-none');
        }

        // Wrapper fetch: kirim Bearer token, tangani 401 (token habis) & 403.
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
                const pesan = hasil.message || 'Terjadi kesalahan. Coba lagi.';
                const error = new Error(pesan);
                error.status = respons.status;
                error.errors = hasil.errors || {};
                throw error;
            }

            return hasil;
        }

        // ---------- Render tabel ----------
        function renderBaris(cabang) {
            const aktif = cabang.is_active === true || cabang.is_active === 1;
            const badge = aktif
                ? '<span class="badge px-3 py-2 text-white fw-bold shadow-sm" style="background-color: #198754; font-size: 12px; border-radius: 8px;">Aktif</span>'
                : '<span class="badge px-3 py-2 text-white fw-bold shadow-sm" style="background-color: #6c757d; font-size: 12px; border-radius: 8px;">Nonaktif</span>';

            const tombolAksi = isOwner
                ? '<div class="d-flex gap-1 justify-content-end">'
                    + '<button type="button" class="btn btn-sm wf-btn-light px-2 py-1 shadow-none" data-aksi="edit" data-id="' + cabang.id + '" title="Edit"><i class="bi bi-pencil-square"></i></button>'
                    + '<button type="button" class="btn btn-sm wf-btn-light px-2 py-1 shadow-none" data-aksi="toggle" data-id="' + cabang.id + '" title="' + (aktif ? 'Nonaktifkan' : 'Aktifkan') + '"><i class="bi bi-' + (aktif ? 'pause-circle' : 'play-circle') + '"></i></button>'
                    + '<button type="button" class="btn btn-sm wf-btn-light px-2 py-1 shadow-none text-danger" data-aksi="hapus" data-id="' + cabang.id + '" title="Hapus"><i class="bi bi-trash"></i></button>'
                  + '</div>'
                : '<span class="text-muted">-</span>';

            return '<tr>'
                + '<td class="ps-4"><strong class="fw-bold text-dark">' + escapeHtml(cabang.name) + '</strong></td>'
                + '<td class="text-muted">' + escapeHtml(cabang.address) + '</td>'
                + '<td class="text-muted">' + escapeHtml(cabang.phone || '-') + '</td>'
                + '<td class="text-center">' + (cabang.users_count === undefined ? 0 : cabang.users_count) + '</td>'
                + '<td class="text-center">' + badge + '</td>'
                + '<td class="text-end pe-4">' + tombolAksi + '</td>'
                + '</tr>';
        }

        let daftarCabang = [];

        async function muatCabang() {
            elTabel.innerHTML = '<tr><td colspan="6" class="text-center text-muted py-4"><span class="spinner-border spinner-border-sm me-2"></span> Memuat data cabang...</td></tr>';

            const params = new URLSearchParams({ per_page: '100' });
            if (cekNonaktif.checked) { params.set('with_inactive', '1'); }
            const kata = inputCari.value.trim();
            if (kata) { params.set('search', kata); }

            try {
                const hasil = await api('/branches?' + params.toString());
                daftarCabang = (hasil.data && hasil.data.branches) ? hasil.data.branches : [];

                if (!daftarCabang.length) {
                    elTabel.innerHTML = '<tr><td colspan="6" class="text-center text-muted py-4">Belum ada cabang. Klik "Tambah Cabang" untuk membuat cabang pertama.</td></tr>';
                    return;
                }

                elTabel.innerHTML = daftarCabang.map(renderBaris).join('');
            } catch (error) {
                elTabel.innerHTML = '<tr><td colspan="6" class="text-center text-danger py-4">' + escapeHtml(error.message) + '</td></tr>';
            }
        }

        // ---------- Modal tambah / edit ----------
        function bukaModal(cabang) {
            alertModal.classList.add('d-none');
            form.reset();

            if (cabang) {
                document.querySelector('#modalCabangLabel').textContent = 'Edit Cabang';
                document.querySelector('#cabangId').value = cabang.id;
                document.querySelector('#namaCabang').value = cabang.name || '';
                document.querySelector('#alamatCabang').value = cabang.address || '';
                document.querySelector('#teleponCabang').value = cabang.phone || '';
                document.querySelector('#aktifCabang').checked = (cabang.is_active === true || cabang.is_active === 1);
            } else {
                document.querySelector('#modalCabangLabel').textContent = 'Tambah Cabang';
                document.querySelector('#cabangId').value = '';
                document.querySelector('#aktifCabang').checked = true;
            }

            modal.show();
        }

        document.querySelector('#btnTambah').addEventListener('click', function () { bukaModal(null); });

        form.addEventListener('submit', async function (event) {
            event.preventDefault();
            alertModal.classList.add('d-none');

            const id = document.querySelector('#cabangId').value;
            const nama = document.querySelector('#namaCabang').value.trim();
            const alamat = document.querySelector('#alamatCabang').value.trim();
            const telepon = document.querySelector('#teleponCabang').value.trim();

            if (!nama || !alamat) {
                tampilPesanModal('Nama dan alamat cabang wajib diisi.');
                return;
            }

            const btnSimpan = document.querySelector('#btnSimpan');
            const teksAsli = btnSimpan.innerHTML;
            btnSimpan.disabled = true;
            btnSimpan.textContent = 'Menyimpan...';

            // PUT = ganti penuh (nama & alamat wajib), sesuai BranchRequest backend.
            const body = { name: nama, address: alamat, phone: telepon || null, is_active: document.querySelector('#aktifCabang').checked };

            try {
                if (id) {
                    await api('/branches/' + id, { method: 'PUT', body: body });
                    tampilPesan('Cabang berhasil diperbarui.');
                } else {
                    await api('/branches', { method: 'POST', body: body });
                    tampilPesan('Cabang berhasil ditambahkan.');
                }
                modal.hide();
                await muatCabang();
            } catch (error) {
                let pesan = error.message;
                if (error.errors) {
                    const detail = Object.values(error.errors).flat();
                    if (detail.length) { pesan = detail.join(' '); }
                }
                tampilPesanModal(pesan);
            } finally {
                btnSimpan.disabled = false;
                btnSimpan.innerHTML = teksAsli;
            }
        });

        // ---------- Aksi per baris ----------
        elTabel.addEventListener('click', async function (event) {
            const tombol = event.target.closest('button[data-aksi]');
            if (!tombol) { return; }

            const aksi = tombol.getAttribute('data-aksi');
            const id = tombol.getAttribute('data-id');
            const cabang = daftarCabang.find(function (c) { return String(c.id) === String(id); });
            if (!cabang) { return; }

            if (aksi === 'edit') {
                bukaModal(cabang);
                return;
            }

            if (aksi === 'toggle') {
                const aktifSekarang = (cabang.is_active === true || cabang.is_active === 1);
                // PATCH: hanya kirim is_active, backend ubah sebagian.
                try {
                    await api('/branches/' + id, { method: 'PATCH', body: { is_active: !aktifSekarang } });
                    tampilPesan(aktifSekarang ? 'Cabang dinonaktifkan.' : 'Cabang diaktifkan kembali.');
                    await muatCabang();
                } catch (error) {
                    tampilPesan(error.message, 'danger');
                }
                return;
            }

            if (aksi === 'hapus') {
                if (!window.confirm('Hapus cabang "' + cabang.name + '"? Tindakan ini tidak bisa dibatalkan.')) { return; }

                try {
                    await api('/branches/' + id, { method: 'DELETE' });
                    tampilPesan('Cabang berhasil dihapus.');
                    await muatCabang();
                } catch (error) {
                    // 409: cabang masih punya user -> tawarkan nonaktifkan saja.
                    if (error.status === 409) {
                        const nonaktifkan = window.confirm(error.message + '\n\nNonaktifkan cabang ini sekarang?');
                        if (nonaktifkan) {
                            try {
                                await api('/branches/' + id, { method: 'PATCH', body: { is_active: false } });
                                tampilPesan('Cabang dinonaktifkan.');
                                await muatCabang();
                            } catch (err2) {
                                tampilPesan(err2.message, 'danger');
                            }
                        }
                        return;
                    }
                    tampilPesan(error.message, 'danger');
                }
            }
        });

        // ---------- Filter ----------
        let timerCari = null;
        inputCari.addEventListener('input', function () {
            clearTimeout(timerCari);
            timerCari = setTimeout(muatCabang, 350);
        });
        cekNonaktif.addEventListener('change', muatCabang);

        muatCabang();
    })();
    </script>
</body>
</html>
