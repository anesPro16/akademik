<div class="container-fluid">
    <div class="row">
        <?php if (!empty($sekolah_list)): ?>
            <?php foreach ($sekolah_list as $sekolah): ?>
                <div class="col-lg-6 col-xl-4 mb-4">
                    
                    <div class="card shadow">
                        <div class="card-body">
                            <h5 class="card-title fw-bold text-primary"><?= htmlspecialchars($sekolah->name, ENT_QUOTES, 'UTF-8'); ?></h5>
                            <p class="card-text"><?= htmlspecialchars($sekolah->code, ENT_QUOTES, 'UTF-8'); ?></p>
                        </div>
                        <div class="card-footer bg-light d-flex justify-content-end">
                            <a href="<?= base_url('siswa/dashboard/class_detail/' . $sekolah->id) ?>" class="btn btn-primary btn-icon-split">
                                <span class="icon text-white-50">
                                    <i class="fas fa-arrow-right"></i>
                                </span>
                                <span class="text">Masuk & Lihat Kelas</span>
                            </a>
                        </div>
                    </div>
                    
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-info" role="alert">
                    Anda belum terhubung dengan sekolah mana pun. Silakan hubungi administrator.
                </div>
            </div>
        <?php endif; ?>
    </div>

</div>