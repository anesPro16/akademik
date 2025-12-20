<style>
  /* --- Table --- */
.table-responsive {
  overflow-x: auto !important;
  -webkit-overflow-scrolling: touch !important;
}

#questionTable {
  min-width: 720px !important; /* trigger horizontal scroll on mobile */
  border: 1px solid #dee2e6 !important;
}

#questionTable thead th,
#questionTable tbody td {
  border: 1px solid #dee2e6 !important;
  vertical-align: middle !important;
  transition: background-color .2s ease !important;
}

#questionTable tbody tr {
  transition: background-color .2s ease, transform .15s ease !important;
}

#questionTable tbody tr:hover {
  background-color: #f8f9fa !important;
  transform: scale(1.002) !important;
}

#submissionsTable {
  min-width: 720px !important; /* trigger horizontal scroll on mobile */
  border: 1px solid #dee2e6 !important;
}

#submissionsTable thead th,
#submissionsTable tbody td {
  border: 1px solid #dee2e6 !important;
  vertical-align: middle !important;
  transition: background-color .2s ease !important;
}

#submissionsTable tbody tr {
  transition: background-color .2s ease, transform .15s ease !important;
}

#submissionsTable tbody tr:hover {
  background-color: #f8f9fa !important;
  transform: scale(1.002) !important;
}

.aksi{
  width: 20%;
}
/* --- Mobile optimization --- */
@media (max-width: 576px) {
  .card-header h5 {
    font-size: 1rem !important;
  }

  .btn-add-quiz {
    font-size: .8rem !important;
  }

  .aksi{
    width: 180px;
  }
}

</style>

<div class="container py-4">

  <!-- Header & Import/Export Buttons sama seperti sebelumnya ... -->
  <div class="d-flex justify-content-between align-items-center mb-3">
    <!-- <div>
      <p class="text-muted">KUIS: <?= htmlspecialchars($quiz->description, ENT_QUOTES, 'UTF-8'); ?></p>
    </div> -->
    <a href="<?= base_url('guru/pbl/tahap2/' . $quiz->class_id) ?>" class="btn btn-secondary">← Kembali</a>
  </div>

  <?php if ($this->session->flashdata('import_success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <strong>Berhasil!</strong> <?= $this->session->flashdata('import_success'); ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>

  <div class="card shadow-sm mb-3">
    <div class="d-flex flex-wrap gap-2 p-3">
      <button class="btn btn-primary" id="btnAddQuestion">
        <i class="bi bi-plus-lg"></i> Tambah Pertanyaan
      </button>
      <a href="<?= base_url('guru/pbl_kuis/export_quiz/' . $quiz->id) ?>" class="btn btn-success">
        <i class="bi bi-file-earmark-spreadsheet"></i> Export
      </a>
      <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#importModal">
        <i class="bi bi-upload"></i> Import
      </button>
    </div>
  </div>

  <div class="row">
    <!-- Tabel Pertanyaan -->
    <div class="card shadow-sm h-100">
      <div class="card-header bg-white">
        <h5 class="mb-0 text-primary">Daftar Pertanyaan</h5>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-hover" id="questionTable">
            <thead>
              <tr>
                <th style="width: 5%;">No</th>
                <th>Pertanyaan</th>
                <th style="width: 10%;">Jawaban</th>
                <th style="width: 15%;">Aksi</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Tabel Nilai Siswa -->
    <div class="card shadow-sm h-100">
      <div class="card-header bg-success text-white">
        <h5 class="mb-0"><i class="bi bi-trophy"></i> Daftar Nilai Siswa</h5>
      </div>
      <div class="card-body" id="submissionsTableContainer">
        <div class="table-responsive">
          <table class="table table-hover table-striped" id="submissionsTable">
            <thead class="table-light">
              <tr>
                <th style="width: 5%">No</th>
                <th>Siswa</th>
                <th>Nilai</th>
                <th>Waktu</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <!-- Diisi oleh JavaScript submissionHandler -->
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

</div>

<!-- Modal Pertanyaan & Import tetap sama -->
<div class="modal fade" id="questionModal" tabindex="-1" aria-labelledby="questionModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form id="questionForm">
        <div class="modal-header">
          <h5 class="modal-title" id="questionModalLabel">Tambah Pertanyaan</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id" id="questionId">
          <input type="hidden" name="quiz_id" value="<?= $quiz->id; ?>">
          <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" 
          value="<?= $this->security->get_csrf_hash(); ?>">

          <div class="mb-3">
            <label for="question_text" class="form-label">Teks Pertanyaan</label>
            <textarea class="form-control" id="question_text" name="question_text" rows="3" required></textarea>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="option_a" class="form-label">Opsi A</label>
              <input type="text" class="form-control" id="option_a" name="option_a" required>
            </div>
            <div class="col-md-6 mb-3">
              <label for="option_b" class="form-label">Opsi B</label>
              <input type="text" class="form-control" id="option_b" name="option_b" required>
            </div>
            <div class="col-md-6 mb-3">
              <label for="option_c" class="form-label">Opsi C</label>
              <input type="text" class="form-control" id="option_c" name="option_c" required>
            </div>
            <div class="col-md-6 mb-3">
              <label for="option_d" class="form-label">Opsi D</label>
              <input type="text" class="form-control" id="option_d" name="option_d" required>
            </div>
          </div>

          <div class="mb-3">
            <label for="correct_answer" class="form-label">Jawaban Benar</label>
            <select class="form-select" id="correct_answer" name="correct_answer" required>
              <option value="" disabled selected>-- Pilih Jawaban --</option>
              <option value="A">A</option>
              <option value="B">B</option>
              <option value="C">C</option>
              <option value="D">D</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="<?= base_url('guru/pbl_kuis/import_quiz'); ?>" method="post" enctype="multipart/form-data">
        <div class="modal-header">
          <h5 class="modal-title" id="importModalLabel">Import Pertanyaan</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="quiz_id_import" value="<?= $quiz->id; ?>">
          <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" 
          value="<?= $this->security->get_csrf_hash(); ?>">
          <div class="mb-3">
            <label for="import_file" class="form-label">Pilih file (Excel/CSV)</label>
            <input class="form-control" type="file" id="import_file" name="import_file"  required>
          </div>
          <p class="form-text">
            Pastikan file Anda memiliki kolom: `question_text`, `option_a`, `option_b`, `option_c`, `option_d`, `correct_answer`.
          </p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Upload</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  window.QUIZ_ID = "<?= $quiz->id; ?>";
  window.BASE_URL = "<?= base_url(); ?>";
  window.CSRF_TOKEN_NAME = "<?= $this->security->get_csrf_token_name(); ?>";
</script>
<script type="module" src="<?= base_url('assets/js/kuis_detail.js'); ?>"></script>