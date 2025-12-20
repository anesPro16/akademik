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

	<input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>"
	value="<?= $this->security->get_csrf_hash(); ?>">
	<input type="hidden" id="currentUserId" value="<?= $user['user_id']; ?>"> 
	<input type="hidden" id="classIdHidden" value="<?= $class_id; ?>">

	<div class="card shadow-sm border-0 mb-4">
		<div class="card-header bg-white py-3">
			<h5 class="mb-0 card-title text-primary"><i class="bi bi-trophy"></i> Rekapitulasi Nilai Kelas</h5>
		</div>
		<div class="card-body" id="rekapTableContainer">
			<div class="table-responsive">
				<table class="table table-hover align-middle" id="rekapTable">
					<thead class="table-light">
						<tr>
							<th width="5%">No</th>
							<th>Nama Siswa</th>
							<th class="text-cente">Quiz</th>
							<th class="text-cente">Observasi</th>
							<th class="text-cente">Esai</th>
							<th class="text-cente fw-bold text-primary">Total Skor</th>
							<th width="15%" class="text-cente">Aksi</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="refleksiModal" tabindex="-1" aria-labelledby="refleksiModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-lg">
		<div class="modal-content border-0 shadow-lg">
			<div class="modal-header bg-success text-white">
				<h5 class="modal-title" id="refleksiModalLabel">Refleksi & Feedback Guru</h5>
				<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body bg-light">
				<form id="refleksiForm">
					<div class="mb-4">
						<h6 class="fw-bold text-secondary text-uppercase small">Catatan Refleksi Guru</h6>
						<div class="p-3 bg-white rounded border" id="viewTeacherReflection" style="min-height: 80px; white-space: pre-wrap;">- Belum ada catatan -</div>
					</div>

					<div class="mb-3">
						<h6 class="fw-bold text-secondary text-uppercase small">Feedback Personal Untuk Anda</h6>
						<div class="p-3 bg-white rounded border border-success" id="viewStudentFeedback" style="min-height: 80px; white-space: pre-wrap;">- Belum ada feedback -</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
			</div>
		</div>
	</div>
</div>

<script>
	window.BASE_URL = "<?= base_url(); ?>";
	window.CSRF_TOKEN_NAME = "<?= $this->security->get_csrf_token_name(); ?>";
	window.CURRENT_CLASS_ID = '<?= $class_id; ?>';
	window.URL_NAME = '<?= $url_name; ?>';
</script>
<script type="module" src="<?= base_url('assets/js/siswa/pbl_tahap5.js'); ?>"></script>