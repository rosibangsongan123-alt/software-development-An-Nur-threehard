<?php
// Halaman Kelola Akun Owner - Toko Sepatu An-Nur
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Akun - Toko Sepatu AN-NUR</title>
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
                <button class="wf-btn-light p-0 d-flex align-items-center justify-content-center shadow-sm" 
                        type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarOwner" aria-controls="sidebarOwner"
                        style="width: 42px; height: 42px;">
                    <i class="bi bi-list fs-4"></i>
                </button>
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
            <div class="d-flex align-items-center gap-2">
                <a href="notifikasi.php" class="wf-btn-light position-relative p-0 d-flex align-items-center justify-content-center text-decoration-none shadow-sm" style="width: 42px; height: 42px;">
                    <i class="bi bi-bell fs-5"></i>
                    <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle"></span>
                </a>
                <div class="vr mx-1 my-auto" style="height: 24px;"></div>
                <button class="wf-btn-dark p-0 d-flex align-items-center justify-content-center shadow-sm" style="width: 42px; height: 42px;">
                    <i class="bi bi-person-fill fs-5"></i>
                </button>
            </div>
        </header>

        <!-- MAIN CONTENT -->
        <main class="content-wrapper flex-grow-1 p-3 p-md-4 container-fluid" style="max-width: 1400px;">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="fw-bold mb-1" style="font-size: 26px; color: var(--text-dark);">Kelola Akun Kasir</h3>
                    <p class="text-muted mb-0 small">Manajemen akun petugas toko</p>
                </div>
                <a href="tambah_akun.php" class="btn wf-btn-dark px-3 py-2 fw-bold text-decoration-none shadow-sm d-flex align-items-center gap-1" style="font-size: 13px;">
                    <i class="bi bi-plus-lg"></i> Tambah Akun
                </a>
            </div>

            <div class="wf-box-solid p-4 mb-3 bg-white shadow-sm rounded-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <strong style="font-size: 16px;" class="text-dark fw-bold">Petugas Kasir Pusat</strong>
                    <span class="badge px-3 py-2 text-white fw-bold shadow-sm" style="background-color: #198754; font-size: 12px; border-radius: 8px;">Aktif</span>
                </div>
                <small class="text-muted d-block" style="font-size: 13px;">
                    <i class="bi bi-envelope me-1"></i> kasir.annur@store.com &bull; <i class="bi bi-geo-alt ms-2 me-1"></i> Kediri
                </small>
            </div>

            <div class="d-grid mt-4 mb-5">
                <a href="dasboard.php" class="btn wf-btn-dark py-3 text-decoration-none text-center fw-bold shadow-sm rounded-4" style="font-size: 14px;">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Dashboard
                </a>
            </div>
        </main>
    </div>

    <!-- SIDEBAR OWNER -->
    <div class="offcanvas offcanvas-start offcanvas-sidebar bg-white" tabindex="-1" id="sidebarOwner" aria-labelledby="sidebarOwnerLabel">
        <div class="offcanvas-header border-bottom px-4 py-3" style="border-color: var(--gold-border) !important;">
            <div class="d-flex align-items-center gap-2">
                <div class="d-flex align-items-center justify-content-center overflow-hidden" style="width: 40px; height: 40px;">
                    <img src="../aset/logo.png" alt="Logo AN-NUR" class="w-100 h-100 object-fit-contain">
                </div>
                <h5 class="offcanvas-title fw-bold mb-0 text-dark" id="sidebarOwnerLabel" style="font-size: 18px; letter-spacing: 0.5px;">AN-NUR (OWNER)</h5>
            </div>
            <button type="button" class="btn-close text-reset shadow-none" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body px-3 py-4 d-flex flex-column justify-content-between">
            <div class="nav flex-column">
                <a href="dasboard.php" class="nav-link-wf">
                    <i class="bi bi-grid-1x2-fill fs-5"></i>
                    <span>Dashboard</span>
                </a>
                <a href="kelola_produk.php" class="nav-link-wf">
                    <i class="bi bi-box-seam fs-5"></i>
                    <span>Kelola Produk</span>
                </a>
                <a href="kelola_akun.php" class="nav-link-wf active">
                    <i class="bi bi-people-fill fs-5"></i>
                    <span>Kelola Akun</span>
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
    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>