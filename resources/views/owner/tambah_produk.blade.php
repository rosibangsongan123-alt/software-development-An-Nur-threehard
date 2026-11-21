<?php
// Halaman Tambah Produk - Toko Sepatu An-Nur
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk - Toko Sepatu AN-NUR</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Custom Gold & White Luxury CSS -->
    <link rel="stylesheet" href="../style.css?v=<?php echo time(); ?>">
</head>
<body class="min-vh-100 m-0">
    <!-- Pembungkus utama dibuat transparan penuh agar background body terlihat -->
    <div class="w-100 min-vh-100 d-flex flex-column bg-transparent">
        
        <!-- HEADER -->
        <header class="app-header d-flex justify-content-between align-items-center px-4 py-2 border-bottom sticky-top" style="background-color: rgba(255, 255, 255, 0.92); backdrop-filter: blur(10px);">
            <div class="d-flex align-items-center gap-3">
                <a href="kelola_produk.php" class="wf-btn-light p-0 d-flex align-items-center justify-content-center text-decoration-none shadow-sm" style="width: 42px; height: 42px;">
                    <i class="bi bi-arrow-left fs-4 text-dark"></i>
                </a>
                <div class="d-flex align-items-center gap-2">
                    <div class="d-flex align-items-center justify-content-center overflow-hidden" style="width: 44px; height: 44px;">
                        <img src="../aset/logo.png" alt="Logo AN-NUR" class="w-100 h-100 object-fit-contain">
                    </div>
                    <div class="lh-xs">
                        <span class="text-uppercase fw-semibold text-muted d-block" style="font-size: 10px; letter-spacing: 1px;">Toko Sepatu</span>
                        <strong class="fw-bold text-dark" style="font-size: 20px; letter-spacing: -0.5px;">AN-NUR</strong>
                    </div>
                </div>
            </div>
        </header>

        <!-- MAIN CONTENT -->
        <main class="content-wrapper content-wrapper-narrow flex-grow-1 p-3 p-md-4 container" style="max-width: 800px;">
            <div class="mb-4">
                <h3 class="fw-bold mb-1" style="font-size: 26px; color: var(--text-dark);">Tambah Produk Baru</h3>
                <p class="text-muted mb-0 small">Masukkan informasi lengkap produk sepatu</p>
            </div>

            <form action="kelola_produk.php" method="POST" enctype="multipart/form-data">
                <div class="wf-box-solid p-4 p-md-5 mb-4 bg-white shadow-sm rounded-4">
                    
                    <!-- Input Unggah Foto Produk -->
                    <div class="mb-4 text-center">
                        <label class="form-label fw-bold text-uppercase d-block text-start mb-2" style="font-size: 11px; color: var(--text-dark);">Foto Produk</label>
                        <div class="p-4 border border-2 border-dashed rounded-4 bg-light d-flex flex-column align-items-center justify-content-center position-relative" style="border-color: var(--gold-border) !important; cursor: pointer;">
                            <i class="bi bi-cloud-arrow-up-fill display-5 mb-2" style="color: var(--gold-primary);"></i>
                            <span class="fw-bold text-dark" style="font-size: 14px;">Klik atau seret foto produk ke sini</span>
                            <small class="text-muted" style="font-size: 12px;">Format yang didukung: JPG, PNG, WEBP (Maks. 2MB)</small>
                            <input type="file" name="foto_produk" class="position-absolute top-0 start-0 w-100 h-100 opacity-0" style="cursor: pointer;" accept="image/*">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold text-uppercase" style="font-size: 11px; color: var(--text-dark);">Nama Produk</label>
                        <input type="text" name="nama_produk" class="form-control wf-box-solid py-2.5 px-3 shadow-none bg-light" placeholder="Contoh: Sepatu Sneakers..." required style="font-size: 14px;">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold text-uppercase" style="font-size: 11px; color: var(--text-dark);">SKU / Kode Barang</label>
                        <input type="text" name="sku" class="form-control wf-box-solid py-2.5 px-3 shadow-none bg-light" placeholder="Contoh: SPH-005" required style="font-size: 14px;">
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-bold text-uppercase" style="font-size: 11px; color: var(--text-dark);">Harga (Rp)</label>
                            <input type="number" name="harga" class="form-control wf-box-solid py-2.5 px-3 shadow-none bg-light" placeholder="250000" required style="font-size: 14px;">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-bold text-uppercase" style="font-size: 11px; color: var(--text-dark);">Stok Awal</label>
                            <input type="number" name="stok" class="form-control wf-box-solid py-2.5 px-3 shadow-none bg-light" placeholder="20" required style="font-size: 14px;">
                        </div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label fw-bold text-uppercase" style="font-size: 11px; color: var(--text-dark);">Deskripsi Produk</label>
                        <textarea name="deskripsi" class="form-control wf-box-solid p-3 shadow-none bg-light" rows="3" placeholder="Tuliskan detail bahan, ukuran, atau keunggulan sepatu..."></textarea>
                    </div>
                </div>

                <div class="d-grid gap-2 mb-5">
                    <button type="submit" class="btn wf-btn-dark py-3 fw-bold text-uppercase shadow-sm rounded-4" style="font-size: 14px; letter-spacing: 0.5px;">
                        <i class="bi bi-check-lg me-2"></i> Simpan Produk Baru
                    </button>
                    <a href="kelola_produk.php" class="btn wf-btn-light py-2.5 text-decoration-none text-center fw-bold shadow-sm rounded-4" style="font-size: 13px;">
                        Batal
                    </a>
                </div>
            </form>
        </main>
    </div>
    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>