<div class="container-fluid">

    <div class="card shadow">
        <div class="p-3">
            <h5 class="mt-2 font-weight-bold"><?= htmlspecialchars($kelas->name, ENT_QUOTES, 'UTF-8'); ?></h5>
            <div class="mb-2">
                <strong>Guru Pengampu:</strong> <?= htmlspecialchars($kelas->teacher_name, ENT_QUOTES, 'UTF-8'); ?>
            </div>
            <p><strong>Kode Kelas:</strong> <span class="badge bg-primary"><?= htmlspecialchars($kelas->code, ENT_QUOTES, 'UTF-8'); ?></span></p>
            <p>Jumlah Siswa: <span id="jumlah-siswa" class="badge bg-info"><?= $kelas->student_count; ?></span></p>
        </div>
        
        <?php if(isset($role_controller) && $role_controller == 'admin'): ?>
             <a href="<?= base_url('admin/dashboard/classes') ?>" class="btn btn-secondary ml-3 mb-3">← Kembali ke Kelola Kelas</a>
        <?php else: ?>
             <a href="<?= base_url('guru/dashboard') ?>" class="btn btn-secondary ml-3 mb-3">← Kembali ke Dashboard</a>
             <a href="<?= base_url('guru/pbl/index/' . $kelas->id); ?>" class="btn btn-outline-primary mt-3">
          <i class="fas fa-lightbulb"></i> Tahap 1 – Orientasi Masalah (PBL)
      </a>
        <?php endif; ?>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3 mt-4">
        <h5 class="m-0 font-weight-bold text-primary">Daftar Siswa</h5>
        
        <!-- Tombol Tambah Siswa HANYA MUNCUL jika diizinkan (Admin) -->
        <?php if (isset($can_manage_students) && $can_manage_students === true): ?>
        <button class="btn btn-primary btn-sm" id="btnAddStudent" data-bs-toggle="modal" data-bs-target="#siswaModal">
            <i class="fas fa-user-plus"></i> Tambah Siswa
        </button>
        <?php endif; ?>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body" id="siswaTableContainer">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="siswaTable" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 5%;">No</th>
                            <th>Nama</th>
                            <th>Username</th>
                            <th>Email</th>
                            <!-- Kolom Aksi Hanya Muncul Jika Boleh Mengelola -->
                            <?php if (isset($can_manage_students) && $can_manage_students === true): ?>
                                <th style="width: 15%;">Aksi</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<!-- Modal hanya dirender jika boleh mengelola -->
<?php if (isset($can_manage_students) && $can_manage_students === true): ?>
<div class="modal fade" id="siswaModal" tabindex="-1" aria-labelledby="siswaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <form id="studentForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="siswaModalLabel">Tambah Siswa ke Kelas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">

                    <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                    <input type="hidden" name="class_id" id="classIdHidden" value="<?= $kelas->id; ?>">
                    
                    <div class="mb-3">
                        <label for="studentSelect" class="form-label">Pilih Siswa</label>
                        <select class="form-select" id="studentSelect" name="student_id" required>
                            <option value="">-- Pilih Siswa --</option>
                            <?php foreach($siswa_list as $s): ?>
                                <option value="<?= $s->id; ?>">
                                    <?= htmlspecialchars($s->name, ENT_QUOTES, 'UTF-8'); ?> (<?= htmlspecialchars($s->username, ENT_QUOTES, 'UTF-8'); ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="form-text">Menampilkan semua siswa aktif.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="btnSaveStudent">Simpan</button>
                </div>
            </form>

        </div>
    </div>
</div>
<?php endif; ?>

<script>
    window.BASE_URL = '<?= base_url() ?>';
    window.CSRF_TOKEN_NAME = '<?= $this->security->get_csrf_token_name(); ?>';
    
    // Konfigurasi Dinamis dari Controller
    window.CURRENT_CLASS_ID = '<?= $kelas->id; ?>';
    // Apakah user saat ini boleh menambah/menghapus siswa?
    window.CAN_MANAGE_STUDENTS = <?= (isset($can_manage_students) && $can_manage_students === true) ? 'true' : 'false' ?>;
    // Controller mana yang dipakai? 'admin' atau 'guru'
    window.ROLE_CONTROLLER = '<?= isset($role_controller) ? $role_controller : 'guru' ?>';
</script>

<script type="module" src="<?= base_url('assets/js/class_detail.js') ?>"></script>