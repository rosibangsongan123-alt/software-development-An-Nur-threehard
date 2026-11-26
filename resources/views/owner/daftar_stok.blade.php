<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar & Cek Stok - Manajemen Inventaris</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
    <style>
        body {
            background: linear-gradient(rgba(255, 255, 255, 0.65), rgba(255, 255, 255, 0.65)),
                        url('../aset/bg.jpg') no-repeat center center fixed !important;
            background-size: cover !important;
        }
        .badge-stok-aman { background-color: #E8F5E9; color: #2E7D32; border: 1px solid #A5D6A7; }
        .badge-stok-menipis { background-color: #FFF8E1; color: #B8860B; border: 1px solid var(--gold-border); }
        .badge-stok-habis { background-color: #FFEBEE; color: #C62828; border: 1px solid #EF9A9A; }
    </style>
</head>
<body>

<div class="app-container">
    <!-- Header -->
    <header class="app-header px-4 py-3 d-flex justify-content-between align-items-center sticky-top">
        <div class="d-flex align-items-center gap-3">
            <button class="btn wf-btn-light px-3 py-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu">
                <i class="bi bi-list fs-5"></i>
            </button>
            <div>
                <h5 class="mb-0 fw-bold" style="color: var(--text-dark);">Manajemen Stok & Inventaris</h5>
                <small style="color: var(--text-muted);">Daftar & Cek Stok Varian Produk</small>
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="stok_masuk.php" class="btn wf-btn-dark px-3 py-2">
                <i class="bi bi-plus-circle me-1"></i> Stok Masuk
            </a>
            <a href="stok_keluar.php" class="btn wf-btn-light px-3 py-2">
                <i class="bi bi-dash-circle me-1"></i> Stok Keluar
            </a>
        </div>
    </header>

    <!-- Main Content -->
    <main class="p-4 flex-grow-1">
        <!-- Ringkasan Stok -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="wf-box-solid p-3 d-flex justify-content-between align-items-center">
                    <div>
                        <small style="color: var(--text-muted);">Total Varian Aktif</small>
                        <h4 class="mb-0 fw-bold mt-1">124 Varian</h4>
                    </div>
                    <div class="p-3 rounded-3" style="background-color: var(--gold-soft-bg); color: var(--gold-primary);">
                        <i class="bi bi-box-seam fs-4"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="wf-box-solid p-3 d-flex justify-content-between align-items-center">
                    <div>
                        <small style="color: var(--text-muted);">Stok Menipis (≤ 5)</small>
                        <h4 class="mb-0 fw-bold mt-1" style="color: var(--gold-hover);">8 Varian</h4>
                    </div>
                    <div class="p-3 rounded-3" style="background-color: #FFF8E1; color: var(--gold-hover);">
                        <i class="bi bi-exclamation-triangle fs-4"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="wf-box-solid p-3 d-flex justify-content-between align-items-center">
                    <div>
                        <small style="color: var(--text-muted);">Stok Habis (0)</small>
                        <h4 class="mb-0 fw-bold mt-1 text-danger">3 Varian</h4>
                    </div>
                    <div class="p-3 rounded-3 bg-danger-subtle text-danger">
                        <i class="bi bi-x-octagon fs-4"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter & Pencarian -->
        <div class="wf-box-solid p-3 mb-4">
            <div class="row g-2 align-items-center">
                <div class="col-md-6">
                    <div class="input-group wf-box-solid px-3 py-1">
                        <span class="input-group-text bg-transparent border-0" style="color: var(--gold-primary);">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" id="searchStok" class="form-control wf-input-custom" placeholder="Cari nama produk, SKU, atau ukuran...">
                    </div>
                </div>
                <div class="col-md-3">
                    <select id="filterStatus" class="form-select wf-box-solid py-2" style="font-size: 14px;">
                        <option value="all">Semua Status Stok</option>
                        <option value="aman">Stok Aman</option>
                        <option value="menipis">Stok Menipis</option>
                        <option value="habis">Stok Habis</option>
                    </select>
                </div>
                <div class="col-md-3 text-md-end">
                    <a href="stok_opname.php" class="btn wf-btn-light w-100 py-2">
                        <i class="bi bi-clipboard-check me-1"></i> Opname & Mutasi
                    </a>
                </div>
            </div>
        </div>

        <!-- Tabel Stok -->
        <div class="wf-box-solid p-4">
            <div class="table-responsive">
                <table class="table align-middle mb-0" id="tabelStok">
                    <thead>
                        <tr style="border-bottom: 2px solid var(--gold-border); color: var(--text-muted); font-size: 13px;">
                            <th>SKU / BARCODE</th>
                            <th>NAMA PRODUK</th>
                            <th>VARIAN / UKURAN</th>
                            <th>STOK TERSEDIA</th>
                            <th>STATUS</th>
                            <th class="text-end">AKSI CEPAT</th>
                        </tr>
                    </thead>
                    <tbody style="font-size: 14px;">
                        <tr data-status="aman">
                            <td class="fw-semibold">SKU-001-40</td>
                            <td>Sepatu Sneakers Putih Gold</td>
                            <td>Putih / Size 40</td>
                            <td class="fw-bold">25 Pasang</td>
                            <td><span class="badge rounded-pill px-3 py-2 badge-stok-aman">Stok Aman</span></td>
                            <td class="text-end">
                                <a href="stok_masuk.php" class="btn btn-sm wf-btn-light me-1" title="Tambah Stok"><i class="bi bi-plus"></i></a>
                                <a href="stok_keluar.php" class="btn btn-sm wf-btn-light" title="Kurangi Stok"><i class="bi bi-dash"></i></a>
                            </td>
                        </tr>
                        <tr data-status="menipis">
                            <td class="fw-semibold">SKU-002-42</td>
                            <td>Sepatu Pantofel Kulit Hitam</td>
                            <td>Hitam / Size 42</td>
                            <td class="fw-bold" style="color: var(--gold-hover);">3 Pasang</td>
                            <td><span class="badge rounded-pill px-3 py-2 badge-stok-menipis">Menipis</span></td>
                            <td class="text-end">
                                <a href="stok_masuk.php" class="btn btn-sm wf-btn-light me-1" title="Tambah Stok"><i class="bi bi-plus"></i></a>
                                <a href="stok_keluar.php" class="btn btn-sm wf-btn-light" title="Kurangi Stok"><i class="bi bi-dash"></i></a>
                            </td>
                        </tr>
                        <tr data-status="habis">
                            <td class="fw-semibold">SKU-003-39</td>
                            <td>Sepatu Running Sport</td>
                            <td>Navy / Size 39</td>
                            <td class="fw-bold text-danger">0 Pasang</td>
                            <td><span class="badge rounded-pill px-3 py-2 badge-stok-habis">Habis</span></td>
                            <td class="text-end">
                                <a href="stok_masuk.php" class="btn btn-sm wf-btn-dark" title="Restock"><i class="bi bi-plus-circle me-1"></i>Restock</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<!-- Sidebar Navigation -->
<div class="offcanvas offcanvas-start offcanvas-sidebar" tabindex="-1" id="sidebarMenu">
    <div class="offcanvas-header border-bottom" style="border-color: var(--gold-border) !important;">
        <h6 class="offcanvas-title fw-bold" style="color: var(--gold-primary);">MENU INVENTARIS</h6>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body">
        <a href="dasboard.php" class="nav-link-wf"><i class="bi bi-speedometer2"></i> Dashboard</a>
        <a href="kelola_produk.php" class="nav-link-wf"><i class="bi bi-bag"></i> Kelola Produk</a>
        <hr style="border-color: var(--gold-border);">
        <a href="daftar_stok.php" class="nav-link-wf active"><i class="bi bi-boxes"></i> Daftar & Cek Stok</a>
        <a href="stok_masuk.php" class="nav-link-wf"><i class="bi bi-box-arrow-in-down"></i> Stok Masuk</a>
        <a href="stok_keluar.php" class="nav-link-wf"><i class="bi bi-box-arrow-up"></i> Stok Keluar</a>
        <a href="stok_opname.php" class="nav-link-wf"><i class="bi bi-clipboard-check"></i> Stok Opname & Mutasi</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const searchInput = document.getElementById('searchStok');
    const filterSelect = document.getElementById('filterStatus');
    const rows = document.querySelectorAll('#tabelStok tbody tr');

    function filterTable() {
        const keyword = searchInput.value.toLowerCase();
        const status = filterSelect.value;

        rows.forEach(row => {
            const text = row.innerText.toLowerCase();
            const rowStatus = row.getAttribute('data-status');
            const matchText = text.includes(keyword);
            const matchStatus = (status === 'all' || rowStatus === status);
            row.style.display = (matchText && matchStatus) ? '' : 'none';
        });
    }

    searchInput.addEventListener('input', filterTable);
    filterSelect.addEventListener('change', filterTable);
</script>
</body>
</html>