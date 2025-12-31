import CrudHandler from './crud_handler.js';

document.addEventListener('DOMContentLoaded', () => {
  
  const ESSAY_ID = document.getElementById('currentEssayId').value;
  const csrfEl = document.querySelector('input[name="' + window.CSRF_TOKEN_NAME + '"]');
  
  const csrfConfig = {
    tokenName: window.CSRF_TOKEN_NAME,
    tokenHash: csrfEl ? csrfEl.value : ''
  };

  // ==========================================
  // FUNGSI BANTUAN DOM (DYNAMIC INPUT)
  // ==========================================
  const container = document.getElementById('dynamicQuestionContainer');
  const btnAddRowWrapper = document.getElementById('btnAddRowWrapper');
  const btnAddRow = document.getElementById('btnAddRow');

  // Fungsi membuat HTML input soal
  const createInputRow = (value = '', isRemovable = true) => {
    const div = document.createElement('div');
    div.className = 'input-group mb-2 question-row';
    
    const label = document.createElement('span');
    label.className = 'input-group-text';
    label.innerText = 'Soal';

    const textarea = document.createElement('textarea');
      textarea.name = 'question_text[]'; // Array name untuk CodeIgniter
      textarea.className = 'form-control';
      textarea.rows = 2;
      textarea.placeholder = 'Tulis pertanyaan disini...';
      textarea.value = value;
      textarea.required = true;

      div.appendChild(label);
      div.appendChild(textarea);

      if (isRemovable) {
        const btnDel = document.createElement('button');
        btnDel.type = 'button';
        btnDel.className = 'btn btn-outline-danger';
        btnDel.innerHTML = '<i class="bi bi-trash"></i>';
        btnDel.onclick = () => div.remove();
        div.appendChild(btnDel);
      }

      return div;
    };

  // Event Listener Tombol Tambah Baris
  if(btnAddRow) {
    btnAddRow.addEventListener('click', () => {
      container.appendChild(createInputRow('', true));
    });
  }

  // ==========================================
  // 1. INSTANCE CRUD: DAFTAR PERTANYAAN
  // ==========================================
  const questionConfig = {
    baseUrl: window.BASE_URL,
    entityName: 'Soal',
    modalId: 'questionModal',
    formId: 'questionForm',
    modalLabelId: 'questionModalLabel',
    hiddenIdField: 'questionId',
    tableId: 'questionTable',
    btnAddId: 'btnAddQuestion',
    tableParentSelector: '#questionTableContainer', 
    csrf: csrfConfig,
    urls: {
      load: `guru/pbl_esai/get_questions_json/${ESSAY_ID}`,
      save: `guru/pbl_esai/save_question`,
      delete: (id) => `guru/pbl_esai/delete_question/${id}`
    },
    deleteMethod: 'POST',
    modalTitles: { add: 'Tambah Soal (Bisa Banyak)', edit: 'Edit Soal' },
      deleteNameField: 'text', // Mengambil dari data-text di tombol

      dataMapper: (q, i) => {
        const shortText = q.question_text.length > 70 ? q.question_text.substring(0, 70) + '...' : q.question_text;
        
        const btns = `
        <button class="btn btn-sm btn-warning btn-edit" 
        data-id="${q.id}" 
        data-question_text="${q.question_text}">
        Ubah
        </button>
        <button class="btn btn-sm btn-danger btn-delete" 
        data-id="${q.id}" 
        data-text="No. ${q.question_number}">
        Hapus
        </button>
        `;
          // Bobot dihapus dari tampilan
          return [q.question_number, shortText, btns];
        },

      // Dijalankan saat tombol "Tambah Soal" diklik
      onAdd: (form) => {
          container.innerHTML = ''; // Reset container
          // Tambah 1 baris default (tidak bisa dihapus agar minimal ada 1)
          container.appendChild(createInputRow('', false));
          // Tampilkan tombol "Tambah Baris"
          btnAddRowWrapper.style.display = 'block';
        },

      // Dijalankan saat tombol "Edit" diklik
      formPopulator: (form, data) => {
          container.innerHTML = ''; // Reset container
          
          // Masukkan data yang ada ke input
          // Baris edit tidak bisa ditambah/dikurangi (Single Mode)
          container.appendChild(createInputRow(data.question_text, false));

          form.querySelector('#questionId').value = data.id;
          
          // Sembunyikan tombol "Tambah Baris" saat mode edit
          btnAddRowWrapper.style.display = 'none';
        }
      };

  // ==========================================
  // 2. INSTANCE CRUD: PENILAIAN (GRADING)
  // ==========================================
  const gradingConfig = {
    baseUrl: window.BASE_URL,
    entityName: 'Nilai',
    modalId: 'gradeModal',
    formId: 'gradeForm',
    modalLabelId: 'gradeModalLabel', 
    tableId: 'gradingTable',
    tableParentSelector: '#gradingTableContainer',
    
    csrf: csrfConfig,
    urls: {
      load: `guru/pbl_esai/get_grading_json/${ESSAY_ID}`,
      save: `guru/pbl_esai/save_grade`,
      delete: null 
    },
    modalTitles: { edit: 'Berikan Penilaian' },

    dataMapper: (s, i) => {
      let statusBadge = '<span class="badge bg-secondary">Belum Mengumpulkan</span>';
      let dateText = '-';
      let gradeText = '-';
      let btnClass = 'btn-secondary disabled';
      let btnIcon = '';
      let btnText = 'Belum Ada';
      let isDisabled = 'disabled';

      if (s.submission_id) {
        statusBadge = '<span class="badge bg-success">Sudah Mengumpulkan</span>';
        dateText = new Date(s.submitted_at).toLocaleString('id-ID');
        gradeText = s.grade !== null ? `<span class="fw-bold text-primary">${s.grade}</span>` : '<span class="text-danger">Belum Dinilai</span>';
        btnClass = 'btn-success btn-edit'; 
        btnIcon = 'bi-pencil-square';
        btnText = 'Nilai';
        isDisabled = '';
      }

      const safeContent = s.submission_content ? encodeURIComponent(s.submission_content) : '';

      const actionBtn = `
      <button class="btn btn-sm ${btnClass}" ${isDisabled}
      data-id="${s.submission_id}" 
      data-student_name="${s.student_name}"
      data-content="${safeContent}"
      data-grade="${s.grade || ''}"
      data-feedback="${s.feedback || ''}">
      <i class="bi ${btnIcon}"></i> ${btnText}
      </button>
      `;

      return [i + 1, s.student_name, statusBadge, dateText, gradeText, actionBtn];
    },

    formPopulator: (form, data) => {
      form.querySelector('#submissionId').value = data.id; 
      
      const labelEl = document.getElementById('gradeModalLabel');
      if(labelEl) labelEl.textContent = `Penilaian: ${data.student_name}`;

      const content = data.content ? decodeURIComponent(data.content) : '-';
      document.getElementById('studentAnswerContent').innerHTML = content.replace(/\n/g, '<br>');

      form.querySelector('#gradeInput').value = data.grade;
      form.querySelector('#feedbackInput').value = data.feedback;
    }
  };

  new CrudHandler(questionConfig).init();
  new CrudHandler(gradingConfig).init();
});