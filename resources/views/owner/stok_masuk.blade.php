<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stok Masuk - Manajemen Inventaris</title>
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
                <h5 class="mb-0 fw-bold">Pencatatan Stok Masuk</h5>
                <small style="color: var(--text-muted);">Tambah stok barang masuk dari supplier atau produksi</small>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="p-4 flex-grow-1">
        <div class="row g-4">
            <!-- Form Stok Masuk -->
            <div class="col-lg-5">
                <div class="wf-box-solid p-4">
                    <h6 class="fw-bold mb-3" style="color: var(--gold-primary);">
                        <i class="bi bi-box-arrow-in-down me-2"></i>Form Barang Masuk
                    </h6>
                    <form action="" method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Tanggal Masuk</label>
                            <div class="wf-box-solid px-3 py-1">
                                <input type="date" name="tanggal" class="form-control wf-input-custom" value="<?= date('Y-m-d'); ?>" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Pilih Produk & Varian</label>
                            <div class="wf-box-solid px-3 py-1">
                                <select name="varian_id" class="form-select wf-input-custom" required>
                                    <option value="">-- Pilih Varian Produk --</option>
                                    <option value="1">SKU-001-40 | Sepatu Sneakers Putih Gold (Size 40)</option>
                                    <option value="2">SKU-002-42 | Sepatu Pantofel Kulit Hitam (Size 42)</option>
                                    <option value="3">SKU-003-39 | Sepatu Running Sport (Size 39)</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Jumlah Masuk</label>
                            <div class="wf-box-solid px-3 py-1">
                                <input type="number" name="jumlah" min="1" class="form-control wf-input-custom" placeholder="Contoh: 20" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Supplier / Sumber</label>
                            <div class="wf-box-solid px-3 py-1">
                                <input type="text" name="supplier" class="form-control wf-input-custom" placeholder="Nama supplier atau nomor PO">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold small">Catatan Tambahan</label>
                            <div class="wf-box-solid px-3 py-2">
                                <textarea name="catatan" rows="2" class="form-control wf-input-custom" placeholder="Keterangan kondisi barang masuk..."></textarea>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn wf-btn-dark w-100 py-2">
                                <i class="bi bi-check2-circle me-1"></i> Simpan Stok Masuk
                            </button>
                            <button type="reset" class="btn wf-btn-light px-3 py-2">Reset</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Riwayat Stok Masuk Hari Ini -->
            <div class="col-lg-7">
                <div class="wf-box-solid p-4 h-100">
                    <h6 class="fw-bold mb-3">Riwayat Stok Masuk Terbaru</h6>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead>
                                <tr style="border-bottom: 2px solid var(--gold-border); color: var(--text-muted); font-size: 13px;">
                                    <th>TANGGAL</th>
                                    <th>PRODUK & VARIAN</th>
                                    <th>SUPPLIER</th>
                                    <th class="text-end">JUMLAH</th>
                                </tr>
                            </thead>
                            <tbody style="font-size: 14px;">
                                <tr>
                                    <td>26 Nov 2026</td>
                                    <td>
                                        <div class="fw-semibold">Sepatu Sneakers Putih Gold</div>
                                        <small style="color: var(--text-muted);">Putih / Size 40</small>
                                    </td>
                                    <td>CV Mitra Sepatu</td>
                                    <td class="text-end fw-bold text-success">+15</td>
                                </tr>
                                <tr>
                                    <td>25 Nov 2026</td>
                                    <td>
                                        <div class="fw-semibold">Sepatu Pantofel Kulit Hitam</div>
                                        <small style="color: var(--text-muted);">Hitam / Size 42</small>
                                    </td>
                                    <td>PT Karya Kulit</td>
                                    <td class="text-end fw-bold text-success">+10</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>