<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Akun</title>

    <link href="<?= base_url('assets/vendor/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/vendor/bootstrap-icons/bootstrap-icons.css') ?>" rel="stylesheet">

    <style>
    body {
        min-height: 100vh;
        background: linear-gradient(rgba(0, 0, 0, 0.45),
                rgba(0, 0, 0, 0.45)),
            url('https://images.unsplash.com/photo-1523240795612-9a054b0db644?q=80&w=2070&auto=format&fit=crop');
        background-size: cover;
        background-position: center;
        display: flex;
        align-items: center;
    }

    .register-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(6px);
        border-radius: 18px;
    }

    .form-label {
        font-weight: 500;
    }

    .password-toggle {
        cursor: pointer;
    }

    .progress {
        height: 5px;
    }
    </style>
</head>

<body>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7">

                <div class="card register-card shadow border-0">
                    <div class="card-body p-4 p-md-5">

                        <!-- HEADER -->
                        <div class="text-center mb-4">
                            <h4 class="fw-bold mb-1">Buat Akun Baru</h4>
                            <p class="text-muted small">Daftar untuk mulai belajar bersama RiyonClass</p>
                        </div>

                        <!-- ALERT -->
                        <?php if ($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger small">
                            <?= $this->session->flashdata('error'); ?>
                        </div>
                        <?php endif; ?>

                        <!-- FORM -->
                        <form action="<?= site_url('auth/register_action'); ?>" method="POST">

                            <div class="mb-3">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control" name="name" value="<?= set_value('name'); ?>"
                                    placeholder="Nama lengkap">
                                <?= form_error('name', '<small class="text-danger">', '</small>'); ?>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" value="<?= set_value('email'); ?>"
                                    placeholder="email@contoh.com">
                                <?= form_error('email', '<small class="text-danger">', '</small>'); ?>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Username</label>
                                <input type="text" class="form-control" name="username"
                                    value="<?= set_value('username'); ?>" placeholder="username">
                                <?= form_error('username', '<small class="text-danger">', '</small>'); ?>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password" name="password"
                                        placeholder="Minimal 8 karakter">
                                    <span class="input-group-text password-toggle" id="togglePassword">
                                        <i class="bi bi-eye-slash"></i>
                                    </span>
                                </div>

                                <div class="progress mt-2">
                                    <div id="password-strength-bar" class="progress-bar"></div>
                                </div>
                                <small id="password-strength-text" class="text-muted"></small>

                                <?= form_error('password', '<small class="text-danger">', '</small>'); ?>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Konfirmasi Password</label>
                                <input type="password" class="form-control" name="password_confirm"
                                    placeholder="Ulangi password">
                            </div>

                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="termsCheck">
                                <label class="form-check-label small">
                                    Saya menyetujui <a href="#" class="text-decoration-none">Syarat & Ketentuan</a>
                                </label>
                            </div>

                            <button type="submit" id="register-btn" class="btn btn-primary w-100 fw-semibold" disabled>
                                Daftar
                            </button>

                        </form>

                        <div class="text-center mt-4 small">
                            Sudah punya akun?
                            <a href="<?= site_url('auth'); ?>" class="fw-semibold text-decoration-none">
                                Masuk
                            </a>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="<?= base_url('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>

    <script>
    const togglePassword = document.getElementById('togglePassword');
    const password = document.getElementById('password');
    const bar = document.getElementById('password-strength-bar');
    const text = document.getElementById('password-strength-text');
    const terms = document.getElementById('termsCheck');
    const btn = document.getElementById('register-btn');

    togglePassword.onclick = () => {
        password.type = password.type === 'password' ? 'text' : 'password';
        togglePassword.firstElementChild.classList.toggle('bi-eye');
        togglePassword.firstElementChild.classList.toggle('bi-eye-slash');
    };

    password.oninput = () => {
        let s = 0;
        if (password.value.length >= 8) s++;
        if (/[A-Z]/.test(password.value)) s++;
        if (/[a-z]/.test(password.value)) s++;
        if (/[0-9]/.test(password.value)) s++;
        if (/[^A-Za-z0-9]/.test(password.value)) s++;

        const w = (s / 5) * 100;
        bar.style.width = w + '%';
        bar.className = 'progress-bar ' +
            (s < 3 ? 'bg-danger' : s === 3 ? 'bg-warning' : 'bg-success');
        text.textContent = s ? `Kekuatan password` : '';
    };

    terms.onchange = () => btn.disabled = !terms.checked;
    </script>

</body>

</html>