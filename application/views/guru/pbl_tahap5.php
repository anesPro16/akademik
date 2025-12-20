<style>
/* --- Header --- */
.pbl-header {
  border-bottom: 1px solid #e5e7eb !important;
  padding-bottom: 1rem !important;
  margin-bottom: 1.5rem !important;
}

.pbl-badge {
  font-size: .75rem !important;
  letter-spacing: .5px !important;
}

/* --- Table --- */
.table-responsive {
  overflow-x: auto !important;
  -webkit-overflow-scrolling: touch !important;
}

#rekapTable {
  min-width: 720px !important; /* trigger horizontal scroll on mobile */
  border: 1px solid #dee2e6 !important;
}

#rekapTable thead th,
#rekapTable tbody td {
  border: 1px solid #dee2e6 !important;
  vertical-align: middle !important;
  transition: background-color .2s ease !important;
}

#rekapTable tbody tr {
  transition: background-color .2s ease, transform .15s ease !important;
}

#rekapTable tbody tr:hover {
  background-color: #f8f9fa !important;
  transform: scale(1.002) !important;
}

.aksi{
  width: 25%;
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

<div class="container-fluid py-3">

  <!-- ===== HEADER ===== -->
  <div class="pbl-header d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div class="d-flex gap-2">
      <a href="<?= base_url($url_name . '/pbl/tahap4/' . $class_id) ?>" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left"></i>Tahap 4
      </a>
      <a href="<?= base_url($url_name . '/pbl/index/' . $class_id); ?>" class="btn btn-primary btn-sm">
        <i class="bi bi-list-task"></i>Tahap 1
      </a>
    </div>
  </div>

  <!-- CSRF -->
  <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>"
         value="<?= $this->security->get_csrf_hash(); ?>">
  <input type="hidden" id="classIdHidden" value="<?= $class_id; ?>">

  <!-- ===== KONTEN UTAMA : KUIS ===== -->
  <div class="card shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
      <h5 class="m-0 font-weight-bold text-primary">
        <i class="bi bi-card-checklist me-2"></i>
        Rekapitulasi Nilai Siswa
      </h5>
    </div>

    <div class="card-body p-0 px-2">
      <div class="table-responsive">
        <!-- TABLE -->
        <table class="table mb-0" id="rekapTable">
          <thead class="table-light">
            <tr>
              <th width="5%">No</th>
              <th>Nama Siswa</th>
              <th class="text-cente">Kuiz</th>
              <th class="text-cente">Observasi</th>
              <th class="text-cente">Esai</th>
              <th class="text-cente fw-bold text-primary">Total Skor</th>
              <th width="15%" class="text-cente aksi">Aksi</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>

      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="refleksiModal" tabindex="-1" aria-labelledby="refleksiModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0 shadow-lg">
      <form id="refleksiForm" autocomplete="off">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="refleksiModalLabel">Input Refleksi & Feedback</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="user_id" id="modalUserId">
          <input type="hidden" name="class_id" value="<?= $class_id; ?>">
          
          <div class="mb-3">
            <label class="form-label fw-bold">Siswa:</label>
            <input type="text" class="form-control-plaintext" id="modalStudentName" readonly value="-">
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="teacherReflection" class="form-label">Catatan Refleksi Guru</label>
              <textarea name="teacher_reflection" id="teacherReflection" class="form-control" rows="6" 
                placeholder="Tuliskan catatan refleksi mengenai performa siswa..."></textarea>
            </div>
            <div class="col-md-6 mb-3">
              <label for="studentFeedback" class="form-label">Feedback untuk Siswa</label>
              <textarea name="student_feedback" id="studentFeedback" class="form-control" rows="6" 
                placeholder="Pesan yang akan dibaca oleh siswa..."></textarea>
            </div>
          </div>
        </div>
        <div class="modal-footer bg-light">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan Refleksi</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  window.BASE_URL = "<?= base_url(); ?>";
  window.CSRF_TOKEN_NAME = "<?= $this->security->get_csrf_token_name(); ?>";
  window.CURRENT_CLASS_ID = '<?= $class_id; ?>';
  window.URL_NAME = '<?= $url_name; ?>';
</script>
<script type="module" src="<?= base_url('assets/js/pbl_tahap5.js'); ?>"></script>