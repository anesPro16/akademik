import CrudHandler from '../crud_handler.js';

document.addEventListener('DOMContentLoaded', () => {
	const QUIZ_ID = window.QUIZ_ID;
	const IS_DONE = window.IS_DONE;

	if (!QUIZ_ID) return;

  // Tentukan URL dan Mapper berdasarkan status pengerjaan
  let loadUrl, dataMapperFn;

  if (IS_DONE) {
    // --- MODE REVIEW (Sudah Mengerjakan) ---
    loadUrl = `siswa/pbl_kuis/get_review/${QUIZ_ID}`;
    
    dataMapperFn = (q, i) => {
    	const num = i + 1;
    	const isCorrect = q.is_correct == 1;

      // Logika Emote & Style
      let cardClass = isCorrect ? 'review-correct' : 'review-wrong';
      /*let emote = isCorrect 
      ? '<i class="bi bi-emoji-smile-fill text-success emote-badge"></i>' 
      : '<i class="bi bi-emoji-frown-fill text-danger emote-badge"></i>';*/
      let emote = ''
      
      // Helper untuk menandai opsi
      const getBadge = (optKey) => {
      	if (q.correct_answer === optKey) return '<span class="badge bg-success ms-2"><i class="bi bi-check"></i> Benar</span>';
      	if (q.selected_option === optKey && !isCorrect) return '<span class="badge bg-danger ms-2"><i class="bi bi-x"></i> Jawabanmu</span>';
      	return '';
      };

      const html = `
      <div class="question-card ${cardClass}">
      ${emote}
      <h5 class="mb-3">Soal ${num}</h5>
      <p class="lead mb-4">${q.question_text}</p>

      <ul class="list-group">
      <li class="list-group-item ${q.correct_answer === 'A' ? 'list-group-item-success' : ''}">
      <strong>A.</strong> ${q.option_a} ${getBadge('A')}
      </li>
      <li class="list-group-item ${q.correct_answer === 'B' ? 'list-group-item-success' : ''}">
      <strong>B.</strong> ${q.option_b} ${getBadge('B')}
      </li>
      <li class="list-group-item ${q.correct_answer === 'C' ? 'list-group-item-success' : ''}">
      <strong>C.</strong> ${q.option_c} ${getBadge('C')}
      </li>
      <li class="list-group-item ${q.correct_answer === 'D' ? 'list-group-item-success' : ''}">
      <strong>D.</strong> ${q.option_d} ${getBadge('D')}
      </li>
      </ul>

      ${!isCorrect ? `<div class="mt-3 text-danger"><small>Jawaban Anda: <strong>${q.selected_option || 'Tidak dijawab'}</strong></small></div>` : ''}
      </div>
      `;
      return [html];
    };

  } else {
    // --- MODE MENGERJAKAN (Belum Mengerjakan) ---
    loadUrl = `siswa/pbl_kuis/get_questions/${QUIZ_ID}`;
    
    dataMapperFn = (q, i) => {
    	const num = i + 1;
    	const html = `
    	<div class="question-card shadow-sm">
    	<h5 class="mb-3">${num}. ${q.question_text}</h5>
    	<div class="options-group">
    	${['A', 'B', 'C', 'D'].map(opt => `
    		<div class="form-check mb-2">
    		<input class="form-check-input" type="radio" name="answers[${q.id}]" id="q${q.id}_${opt}" value="${opt}">
    		<label class="form-check-label w-100 p-2 border rounded option-hover" for="q${q.id}_${opt}" style="cursor:pointer">
    		<strong>${opt}.</strong> ${q['option_'+opt.toLowerCase()]}
    		</label>
    		</div>
    		`).join('')}
    	</div>
    	</div>
    	`;
    	return [html];
    };
  }

// --- Inisialisasi CrudHandler ---
const csrfEl = document.querySelector(`input[name="${window.CSRF_TOKEN_NAME}"]`);
const config = {
	baseUrl: window.BASE_URL,
	entityName: 'Soal',
    readOnly: true, // Kita handle submit manual, jadi set true agar CrudHandler hanya load data
    tableId: 'questionsTable',
    tableParentSelector: '#questionsTableContainer',
    csrf: {
    	tokenName: window.CSRF_TOKEN_NAME,
    	tokenHash: csrfEl ? csrfEl.value : ''
    },
    urls: {
    	load: loadUrl,
        save: '', delete: '' // Tidak dipakai di readOnly
      },
      dataMapper: dataMapperFn
    };

    const handler = new CrudHandler(config);
    handler.init();

// --- Logic Submit (Hanya jika belum done) ---
const form = document.getElementById('quizSubmissionForm');
if (form && !IS_DONE) {
	form.addEventListener('submit', function(e) {
		e.preventDefault();
		Swal.fire({
			title: 'Kirim Jawaban?',
			text: "Pastikan semua soal terjawab. Aksi ini tidak bisa dibatalkan.",
			icon: 'question',
			showCancelButton: true,
			confirmButtonText: 'Ya, Kirim!',
			cancelButtonText: 'Batal'
		}).then((result) => {
			if (result.isConfirmed) submitQuizData();
		});
	});
}

function submitQuizData() {
	const formData = new FormData(form);
	formData.append('quiz_id', QUIZ_ID);

	fetch(`${window.BASE_URL}siswa/pbl_kuis/submit_quiz`, {
		method: 'POST',
		body: formData
	})
	.then(res => res.json())
	.then(data => {
		if (data.csrf_hash) {
			document.querySelectorAll(`input[name="${window.CSRF_TOKEN_NAME}"]`).forEach(el => el.value = data.csrf_hash);
		}
		if (data.status === 'success') {
			Swal.fire({
				icon: 'success',
				title: 'Selesai!',
				text: `Nilai Anda: ${data.score || 0}`,
				allowOutsideClick: false
			}).then(() => {
                window.location.reload(); // Reload untuk masuk ke Mode Review
              });
		} else {
			Swal.fire('Gagal', data.message, 'error');
		}
	})
	.catch(err => Swal.fire('Error', 'Terjadi kesalahan jaringan.', 'error'));
}
});