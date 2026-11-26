<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stok Keluar - Manajemen Inventaris</title>
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
                <h5 class="mb-0 fw-bold">Pencatatan Stok Keluar</h5>
                <small style="color: var(--text-muted);">Pengurangan stok barang rusak, hilang, atau retur supplier</small>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="p-4 flex-grow-1">
        <div class="row g-4">
            <!-- Form Stok Keluar -->
            <div class="col-lg-5">
                <div class="wf-box-solid p-4">
                    <h6 class="fw-bold mb-3 text-danger">
                        <i class="bi bi-box-arrow-up me-2"></i>Form Pengurangan Stok
                    </h6>
                    <form action="" method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Tanggal Keluar</label>
                            <div class="wf-box-solid px-3 py-1">
                                <input type="date" name="tanggal" class="form-control wf-input-custom" value="<?= date('Y-m-d'); ?>" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Pilih Produk & Varian</label>
                            <div class="wf-box-solid px-3 py-1">
                                <select name="varian_id" class="form-select wf-input-custom" required>
                                    <option value="">-- Pilih Varian Produk --</option>
                                    <option value="1">SKU-001-40 | Sepatu Sneakers Putih Gold (Stok: 25)</option>
                                    <option value="2">SKU-002-42 | Sepatu Pantofel Kulit Hitam (Stok: 3)</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Alasan Pengurangan</label>
                            <div class="wf-box-solid px-3 py-1">
                                <select name="alasan" class="form-select wf-input-custom" required>
                                    <option value="rusak">Barang Rusak / Cacat</option>
                                    <option value="hilang">Barang Hilang / Selisih</option>
                                    <option value="retur">Retur ke Supplier</option>
                                    <option value="sample">Display / Sample Toko</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Jumlah Keluar</label>
                            <div class="wf-box-solid px-3 py-1">
                                <input type="number" name="jumlah" min="1" class="form-control wf-input-custom" placeholder="Contoh: 2" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold small">Keterangan Detail</label>
                            <div class="wf-box-solid px-3 py-2">
                                <textarea name="keterangan" rows="2" class="form-control wf-input-custom" placeholder="Jelaskan alasan pengurangan stok..." required></textarea>
                            </div>
                        </div>

                        <button type="submit" class="btn wf-btn-danger-custom text-white fw-semibold w-100 py-2">
                            <i class="bi bi-exclamation-circle me-1"></i> Catat Pengurangan Stok
                        </button>
                    </form>
                </div>
            </div>

            <!-- Riwayat Stok Keluar -->
            <div class="col-lg-7">
                <div class="wf-box-solid p-4 h-100">
                    <h6 class="fw-bold mb-3">Riwayat Stok Keluar Terbaru</h6>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead>
                                <tr style="border-bottom: 2px solid var(--gold-border); color: var(--text-muted); font-size: 13px;">
                                    <th>TANGGAL</th>
                                    <th>PRODUK & VARIAN</th>
                                    <th>ALASAN</th>
                                    <th class="text-end">JUMLAH</th>
                                </tr>
                            </thead>
                            <tbody style="font-size: 14px;">
                                <tr>
                                    <td>24 Nov 2026</td>
                                    <td>
                                        <div class="fw-semibold">Sepatu Sneakers Putih Gold</div>
                                        <small style="color: var(--text-muted);">Putih / Size 40</small>
                                    </td>
                                    <td><span class="badge bg-danger-subtle text-danger">Barang Rusak</span></td>
                                    <td class="text-end fw-bold text-danger">-1</td>
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