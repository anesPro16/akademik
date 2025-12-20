import CrudHandler from './crud_handler.js';

document.addEventListener('DOMContentLoaded', () => {

  const csrfEl = document.querySelector('input[name="' + window.CSRF_TOKEN_NAME + '"]');
  const IS_ADMIN_OR_GURU = window.IS_ADMIN_OR_GURU || false;
  const CURRENT_CLASS_ID = window.CURRENT_CLASS_ID || null;

  // Hapus tombol "Tambah" jika Murid
  if (!IS_ADMIN_OR_GURU) {
    ['btnAddEsai', 'btnAddKuisEvaluasi'].forEach(id => {
      const btn = document.getElementById(id);
      if (btn) btn.remove(); // Menghapus elemen dari DOM
    });
  }

  if (!CURRENT_CLASS_ID) {
    console.error('CLASS ID tidak ditemukan.');
    return;
  }

  const csrfConfig = {
    tokenName: window.CSRF_TOKEN_NAME,
    tokenHash: csrfEl ? csrfEl.value : ''
  };

  // --- Inisialisasi CRUD 1: Aktivitas Esai Solusi ---
  const esaiConfig = {
    baseUrl: window.BASE_URL,
    entityName: 'Aktivitas Esai',
    modalId: 'esaiModal',
    formId: 'esaiForm',
    modalLabelId: 'esaiModalLabel',
    hiddenIdField: 'esaiId',
    tableId: 'esaiTable',
    btnAddId: 'btnAddEsai',
    tableParentSelector: '#solusi', // Parent tab
    csrf: csrfConfig,
    urls: {
      load: IS_ADMIN_OR_GURU ? `guru/pbl/get_solution_essays/${CURRENT_CLASS_ID}` : `siswa/pbl/get_solution_essays/${CURRENT_CLASS_ID}`,
      save: `guru/pbl/save_solution_essay`,
      delete: (id) => `guru/pbl/delete_solution_essay/${id}`
    },
    deleteMethod: 'POST',
    modalTitles: { add: 'Tambah Aktivitas Esai', edit: 'Edit Aktivitas Esai' },
    deleteNameField: 'title', // (data-title dari tombol delete)

    dataMapper: (q, i) => {
      const detailBtn = `<a href="${window.BASE_URL}${window.URL_NAME}/pbl_esai/detail/${q.id}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i> Detail</a>`;
      
      const actionBtns = IS_ADMIN_OR_GURU ? `
        <button class="btn btn-sm btn-warning btn-edit" data-id="${q.id}" data-title="${q.title}" data-description="${q.description || ''}"><i class="bi bi-pencil"></i></button>
        <button class="btn btn-sm btn-danger btn-delete" data-id="${q.id}" data-title="${q.title}"><i class="bi bi-trash"></i></button>
      ` : '';

      return [i + 1, q.title, q.description || '-', detailBtn + actionBtns];
    },

    formPopulator: (form, data) => {
      form.querySelector('#esaiId').value = data.id;
      form.querySelector('[name="title"]').value = data.title;
      form.querySelector('[name="description"]').value = data.description || '';
    }
  };

  // --- Inisialisasi CRUD 2: Kuis Evaluasi ---
  /*const evaluasiConfig = {
    baseUrl: window.BASE_URL,
    entityName: 'Kuis Evaluasi',
    modalId: 'evaluasiModal',
    formId: 'evaluasiForm',
    modalLabelId: 'evaluasiModalLabel',
    hiddenIdField: 'evaluasiId',
    tableId: 'kuisEvaluasiTable',
    btnAddId: 'btnAddKuisEvaluasi',
    tableParentSelector: '#evaluasi', // Parent tab
    csrf: csrfConfig,
    urls: {
      load: IS_ADMIN_OR_GURU ? `guru/pbl/get_evaluation_quizzes/${CURRENT_CLASS_ID}` : `siswa/pbl/get_evaluation_quizzes/${CURRENT_CLASS_ID}`,
      save: `guru/pbl/save_evaluation_quiz`,
      delete: (id) => `guru/pbl/delete_evaluation_quiz/${id}`
    },
    deleteMethod: 'POST',
    modalTitles: { add: 'Tambah Kuis Evaluasi', edit: 'Edit Kuis Evaluasi' },
    deleteNameField: 'title',

    dataMapper: (q, i) => {
      const detailBtn = `<a href="${window.BASE_URL}${window.URL_NAME}/pbl_kuis_evaluasi/detail/${q.id}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i> Detail</a>`;
      
      const actionBtns = IS_ADMIN_OR_GURU ? `
        <button class="btn btn-sm btn-warning btn-edit" data-id="${q.id}" data-title="${q.title}" data-description="${q.description || ''}"><i class="bi bi-pencil"></i></button>
        <button class="btn btn-sm btn-danger btn-delete" data-id="${q.id}" data-title="${q.title}"><i class="bi bi-trash"></i></button>
      ` : '';

      return [i + 1, q.title, q.description || '-', detailBtn + actionBtns];
    },
    
    formPopulator: (form, data) => {
      form.querySelector('#evaluasiId').value = data.id;
      form.querySelector('[name="title"]').value = data.title;
      form.querySelector('[name="description"]').value = data.description || '';
    }
  };*/

  // Inisialisasi kedua handler
  new CrudHandler(esaiConfig).init();
  // new CrudHandler(evaluasiConfig).init();
  
});