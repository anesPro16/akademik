import CrudHandler from './crud_handler.js';

document.addEventListener('DOMContentLoaded', () => {

	const csrfEl = document.querySelector('input[name="' + window.CSRF_TOKEN_NAME + '"]');
	// const IS_ADMIN_OR_GURU = window.IS_ADMIN_OR_GURU || false; // Tidak terlalu butuh di tahap 5 jika hanya guru
	const CURRENT_CLASS_ID = window.CURRENT_CLASS_ID || null;

	if (!CURRENT_CLASS_ID) {
		console.error('CLASS ID tidak ditemukan.');
		return;
	}

	const csrfConfig = {
		tokenName: window.CSRF_TOKEN_NAME,
		tokenHash: csrfEl ? csrfEl.value : ''
	};

	// --- Konfigurasi CRUD Refleksi ---
	const refleksiConfig = {
		baseUrl: window.BASE_URL,
		entityName: 'Refleksi Siswa',
		
			// ID Elemen DOM
			modalId: 'refleksiModal',
			formId: 'refleksiForm',
			modalLabelId: 'refleksiModalLabel',
			tableId: 'rekapTable',
			btnAddId: null, // Kita tidak pakai tombol tambah global, tapi per baris
			
			tableParentSelector: '.card-body', // Sesuaikan dengan parent tabel Anda

			csrf: csrfConfig,
			
			urls: {
				load: `guru/pbl/get_student_recap/${CURRENT_CLASS_ID}`,
				save: `guru/pbl/save_reflection`,
					delete: null // Tidak ada fitur delete refleksi di tabel ini
				},

				modalTitles: { 
					add: 'Input Refleksi', 
					edit: 'Input / Edit Refleksi' 
				},

			// --- MAPPING DATA JSON KE TABEL HTML ---
			dataMapper: (student, index) => {
					// 1. Hitung Total Skor (Pastikan tipe data angka)
					const scoreQuiz = (parseFloat(student.quiz_score) || 0) + (parseFloat(student.tts_score) || 0);
					const scoreObs  = parseFloat(student.obs_score) || 0;
					const scoreEssay = parseFloat(student.essay_score) || 0;
					const totalScore = scoreQuiz + scoreObs + scoreEssay;

					// 2. Cek apakah sudah ada refleksi (untuk warna tombol)
					// Perhatikan: properti JSON harus sama persis dengan alias di Model (teacher_reflection, student_feedback)
					const hasReflection = (student.teacher_reflection && student.teacher_reflection.trim() !== "") || 
					(student.student_feedback && student.student_feedback.trim() !== "");
					
					const btnClass = hasReflection ? 'btn-warning' : 'btn-primary';
					const btnIcon = hasReflection ? 'bi-pencil-square' : 'bi-plus-lg';
					const btnText = hasReflection ? 'Edit Refleksi' : 'Input Refleksi';

					// 3. Tombol Aksi
					// PENTING: Class 'btn-edit' digunakan CrudHandler untuk trigger modal open
					// Kita simpan data yang akan dimasukkan ke form di dalam attribute `data-*`
					const actionBtn = `
					<button type="button" class="btn btn-sm ${btnClass} btn-edit" 
					data-id="${student.user_id}" 
					data-name="${student.student_name}"
					data-reflection="${student.teacher_reflection || ''}"
					data-feedback="${student.student_feedback || ''}">
					<i class="bi ${btnIcon}"></i> ${btnText}
					</button>
					`;

					// Return Array kolom tabel
					return [
					index + 1,
					`<div class="fw-bold">${student.student_name}</div>`,
					`<span class="badge bg-secondary">${scoreQuiz}</span>`,
					`<span class="badge bg-info text-dark">${scoreObs}</span>`,
					`<span class="badge bg-success">${scoreEssay}</span>`,
					`<span class="fw-bold text-primary fs-6">${totalScore}</span>`,
					actionBtn
					];
				},

			// --- MENGISI FORM MODAL SAAT TOMBOL DIKLIK ---
			formPopulator: (form, data) => {
					// `data` disini berisi dataset dari tombol (.btn-edit)
					
					// Isi Hidden Input
					const userIdInput = form.querySelector('#modalUserId');
					if(userIdInput) userIdInput.value = data.id; // data-id -> user_id

					// Isi Nama Siswa (Readonly)
					const nameInput = form.querySelector('#modalStudentName');
					if(nameInput) nameInput.value = data.name;

					// Isi Textarea Refleksi
					const reflectionInput = form.querySelector('[name="teacher_reflection"]');
					if(reflectionInput) reflectionInput.value = data.reflection || '';

					// Isi Textarea Feedback
					const feedbackInput = form.querySelector('[name="student_feedback"]');
					if(feedbackInput) feedbackInput.value = data.feedback || '';
				}
			};

	// Inisialisasi
	new CrudHandler(refleksiConfig).init();
});