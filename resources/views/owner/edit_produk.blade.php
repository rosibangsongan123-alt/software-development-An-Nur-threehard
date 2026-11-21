<?php
// Halaman Edit Produk - Toko Sepatu An-Nur
// Menangkap parameter SKU atau ID yang dikirim dari halaman kelola produk
$sku = isset($_GET['sku']) ? $_GET['sku'] : 'SPH-001';

// Simulasi data produk berdasarkan SKU (nantinya bisa diambil dari database)
$nama_produk = "Sepatu Sneakers Casual";
$harga = 250000;
$stok = 24;
$deskripsi = "Sepatu kasual harian berbahan kanvas lembut dengan sol anti-slip.";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk - Toko Sepatu AN-NUR</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="app-container">
        <!-- HEADER -->
        <header class="d-flex justify-content-between align-items-center px-4 py-3 bg-white border-bottom border-dark border-2">
            <div class="d-flex align-items-center gap-3">
                <a href="kelola_produk.php" class="wf-btn-light p-0 d-flex align-items-center justify-content-center text-decoration-none shadow-none" style="width: 40px; height: 40px;">
                    <i class="bi bi-arrow-left fs-4"></i>
                </a>
                <div class="d-flex align-items-center gap-2">
                    <div class="wf-box d-flex align-items-center justify-content-center fw-bold" style="width: 38px; height: 38px; font-size: 8px;">
                        [ LOGO ]
                    </div>
                    <div class="lh-sm">
                        <span class="text-uppercase fw-bold" style="font-size: 9px; letter-spacing: 0.5px;">Toko Sepatu</span>
                        <strong class="fw-bold" style="font-size: 15px;">AN-NUR</strong>
                    </div>
                </div>
            </div>
        </header>

        <!-- MAIN CONTENT -->
        <main class="content-wrapper content-wrapper-narrow flex-grow-1">
            <div class="mb-4">
                <h3 class="fw-bold mb-0">Edit Produk</h3>
                <small class="fw-bold">Perbarui informasi dan deskripsi produk</small>
            </div>

            <form action="kelola_produk.php" method="POST">
                <div class="wf-box p-4 mb-4 bg-white">
                    <div class="mb-3">
                        <label class="form-label fw-bold text-uppercase" style="font-size: 11px;">Nama Produk</label>
                        <input type="text" name="nama_produk" class="form-control wf-box-solid py-2 px-3 shadow-none" value="<?php echo htmlspecialchars($nama_produk); ?>" required style="font-size: 14px;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold text-uppercase" style="font-size: 11px;">SKU / Kode Barang</label>
                        <input type="text" name="sku" class="form-control wf-box-solid py-2 px-3 shadow-none bg-light" value="<?php echo htmlspecialchars($sku); ?>" readonly style="font-size: 14px;">
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-bold text-uppercase" style="font-size: 11px;">Harga (Rp)</label>
                            <input type="number" name="harga" class="form-control wf-box-solid py-2 px-3 shadow-none" value="<?php echo $harga; ?>" required style="font-size: 14px;">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-bold text-uppercase" style="font-size: 11px;">Jumlah Stok</label>
                            <input type="number" name="stok" class="form-control wf-box-solid py-2 px-3 shadow-none" value="<?php echo $stok; ?>" required style="font-size: 14px;">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold text-uppercase" style="font-size: 11px;">Deskripsi Produk</label>
                        <textarea name="deskripsi" class="form-control wf-box-solid p-3 shadow-none" rows="3" placeholder="Tuliskan deskripsi produk..."><?php echo htmlspecialchars($deskripsi); ?></textarea>
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn wf-btn-dark py-3 fw-bold text-uppercase shadow-sm" style="font-size: 14px; letter-spacing: 0.5px;">
                        <i class="bi bi-save-fill me-2"></i> Simpan Perubahan
                    </button>
                    <a href="kelola_produk.php" class="btn wf-btn-light py-2 text-decoration-none text-center fw-bold" style="font-size: 13px;">
                        Batal
                    </a>
                </div>
            </form>
        </main>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>