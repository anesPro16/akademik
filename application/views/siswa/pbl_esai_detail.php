<div class="container py-3">

	<!-- Header Halaman -->
	<div class="d-flex justify-content-between align-items-center mb-3">
		<a href="<?= base_url('siswa/pbl/tahap4/' . $class_id) ?>" class="btn btn-secondary">← Kembali ke Tahap 4</a>
		<h3 class="text-primary mb-0"><?= htmlspecialchars($essay->title, ENT_QUOTES, 'UTF-8'); ?></h3>
	</div>

	<!-- Status Penilaian -->
	<div class="alert alert-info d-flex align-items-center" role="alert" id="submissionStatus">
		<i class="bi bi-info-circle-fill me-2"></i>
		<div id="statusText">
			<?php if ($submission && $submission->grade !== null) : ?>
				<span class="text-success fw-bold">Sudah Dinilai!</span> | Nilai Anda: <span class="badge bg-success fs-6"><?= $submission->grade ?></span> | Terakhir Diperbarui: <?= date('d M Y H:i', strtotime($submission->updated_at)); ?>
				<?php elseif ($submission) : ?>
					<span class="text-warning fw-bold">Sudah Dikirim!</span> Menunggu penilaian guru. | Terakhir Dikirim: <?= date('d M Y H:i', strtotime($submission->updated_at)); ?>
					<?php else : ?>
						<span class="fw-bold">Anda belum mengirimkan jawaban.</span>
					<?php endif; ?>
				</div>
			</div>

			<!-- Instruksi Esai Utama -->
			<div class="card shadow-sm mb-4">
				<div class="card-header bg-info text-white">
					<h5 class="mb-0">Instruksi Esai Utama</h5>
				</div>
				<div class="card-body">
					<p class="fs-5"><?= nl2br(htmlspecialchars($essay->description, ENT_QUOTES, 'UTF-8')); ?></p>
				</div>
			</div>
			
			<!-- Daftar Pertanyaan Esai -->
			<?php if (!empty($questions)) : ?>
				<div class="card shadow-sm mb-4">
					<div class="card-header bg-success text-white">
						<h5 class="mb-0">Daftar Pertanyaan</h5>
					</div>
					<ul class="list-group list-group-flush">
						<?php foreach ($questions as $q) : ?>
							<li class="list-group-item">
								<p class="mb-1 fw-bold">P.<?= $q->question_number ?> (Bobot: <?= $q->weight ?>%)</p>
								<p class="mb-0"><?= nl2br(htmlspecialchars($q->question_text, ENT_QUOTES, 'UTF-8')); ?></p>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endif; ?>

			<!-- Feedback Guru -->
			<div class="card shadow-sm mb-4" id="feedbackCard" style="display: <?= ($submission && $submission->feedback) ? 'block' : 'none'; ?>;">
				<div class="card-header bg-warning text-dark">
					<h5 class="mb-0">Feedback Guru</h5>
				</div>
				<div class="card-body">
					<p id="feedbackContent" class="mb-0"><?= nl2br(htmlspecialchars($submission->feedback ?? '', ENT_QUOTES, 'UTF-8')); ?></p>
				</div>
			</div>

			<!-- Form Jawaban Siswa -->
			<div class="card shadow-sm mb-5">
				<div class="card-header bg-primary text-white">
					<h5 class="mb-0">Formulir Jawaban Anda</h5>
				</div>
				<form id="submissionForm" autocomplete="off">
					<div class="card-body">
						<input type="hidden" name="essay_id" value="<?= $essay->id; ?>">
						<input type="hidden" name="submission_id" id="submissionId" value="<?= $submission->id ?? ''; ?>">
						<input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" 
						value="<?= $this->security->get_csrf_hash(); ?>">
						
						<div class="mb-3">
							<label for="submission_content" class="form-label">Tuliskan Jawaban Esai Anda Di Sini:</label>
							<textarea name="submission_content" id="submission_content" class="form-control" rows="15" required><?= htmlspecialchars($submission->submission_content ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
						</div>

						<p class="text-muted small">Jawaban akan otomatis diperbarui atau dikirim saat Anda menekan tombol "Kirim Jawaban".</p>
					</div>
					<div class="card-footer bg-light text-end">
						<button type="submit" class="btn btn-primary" id="submitButton">
							<i class="bi bi-send-fill"></i> Kirim Jawaban
						</button>
					</div>
				</form>
			</div>

		</div>

		<!-- Script Loader -->
		<script>
			window.BASE_URL = "<?= base_url(); ?>";
			window.CSRF_TOKEN_NAME = "<?= $this->security->get_csrf_token_name(); ?>";
  // Tidak perlu CURRENT_ESSAY_ID di sini karena sudah ada di form
  // Namun, kita bisa tambahkan untuk kemudahan di JS
  window.CURRENT_ESSAY_ID = "<?= $essay->id; ?>";
</script>
<script type="module" src="<?= base_url('assets/js/pbl_esai_detail_siswa.js'); ?>"></script>