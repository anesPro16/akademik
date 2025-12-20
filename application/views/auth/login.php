<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-name" content="<?= $this->security->get_csrf_token_name(); ?>">
  <meta name="csrf-hash" content="<?= $this->security->get_csrf_hash(); ?>">
  <title>Login Platform Edukasi</title>
  <!-- Favicons -->
  <link href="<?= base_url('assets/img/favicon.png'); ?>" rel="icon">
  <link href="<?= base_url('assets/img/apple-touch-icon.png'); ?>" rel="apple-touch-icon">
  <link href="<?= base_url('assets/vendor/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
  <link rel="stylesheet" href="<?= base_url('assets/vendor/bootstrap-icons/bootstrap-icons.css') ?>">
  <link href="<?= base_url('assets/css/style.css') ?>" rel="stylesheet">
  <style>
    /* Opsional: Menambahkan sedikit gaya untuk body */
    body {
      background-color: #f0f2f5;
    }
  </style>
</head>

<body>

  <div class="container py-4">
    <div class="row justify-content-center">
      <div class="col-md-5 col-lg-5">
        <div class="card shadow-lg border-0 rounded-4">
          <div class="card-body p-4 p-sm-5">

            <div class="text-center mb-4">
              <img src="<?= base_url('assets/img/logo.png') ?>" alt="Logo Edukasi" class="mb-3" style="width: 120px;">
              <h3 class="fw-bold">Selamat Datang 👋</h3>
              <p class="text-muted"> AYO Lanjutkan perjalanan belajarmu hari ini.</p>
            </div>

            <?php if ($this->session->flashdata('error')): ?>
              <div class="alert alert-danger d-flex align-items-center" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <div>
                  <?= $this->session->flashdata('error'); ?>
                </div>
              </div>
            <?php endif; ?>

            <?php if ($this->session->flashdata('success')): ?>
              <div class="alert alert-success d-flex align-items-center" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <div>
                  <?= $this->session->flashdata('success'); ?>
                </div>
              </div>
            <?php endif; ?>

            <!-- <form action="</?= site_url('auth'); ?>" method="POST"> -->
             <?= form_open('auth'); ?>
              <div class="mb-3">
                <label for="username" class="form-label">Username atau Email</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                  <input type="text" class="form-control" id="username" name="username" placeholder="cth: nama.siswa" >
                </div>
                  <?= form_error('username', '<small class="text-danger pl-3">', '</small>'); ?>
              </div>

              <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                  <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password Anda" >
                </div>
                  <?= form_error('password', '<small class="text-danger pl-3">', '</small>'); ?>
              </div>

              <button type="submit" class="btn btn-primary w-100 mt-2 fw-bold">Masuk</button>
            <!-- </form> -->
            <?= form_close(); ?>

            <div class="text-center mt-4">
              <p class="text-muted">Belum punya akun? <a href="<?= site_url('auth/register'); ?>" class="fw-bold text-decoration-none">Daftar sekarang</a></p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="<?= base_url('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
</body>

</html>