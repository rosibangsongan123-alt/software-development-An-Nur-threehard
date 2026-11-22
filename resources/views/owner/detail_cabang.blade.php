<?php
// Halaman Riwayat Transaksi Cabang Real-time - Toko Sepatu An-Nur
$cabang_id = isset($_GET['cabang']) ? $_GET['cabang'] : 'pusat';

if ($cabang_id == 'pare') {
    $nama_cabang = "Cabang Pare";
} else {
    $nama_cabang = "Cabang Kediri (Pusat)";
}

// Simulasi data riwayat transaksi real-time kasir
$transaksi = [
    ["jam" => "14:25:10", "tanggal" => "26 Okt 2026", "jenis" => "Pemasukan", "keterangan" => "Penjualan Sepatu Sneakers Casual (SKU: SPH-001)", "nominal" => "+ Rp 250.000"],
    ["jam" => "13:10:45", "tanggal" => "26 Okt 2026", "jenis" => "Pemasukan", "keterangan" => "Penjualan Sepatu Running Sport Pro (SKU: SPH-002)", "nominal" => "+ Rp 375.000"],
    ["jam" => "11:05:20", "tanggal" => "26 Okt 2026", "jenis" => "Pengeluaran", "keterangan" => "Pembelian Perlengkapan Kebersihan Toko", "nominal" => "- Rp 75.000"],
    ["jam" => "09:30:15", "tanggal" => "26 Okt 2026", "jenis" => "Pemasukan", "keterangan" => "Penjualan Sepatu Formal Pantofel (SKU: SPH-003)", "nominal" => "+ Rp 450.000"]
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Transaksi <?php echo $nama_cabang; ?> - Toko Sepatu AN-NUR</title>
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
                <a href="total_omset.php" class="wf-btn-light p-0 d-flex align-items-center justify-content-center text-decoration-none shadow-sm" style="width: 42px; height: 42px;">
                    <i class="bi bi-arrow-left fs-4 text-dark"></i>
                </a>
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
            <!-- Real-time Clock Badge -->
            <div class="wf-box px-3 py-2 bg-white text-dark fw-bold shadow-sm rounded-3 border" style="font-size: 12px; border-color: var(--gold-border) !important;">
                <i class="bi bi-clock-fill me-1 text-warning"></i> <span id="realtime-clock">--:--:--</span>
            </div>
        </header>

        <!-- MAIN CONTENT -->
        <main class="content-wrapper flex-grow-1 p-3 p-md-4 container-fluid" style="max-width: 1400px;">
            <div class="mb-4">
                <h3 class="fw-bold mb-1" style="font-size: 26px; color: var(--text-dark);">Riwayat Transaksi</h3>
                <p class="text-muted mb-0 small">Pemasukan & Pengeluaran Kasir - <?php echo $nama_cabang; ?></p>
            </div>

            <!-- Ringkasan Kas -->
            <div class="row g-3 mb-4">
                <div class="col-12 col-md-6">
                    <div class="wf-box-solid p-4 bg-white shadow-sm rounded-4">
                        <small class="text-muted fw-bold d-block mb-1" style="font-size: 11px;">TOTAL PEMASUKAN</small>
                        <span class="fw-bold fs-5 text-success">+ Rp 18.500.000</span>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="wf-box-solid p-4 bg-white shadow-sm rounded-4">
                        <small class="text-muted fw-bold d-block mb-1" style="font-size: 11px;">TOTAL PENGELUARAN</small>
                        <span class="fw-bold fs-5 text-danger">- Rp 250.000</span>
                    </div>
                </div>
            </div>

            <!-- Daftar Riwayat Real-time -->
            <h5 class="fw-bold mb-3 text-dark" style="font-size: 18px;">Log Transaksi Real-time</h5>

            <?php foreach ($transaksi as $trx): ?>
                <div class="wf-box-solid p-4 mb-3 bg-white shadow-sm rounded-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="badge px-3 py-2 text-white fw-bold shadow-sm" style="background-color: <?php echo ($trx['jenis'] == 'Pemasukan') ? 'var(--text-dark)' : '#dc3545'; ?>; font-size: 11px; border-radius: 8px;">
                            <?php echo $trx['jenis']; ?>
                        </span>
                        <small class="text-muted fw-bold" style="font-size: 12px;">
                            <i class="bi bi-clock me-1"></i><?php echo $trx['jam']; ?> &bull; <?php echo $trx['tanggal']; ?>
                        </small>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top" style="border-color: var(--gold-border) !important;">
                        <p class="mb-0 text-dark fw-semibold" style="font-size: 14px;"><?php echo $trx['keterangan']; ?></p>
                        <strong class="<?php echo ($trx['jenis'] == 'Pemasukan') ? 'text-success' : 'text-danger'; ?>" style="font-size: 15px;">
                            <?php echo $trx['nominal']; ?>
                        </strong>
                    </div>
                </div>
            <?php endforeach; ?>

            <div class="d-grid mt-4 mb-5">
                <a href="total_omset.php" class="btn wf-btn-dark py-3 text-decoration-none text-center fw-bold shadow-sm rounded-4" style="font-size: 14px;">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Total Omset
                </a>
            </div>
        </main>
    </div>

    <!-- Script Jam Real-time -->
    <script>
        function updateClock() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            document.getElementById('realtime-clock').textContent = `${hours}:${minutes}:${seconds}`;
        }
        setInterval(updateClock, 1000);
        updateClock();
    </script>
    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>