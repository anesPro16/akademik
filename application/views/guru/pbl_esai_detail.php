<div class="container py-3">

  <!-- Header Halaman -->
  <div class="d-flex justify-content-between align-items-center mb-3">
    <a href="<?= base_url('guru/pbl/tahap4/' . $class_id) ?>" class="btn btn-secondary">← Kembali ke Tahap 4</a>
  </div>

  <!-- Instruksi Esai -->
  <div class="card shadow-sm mb-3">
    <div class="card-header bg-info text-dark">
      <h5 class="mb-0">Instruksi Esai Utama</h5>
    </div>
    <div class="card-body">
      <p class="fs-5"><?= nl2br(htmlspecialchars($essay->description, ENT_QUOTES, 'UTF-8')); ?></p>
    </div>
  </div>
  
  <!-- Bagian Baru: Manajemen Pertanyaan Esai -->
  <div class="card shadow-sm mb-5">
    <div class="card-header d-flex justify-content-between align-items-center bg-success text-white">
      <h5 class="mb-0">Daftar Pertanyaan Esai</h5>
      <button class="btn btn-light btn-sm" id="btnAddQuestion"><i class="bi bi-plus-circle"></i> Tambah Pertanyaan</button>
    </div>
    <div class="card-body" id="questionsTableContainer">
      <table class="table table-hover" id="questionsTable">
        <thead class="table-light">
          <tr>
            <th style="width: 10%;">No.</th>
            <th>Teks Pertanyaan</th>
            <th style="width: 15%;">Bobot (%)</th>
            <th style="width: 15%;">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <!-- Diisi oleh JavaScript -->
        </tbody>
      </table>
    </div>
  </div>

  <!-- Tabel Jawaban Siswa (Bagian Lama) -->
  <div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
      <h5 class="mb-0">Review Jawaban Siswa</h5>
    </div>
    <div class="card-body" id="submissionsTableContainer">
      <table class="table table-hover" id="submissionsTable">
        <thead class="table-light">
          <tr>
            <th>Siswa</th>
            <th>Jawaban</th>
            <th>Nilai</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <!-- Diisi oleh JavaScript -->
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Modal Form Nilai/Feedback (Modal Edit Jawaban Siswa) (TETAP SAMA) -->
<div class="modal fade" id="feedbackModal" tabindex="-1" aria-labelledby="feedbackModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content shadow-lg border-0">
      <form id="feedbackForm" autocomplete="off">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title mb-0" id="feedbackModalLabel">Beri Nilai & Feedback</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id" id="submissionId">
          <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" 
                 value="<?= $this->security->get_csrf_hash(); ?>">
          
          <div class="mb-3">
            <label class="form-label">Jawaban Siswa:</label>
            <div class="card bg-light p-3" id="submissionContentPreview" style="max-height: 200px; overflow-y: auto;">
              <!-- Diisi oleh JS -->
            </div>
          </div>

          <div class="row">
            <div class="col-md-4">
              <label for="grade" class="form-label">Nilai (Angka)</label>
              <input type="number" name="grade" id="grade" class="form-control" min="0" max="100">
            </div>
            <div class="col-md-8">
              <label for="feedback" class="form-label">Feedback</label>
              <textarea name="feedback" id="feedback" class="form-control" rows="3"></textarea>
            </div>
          </div>
        </div>
        <div class="modal-footer bg-light">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan Nilai</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Form Pertanyaan Esai (Modal CRUD Pertanyaan) (BAGIAN BARU) -->
<div class="modal fade" id="questionModal" tabindex="-1" aria-labelledby="questionModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content shadow-lg border-0">
      <form id="questionForm" autocomplete="off">
        <div class="modal-header bg-success text-white">
          <h5 class="modal-title mb-0" id="questionModalLabel">Tambah Pertanyaan Esai</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id" id="questionId">
          <input type="hidden" name="essay_id" id="questionEssayId" value="<?= $essay->id; ?>">
          <!-- <input type="hidden" name="</?= $this->security->get_csrf_token_name(); ?>" 
                 value="</?= $this->security->get_csrf_hash(); ?>"> -->
          
          <div class="row">
            <div class="col-md-4 mb-3">
              <label for="question_number" class="form-label">Nomor Pertanyaan</label>
              <input type="number" name="question_number" id="question_number" class="form-control" required min="1">
            </div>
            <div class="col-md-8 mb-3">
              <label for="weight" class="form-label">Bobot Nilai (%)</label>
              <input type="number" name="weight" id="weight" class="form-control" required min="1" max="100" value="100">
            </div>
          </div>
          
          <div class="mb-3">
            <label for="question_text" class="form-label">Teks Pertanyaan</label>
            <textarea name="question_text" id="question_text" class="form-control" rows="5" required></textarea>
          </div>
          
        </div>
        <div class="modal-footer bg-light">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-success">Simpan Pertanyaan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Script Loader -->
<script>
  window.BASE_URL = "<?= base_url(); ?>";
  window.CSRF_TOKEN_NAME = "<?= $this->security->get_csrf_token_name(); ?>";
  window.CURRENT_ESSAY_ID = "<?= $essay->id; ?>";
</script>
<script type="module" src="<?= base_url('assets/js/pbl_esai_detail.js'); ?>"></script>