import CrudHandler from './crud_handler.js'; // (Pastikan path ini benar)

document.addEventListener('DOMContentLoaded', () => {

  const csrfEl = document.querySelector('input[name="' + window.CSRF_TOKEN_NAME + '"]');
  const CURRENT_ESSAY_ID = window.CURRENT_ESSAY_ID;

  if (!CURRENT_ESSAY_ID) {
    console.error('ESSAY ID tidak ditemukan.');
    return;
  }

  const csrfConfig = {
    tokenName: window.CSRF_TOKEN_NAME,
    tokenHash: csrfEl ? csrfEl.value : ''
  };
  
  // --- A. Konfigurasi CRUD untuk Pertanyaan Esai ---
  const questionConfig = {
    baseUrl: window.BASE_URL,
    entityName: 'Pertanyaan Esai',
    modalId: 'questionModal',
    formId: 'questionForm',
    modalLabelId: 'questionModalLabel',
    hiddenIdField: 'questionId', 
    tableId: 'questionsTable',
    btnAddId: 'btnAddQuestion', // ID tombol tambah baru
    
    tableParentSelector: '#questionsTableContainer', 
    csrf: csrfConfig,
    urls: {
      load: `guru/pbl_esai/get_questions/${CURRENT_ESSAY_ID}`,
      save: `guru/pbl_esai/save_question`,
      delete: `guru/pbl_esai/delete_question`,
    },
    deleteMethod: 'POST', 
    modalTitles: { add: 'Tambah Pertanyaan Esai', edit: 'Edit Pertanyaan Esai' },
    deleteNameField: 'question_number', // Field untuk konfirmasi hapus

    dataMapper: (item, i) => {
      // Potong teks pertanyaan agar tidak terlalu panjang di tabel
      const questionText = item.question_text ? item.question_text.substring(0, 150) + (item.question_text.length > 150 ? '...' : '') : '-';
      
      // Tombol Aksi
      const actionButtons = `
        <button class="btn btn-sm btn-info btn-edit me-2"
          data-id="${item.id}"
          data-question_number="${item.question_number}"
          data-question_text="${item.question_text}"
          data-weight="${item.weight}">
          <i class="bi bi-pencil"></i> Edit
        </button>
        <button class="btn btn-sm btn-danger btn-delete"
          data-id="${item.id}"
          data-question_number="${item.question_number}">
          <i class="bi bi-trash"></i> Hapus
        </button>
      `;

      return [
        item.question_number,
        questionText,
        `${item.weight}%`,
        actionButtons
      ];
    },

    /**
     * Mengisi form modal saat tombol 'Edit' diklik
     */
    formPopulator: (form, data) => {
      form.querySelector('#questionId').value = data.id;
      form.querySelector('#question_number').value = data.question_number;
      form.querySelector('#question_text').value = data.question_text;
      form.querySelector('#weight').value = data.weight;
      // Essay ID sudah terisi dari PHP
    }
  };

  // Inisialisasi handler pertanyaan
  const questionHandler = new CrudHandler(questionConfig);
  questionHandler.init();


  // --- B. Konfigurasi CRUD untuk Memberi Feedback (Kode lama, tetap dipertahankan) ---
  const feedbackConfig = {
    baseUrl: window.BASE_URL,
    entityName: 'Feedback',
    modalId: 'feedbackModal',
    formId: 'feedbackForm',
    modalLabelId: 'feedbackModalLabel',
    hiddenIdField: 'submissionId', // ID field di form modal
    tableId: 'submissionsTable',
    // 'btnAddId' sengaja dikosongkan karena kita tidak menambah jawaban, hanya me-review
    
    tableParentSelector: '#submissionsTableContainer', 
    csrf: csrfConfig,
    urls: {
      load: `guru/pbl_esai/get_submissions/${CURRENT_ESSAY_ID}`,
      save: `guru/pbl_esai/save_feedback`,
      // 'delete' tidak kita gunakan di sini
    },
    deleteMethod: 'POST', 
    modalTitles: { add: '', edit: 'Beri Nilai & Feedback' }, // 'add' kosong
    deleteNameField: 'student_name', 

    dataMapper: (item, i) => {
      const submissionText = item.submission_content ? item.submission_content.substring(0, 100) + '...' : '(Belum submit)';
      const grade = item.grade || '-';
      
      // Tombol Aksi (ini adalah 'btn-edit' untuk CrudHandler)
      const actionButton = `
        <button class="btn btn-sm btn-primary btn-edit"
          data-id="${item.id}"
          data-student_name="${item.student_name}"
          data-submission_content="${item.submission_content}"
          data-grade="${item.grade || ''}"
          data-feedback="${item.feedback || ''}">
          <i class="bi bi-pencil-square"></i> Beri Nilai
        </button>
      `;

      return [
        item.student_name || '(Siswa tidak ditemukan)',
        submissionText,
        grade,
        actionButton
      ];
    },

    /**
     * Mengisi form modal saat tombol 'Beri Nilai' diklik
     */
    formPopulator: (form, data) => {
      form.querySelector('#submissionId').value = data.id;
      form.querySelector('#grade').value = data.grade;
      form.querySelector('#feedback').value = data.feedback;
      
      // Tampilkan preview jawaban siswa di dalam modal
      const previewEl = form.querySelector('#submissionContentPreview');
      previewEl.innerText = data.submission_content;
    }
  };

  // Inisialisasi handler feedback
  const feedbackHandler = new CrudHandler(feedbackConfig);
  feedbackHandler.init();
  
});