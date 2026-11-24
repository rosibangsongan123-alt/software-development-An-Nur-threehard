<?php
// Halaman Dashboard Owner - Toko Sepatu An-Nur
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'minggu';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Owner - Toko Sepatu AN-NUR</title>
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
                        <img src="{{ asset('aset/logo.png') }}" alt="Logo AN-NUR" class="w-100 h-100 object-fit-contain">
                    </div>
                    <div class="lh-xs">
                        <span class="text-uppercase fw-semibold text-muted d-block" style="font-size: 10px; letter-spacing: 1px;">Toko Sepatu</span>
                        <strong class="fw-bold text-dark" style="font-size: 20px; letter-spacing: -0.5px;">AN-NUR</strong>
                    </div>
                </div>
            </div>
            <div class="d-flex align-items-center gap-2">
                <!-- Tombol Lonceng Notifikasi -->
                <a href="notifikasi.php" class="wf-btn-light position-relative p-0 d-flex align-items-center justify-content-center text-decoration-none shadow-sm" style="width: 42px; height: 42px;">
                    <i class="bi bi-bell fs-5"></i>
                    <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle"></span>
                </a>
                <div class="vr mx-1 my-auto" style="height: 24px;"></div>
                <!-- Tombol Profil -->
                <button class="wf-btn-dark p-0 d-flex align-items-center justify-content-center shadow-sm" style="width: 42px; height: 42px;">
                    <i class="bi bi-person-fill fs-5"></i>
                </button>
            </div>
        </header>

        <!-- MAIN CONTENT -->
        <main class="content-wrapper flex-grow-1 p-3 p-md-4 container-fluid" style="max-width: 1400px;">
            <div class="mb-4">
                <h3 class="fw-bold mb-1" style="font-size: 26px; color: var(--text-dark);">Dashboard Owner</h3>
                <p class="text-muted mb-0 small"><i class="bi bi-calendar3 me-1"></i> Hari ini: <strong><?php echo date('d M Y'); ?></strong></p>
            </div>

            <!-- Ringkasan Omset (Tombol Panah Diperkecil & Seluruh Kotak Berfungsi sebagai Link) -->
            <div class="row g-3 mb-4">
                <div class="col-12">
                    <a href="total_omset.php" class="wf-box-solid p-4 d-block text-decoration-none text-dark shadow-sm bg-white rounded-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted fw-bold d-block mb-1" style="font-size: 13px;">Total Omset</small>
                                <h3 class="fw-bold mb-0 text-dark" style="font-size: 28px;">Rp 4.250.000</h3>
                            </div>
                            <!-- Tombol Panah (Jarum) Diperkecil Ukurannya -->
                            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; background-color: var(--gold-soft-bg); border: 1px solid var(--gold-border);">
                                <i class="bi bi-arrow-right text-warning fs-5"></i>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Menu Kelola Owner (4 Kotak Rapi) -->
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-4 col-lg">
                    <a href="{{ route('owner.produk') }}" class="wf-box-solid p-3 p-lg-4 rounded-4 d-flex flex-column justify-content-center align-items-center text-center shadow-sm h-100 text-decoration-none text-dark bg-white" style="min-height: 130px;">
                        <i class="bi bi-box-seam fs-1 mb-2" style="color: var(--gold-primary);"></i>
                        <span class="fw-bold" style="font-size: 14px;">Kelola Produk</span>
                    </a>
                </div>
                <div class="col-6 col-md-4 col-lg">
                    <a href="kelola_akun.php" class="wf-box-solid p-3 p-lg-4 rounded-4 d-flex flex-column justify-content-center align-items-center text-center shadow-sm h-100 text-decoration-none text-dark bg-white" style="min-height: 130px;">
                        <i class="bi bi-people-fill fs-1 mb-2" style="color: var(--gold-primary);"></i>
                        <span class="fw-bold" style="font-size: 14px;">Kelola Akun</span>
                    </a>
                </div>
                <div class="col-6 col-md-4 col-lg">
                    <a href="kelola_barcode.php" class="wf-box-solid p-3 p-lg-4 rounded-4 d-flex flex-column justify-content-center align-items-center text-center shadow-sm h-100 text-decoration-none text-dark bg-white" style="min-height: 130px;">
                        <i class="bi bi-upc-scan fs-1 mb-2" style="color: var(--gold-primary);"></i>
                        <span class="fw-bold" style="font-size: 14px;">Kelola Barcode</span>
                    </a>
                </div>
                <div class="col-6 col-md-4 col-lg">
                    <a href="total_omset.php" class="wf-box-solid p-3 p-lg-4 rounded-4 d-flex flex-column justify-content-center align-items-center text-center shadow-sm h-100 text-decoration-none text-dark bg-white" style="min-height: 130px;">
                        <i class="bi bi-wallet2 fs-1 mb-2" style="color: var(--gold-primary);"></i>
                        <span class="fw-bold" style="font-size: 14px;">Kelola Omset</span>
                    </a>
                </div>
            <div class="col-6 col-md-4 col-lg">
                    <a href="{{ route('owner.cabang') }}" class="wf-box-solid p-3 p-lg-4 rounded-4 d-flex flex-column justify-content-center align-items-center text-center shadow-sm h-100 text-decoration-none text-dark bg-white" style="min-height: 130px;">
                        <i class="bi bi-shop fs-1 mb-2" style="color: var(--gold-primary);"></i>
                        <span class="fw-bold" style="font-size: 14px;">Kelola Cabang</span>
                    </a>
                </div>
            </div>
            <div class="wf-box-solid p-4 mb-3 bg-white shadow-sm">
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-3 gap-2">
                    <div>
                        <span class="fw-bold d-block" style="font-size: 16px;">Grafik Penjualan</span>
                        <small class="text-muted">Analisis Performa Toko</small>
                    </div>
                    <div class="btn-group wf-box p-1 bg-white border" role="group" style="font-size: 12px; border-color: var(--gold-border) !important; border-radius: 10px;">
                        <a href="dasboard.php?filter=hari" class="btn btn-sm border-0 fw-bold px-3 <?php echo ($filter == 'hari') ? 'wf-btn-dark' : 'text-dark'; ?>">Hari</a>
                        <a href="dasboard.php?filter=minggu" class="btn btn-sm border-0 fw-bold px-3 <?php echo ($filter == 'minggu') ? 'wf-btn-dark' : 'text-dark'; ?>">Minggu</a>
                        <a href="dasboard.php?filter=bulan" class="btn btn-sm border-0 fw-bold px-3 <?php echo ($filter == 'bulan') ? 'wf-btn-dark' : 'text-dark'; ?>">Bulan</a>
                        <a href="dasboard.php?filter=tahun" class="btn btn-sm border-0 fw-bold px-3 <?php echo ($filter == 'tahun') ? 'wf-btn-dark' : 'text-dark'; ?>">Tahun</a>
                    </div>
                </div>

                <div class="chart-bar-container">
                    <?php if ($filter == 'hari'): ?>
                        <?php $labels = ['08:00', '10:00', '12:00', '14:00', '16:00', '18:00', '20:00'];
                        $heights = [20, 45, 75, 50, 65, 85, 40]; ?>
                    <?php elseif ($filter == 'bulan'): ?>
                        <?php $labels = ['Minggu 1', 'Minggu 2', 'Minggu 3', 'Minggu 4'];
                        $heights = [60, 80, 55, 90]; ?>
                    <?php elseif ($filter == 'tahun'): ?>
                        <?php $labels = ['Jan', 'Mar', 'Mei', 'Jul', 'Sep', 'Nov'];
                        $heights = [40, 55, 70, 65, 85, 95]; ?>
                    <?php else: ?>
                        <?php $labels = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];
                        $heights = [45, 30, 50, 25, 60, 75, 35]; ?>
                    <?php endif; ?>

                    <?php foreach ($labels as $index => $label): 
                        $h = $heights[$index % count($heights)];
                    ?>
                        <div class="chart-bar-wrapper">
                            <div class="chart-bar" style="height: <?php echo $h; ?>%;"></div>
                            <small class="fw-bold text-muted mt-2" style="font-size: 12px;"><?php echo $label; ?></small>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </main>
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
                <a href="dasboard.php" class="nav-link-wf active">
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