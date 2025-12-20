<style>
    /* Styling khusus untuk Review Mode */
    .review-correct { border: 2px solid #1cc88a; background-color: #f0fff4; }
    .review-wrong { border: 2px solid #e74a3b; background-color: #fff5f5; }
    .emote-badge { font-size: 2rem; position: absolute; top: 10px; right: 20px; }
    
    /* Styling standar */
    .question-card {
        border: 1px solid #e3e6f0; border-radius: 0.35rem;
        padding: 1.5rem; margin-bottom: 1rem; position: relative;
        background: #fff;
    }
    #questionsTable thead { display: none; }
    .table > :not(caption) > * > * { padding: 0; border: none; }
</style>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        
        <a href="<?= base_url('siswa/pbl/tahap2/' . $class_id) ?>" class="btn btn-secondary">Kembali</a>
    </div>

    <?php if ($result): ?>
        <div class="alert <?= ($result->score >= 70) ? 'alert-success' : 'alert-warning'; ?> text-center shadow-sm">
            <h4><i class="bi bi-journal-check"></i> Hasil Pengerjaan</h4>
            <h1 class="display-3 fw-bold"><?= $result->score; ?></h1>
            <p>Benar: <b><?= $result->total_correct; ?></b> / <?= $result->total_questions; ?> Soal</p>
        </div>
        <div class="alert alert-info"><i class="bi bi-info-circle"></i> Berikut adalah detail jawaban Anda.</div>
    <?php endif; ?>

    <form id="quizSubmissionForm">
        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
        
        <div id="questionsTableContainer">
            <table class="table table-borderless" id="questionsTable">
                <thead><tr><th>Data</th></tr></thead>
                <tbody></tbody>
            </table>
        </div>

        <?php if (!$result): ?>
            <div class="d-grid gap-2 mt-4 mb-5">
                <button type="submit" class="btn btn-primary btn-lg" id="btnSubmitQuiz">
                    <i class="bi bi-send"></i> Kirim Jawaban
                </button>
            </div>
        <?php endif; ?>
    </form>
</div>

<script>
    window.BASE_URL = "<?= base_url(); ?>";
    window.CSRF_TOKEN_NAME = "<?= $this->security->get_csrf_token_name(); ?>";
    window.QUIZ_ID = "<?= $quiz->id; ?>";
    // Flag Penting: Apakah siswa sudah mengerjakan?
    window.IS_DONE = <?= $is_done ? 'true' : 'false'; ?>; 
</script>

<script type="module" src="<?= base_url('assets/js/siswa/pbl_kuis_detail.js'); ?>"></script>