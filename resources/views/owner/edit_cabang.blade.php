<?php
// Halaman Edit Cabang - Toko Sepatu An-Nur
$cabang_id = isset($_GET['cabang']) ? $_GET['cabang'] : 'pusat';

// Simulasi data berdasarkan parameter cabang
if ($cabang_id == 'pare') {
    $nama_cabang = "Cabang Pare";
    $lokasi = "Pare, Kediri";
    $keterangan = "Performa stabil &bull; Pertumbuhan positif";
} else {
    $nama_cabang = "Cabang Kediri (Pusat)";
    $lokasi = "Pusat Kota Kediri";
    $keterangan = "Performa stabil &bull; Kontribusi dominan";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Cabang - Toko Sepatu AN-NUR</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="app-container">
        <!-- HEADER -->
        <header class="d-flex justify-content-between align-items-center px-4 py-3 bg-white border-bottom border-dark border-2">
            <div class="d-flex align-items-center gap-3">
                <a href="total_omset.php" class="wf-btn-light p-0 d-flex align-items-center justify-content-center text-decoration-none shadow-none" style="width: 40px; height: 40px;">
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
                <h3 class="fw-bold mb-0">Edit Cabang Toko</h3>
                <small class="fw-bold">Perbarui informasi dan keterangan cabang</small>
            </div>

            <form action="total_omset.php" method="POST">
                <div class="wf-box p-4 mb-4 bg-white">
                    <div class="mb-3">
                        <label class="form-label fw-bold text-uppercase" style="font-size: 11px;">Nama Cabang</label>
                        <input type="text" name="nama_cabang" class="form-control wf-box-solid py-2 px-3 shadow-none" value="<?php echo htmlspecialchars($nama_cabang); ?>" required style="font-size: 14px;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold text-uppercase" style="font-size: 11px;">Lokasi / Alamat</label>
                        <input type="text" name="lokasi" class="form-control wf-box-solid py-2 px-3 shadow-none" value="<?php echo htmlspecialchars($lokasi); ?>" required style="font-size: 14px;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold text-uppercase" style="font-size: 11px;">Keterangan / Status Performa</label>
                        <textarea name="keterangan" class="form-control wf-box-solid p-3 shadow-none" rows="3"><?php echo htmlspecialchars($keterangan); ?></textarea>
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn wf-btn-dark py-3 fw-bold text-uppercase shadow-sm" style="font-size: 14px; letter-spacing: 0.5px;">
                        <i class="bi bi-save-fill me-2"></i> Simpan Perubahan Cabang
                    </button>
                    <a href="total_omset.php" class="btn wf-btn-light py-2 text-decoration-none text-center fw-bold" style="font-size: 13px;">
                        Batal
                    </a>
                </div>
            </form>
        </main>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>