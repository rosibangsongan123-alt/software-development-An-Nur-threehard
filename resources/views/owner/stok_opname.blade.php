<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stok Opname & Riwayat Mutasi - Manajemen Inventaris</title>
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
    </style>
</head>
<body>

<div class="app-container">
    <!-- Header -->
    <header class="app-header px-4 py-3 d-flex justify-content-between align-items-center sticky-top">
        <div class="d-flex align-items-center gap-3">
            <a href="daftar_stok.php" class="btn wf-btn-light px-3 py-2">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h5 class="mb-0 fw-bold">Stok Opname & Riwayat Mutasi</h5>
                <small style="color: var(--text-muted);">Penyesuaian stok fisik & log pergerakan barang</small>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="p-4 flex-grow-1">
        <!-- Form Stok Opname -->
        <div class="wf-box-solid p-4 mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0" style="color: var(--gold-primary);">
                    <i class="bi bi-clipboard-check me-2"></i>Input Stok Opname Fisik
                </h6>
                <span class="badge rounded-pill px-3 py-2" style="background-color: var(--gold-soft-bg); color: var(--gold-hover); border: 1px solid var(--gold-border);">
                    Selisih dihitung otomatis
                </span>
            </div>

            <form action="" method="POST">
                <div class="table-responsive">
                    <table class="table align-middle mb-3">
                        <thead>
                            <tr style="border-bottom: 2px solid var(--gold-border); color: var(--text-muted); font-size: 13px;">
                                <th>SKU</th>
                                <th>PRODUK & VARIAN</th>
                                <th class="text-center">STOK SISTEM</th>
                                <th class="text-center" style="width: 160px;">STOK FISIK</th>
                                <th class="text-center">SELISIH</th>
                                <th>CATATAN OPNAME</th>
                            </tr>
                        </thead>
                        <tbody style="font-size: 14px;">
                            <tr>
                                <td class="fw-semibold">SKU-001-40</td>
                                <td>Sepatu Sneakers Putih Gold (Size 40)</td>
                                <td class="text-center fw-bold stok-sistem" data-sistem="25">25</td>
                                <td>
                                    <div class="wf-box-solid px-2 py-1">
                                        <input type="number" name="fisik[]" min="0" value="25" class="form-control wf-input-custom text-center fw-bold input-fisik">
                                    </div>
                                </td>
                                <td class="text-center fw-bold hasil-selisih text-muted">0</td>
                                <td>
                                    <div class="wf-box-solid px-2 py-1">
                                        <input type="text" name="catatan[]" class="form-control wf-input-custom" placeholder="Opsional...">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">SKU-002-42</td>
                                <td>Sepatu Pantofel Kulit Hitam (Size 42)</td>
                                <td class="text-center fw-bold stok-sistem" data-sistem="3">3</td>
                                <td>
                                    <div class="wf-box-solid px-2 py-1">
                                        <input type="number" name="fisik[]" min="0" value="3" class="form-control wf-input-custom text-center fw-bold input-fisik">
                                    </div>
                                </td>
                                <td class="text-center fw-bold hasil-selisih text-muted">0</td>
                                <td>
                                    <div class="wf-box-solid px-2 py-1">
                                        <input type="text" name="catatan[]" class="form-control wf-input-custom" placeholder="Opsional...">
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="text-end">
                    <button type="submit" class="btn wf-btn-dark px-4 py-2">
                        <i class="bi bi-save me-1"></i> Simpan Hasil Opname
                    </button>
                </div>
            </form>
        </div>

        <!-- Riwayat Mutasi Stok -->
        <div class="wf-box-solid p-4">
            <h6 class="fw-bold mb-3">Riwayat Mutasi Stok Keseluruhan</h6>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr style="border-bottom: 2px solid var(--gold-border); color: var(--text-muted); font-size: 13px;">
                            <th>WAKTU</th>
                            <th>PRODUK & VARIAN</th>
                            <th>JENIS MUTASI</th>
                            <th class="text-center">PERUBAHAN</th>
                            <th class="text-center">STOK AKHIR</th>
                            <th>KETERANGAN</th>
                        </tr>
                    </thead>
                    <tbody style="font-size: 14px;">
                        <tr>
                            <td>26 Nov 2026, 14:20</td>
                            <td>Sepatu Sneakers Putih Gold (Size 40)</td>
                            <td><span class="badge bg-success-subtle text-success">Stok Masuk</span></td>
                            <td class="text-center fw-bold text-success">+15</td>
                            <td class="text-center fw-bold">25</td>
                            <td>Restock CV Mitra Sepatu</td>
                        </tr>
                        <tr>
                            <td>25 Nov 2026, 19:05</td>
                            <td>Sepatu Pantofel Kulit Hitam (Size 42)</td>
                            <td><span class="badge bg-warning-subtle text-dark">Stok Opname</span></td>
                            <td class="text-center fw-bold text-danger">-1</td>
                            <td class="text-center fw-bold">3</td>
                            <td>Selisih perhitungan fisik</td>
                        </tr>
                        <tr>
                            <td>24 Nov 2026, 11:10</td>
                            <td>Sepatu Sneakers Putih Gold (Size 40)</td>
                            <td><span class="badge bg-danger-subtle text-danger">Stok Keluar</span></td>
                            <td class="text-center fw-bold text-danger">-1</td>
                            <td class="text-center fw-bold">10</td>
                            <td>Lem sol terkelupas (rusak)</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Hitung selisih otomatis (Stok Fisik - Stok Sistem)
    document.querySelectorAll('.input-fisik').forEach(input => {
        input.addEventListener('input', function () {
            const row = this.closest('tr');
            const stokSistem = parseInt(row.querySelector('.stok-sistem').dataset.sistem, 10) || 0;
            const stokFisik = parseInt(this.value, 10);
            const cellSelisih = row.querySelector('.hasil-selisih');

            if (isNaN(stokFisik)) {
                cellSelisih.textContent = '-';
                cellSelisih.className = 'text-center fw-bold hasil-selisih text-muted';
                return;
            }

            const selisih = stokFisik - stokSistem;
            if (selisih > 0) {
                cellSelisih.textContent = '+' + selisih;
                cellSelisih.className = 'text-center fw-bold hasil-selisih text-success';
            } else if (selisih < 0) {
                cellSelisih.textContent = selisih;
                cellSelisih.className = 'text-center fw-bold hasil-selisih text-danger';
            } else {
                cellSelisih.textContent = '0';
                cellSelisih.className = 'text-center fw-bold hasil-selisih text-muted';
            }
        });
    });
</script>
</body>
</html>