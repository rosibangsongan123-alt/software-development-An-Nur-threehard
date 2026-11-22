<?php
// Halaman Login - Toko Sepatu An-Nur
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Toko Sepatu AN-NUR</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}?v=<?php echo time(); ?>">
</head>
<body class="min-vh-100 d-flex align-items-center justify-content-center m-0">

    <!-- MAIN CONTENT (Tanpa Kotak/Card Putih di Tengah) -->
    <main class="d-flex align-items-center justify-content-center py-5 w-100">
        <div class="p-4 p-sm-5 w-100" style="max-width: 480px;">
            
            <!-- Logo & Title -->
            <div class="d-flex flex-column align-items-center text-center mb-4">
                <div class="rounded-circle d-flex align-items-center justify-content-center mb-3 overflow-hidden shadow-sm bg-white" style="width: 150px; height: 150px; border: 2px solid var(--gold-border);">
                    <img src="{{ asset('aset/logo.png') }}" alt="Logo Toko Sepatu AN-NUR" class="img-fluid w-100 h-100 object-fit-cover">
                </div>
                <span class="text-uppercase fw-bold tracking-widest text-muted" style="font-size: 14px; letter-spacing: 2px;">Toko Sepatu</span>
                <span class="fs-3 fw-bold tracking-widest text-dark" style="letter-spacing: 1px;">AN-NUR</span>
            </div>

            <!-- Form Login: dikirim ke POST /api/login (Issue #6), bukan ke file .php statis -->
            <form id="formLogin" method="POST" novalidate>

                <!-- Pesan error dari API -->
                <div id="alertLogin" class="alert alert-danger py-2 px-3 mb-3 d-none" style="font-size: 13px;" role="alert"></div>
                
                <!-- Input Email -->
                <div class="mb-3">
                    <label for="email" class="form-label fw-bold text-uppercase" style="font-size: 11px; letter-spacing: 0.5px;">Email</label>
                    <div class="wf-box-solid d-flex align-items-center px-3 py-1 bg-white">
                        <span class="text-muted me-2">
                            <i class="bi bi-envelope fs-5"></i>
                        </span>
                        <input type="email" id="email" name="email" class="form-control wf-input-custom py-2 border-0 shadow-none bg-transparent" placeholder="contoh@email.com" required style="font-size: 13px;">
                    </div>
                </div>

                <!-- Input Kata Sandi -->
                <div class="mb-3">
                    <label for="password" class="form-label fw-bold text-uppercase" style="font-size: 11px; letter-spacing: 0.5px;">Kata Sandi</label>
                    <div class="wf-box-solid d-flex align-items-center px-3 py-1 bg-white">
                        <span class="text-muted me-2">
                            <i class="bi bi-lock fs-5"></i>
                        </span>
                        <input type="password" id="password" name="password" class="form-control wf-input-custom py-2 border-0 shadow-none bg-transparent" placeholder="••••••••••••" required style="font-size: 13px;">
                        <button type="button" class="btn border-0 text-muted p-0 ms-2 bg-transparent shadow-none" id="togglePassword">
                            <i class="bi bi-eye-slash fs-5" id="toggleIcon"></i>
                        </button>
                    </div>
                </div>

                <!-- Remember Me & Lupa Sandi -->
                <div class="d-flex align-items-center justify-content-between mb-4" style="font-size: 13px;">
                    <div class="form-check">
                        <input class="form-check-input cursor-pointer" type="checkbox" id="rememberMe" name="remember">
                        <label class="form-check-label fw-bold text-dark cursor-pointer" for="rememberMe">
                            Ingat Saya
                        </label>
                    </div>
                    <a href="#" class="text-dark fw-bold text-decoration-none">Lupa Kata Sandi?</a>
                </div>

                <!-- Tombol Submit -->
                <button type="submit" class="btn wf-btn-dark w-100 py-3 text-uppercase fw-bold tracking-wider text-center text-decoration-none d-block shadow-sm" style="font-size: 12px; letter-spacing: 1px;">
                    Masuk Sekarang
                </button>
            </form>
        </div>
    </main>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Script Toggle Password Visibility -->
    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');
        const toggleIcon = document.querySelector('#toggleIcon');

        togglePassword.addEventListener('click', function () {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            toggleIcon.classList.toggle('bi-eye');
            toggleIcon.classList.toggle('bi-eye-slash');
        });

        // ---- Submit ke POST /api/login (Backend Issue #6) ----
        const form = document.querySelector('#formLogin');
        const alertBox = document.querySelector('#alertLogin');
        const btnMasuk = form.querySelector('button[type="submit"]');

        function tampilkanPesan(pesan) {
            alertBox.textContent = pesan;
            alertBox.classList.remove('d-none');
        }

        form.addEventListener('submit', async function (event) {
            event.preventDefault();
            alertBox.classList.add('d-none');
            btnMasuk.disabled = true;
            btnMasuk.textContent = 'Memproses...';

            try {
                const respons = await fetch('/api/login', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: JSON.stringify({
                        email: document.querySelector('#email').value,
                        password: document.querySelector('#password').value,
                        remember: document.querySelector('#rememberMe').checked,
                    }),
                });

                const hasil = await respons.json();

                if (!respons.ok || hasil.success !== true) {
                    tampilkanPesan(hasil.message || 'Login gagal. Coba lagi.');
                    return;
                }

                // Token disimpan di localStorage, dipakai lagi sebagai header Authorization.
                localStorage.setItem('annur_token', hasil.data.token);
                localStorage.setItem('annur_user', JSON.stringify(hasil.data.user));
                window.location.href = hasil.data.user.redirect_to;
            } catch (error) {
                tampilkanPesan('Tidak dapat menghubungi server. Periksa koneksi lalu coba lagi.');
            } finally {
                btnMasuk.disabled = false;
                btnMasuk.textContent = 'Masuk Sekarang';
            }
        });
    </script>
</body>
</html>