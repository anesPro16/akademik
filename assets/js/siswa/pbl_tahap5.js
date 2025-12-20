import CrudHandler from '../crud_handler.js';

document.addEventListener('DOMContentLoaded', () => {

    const csrfEl = document.querySelector('input[name="' + window.CSRF_TOKEN_NAME + '"]');
    const CURRENT_CLASS_ID = window.CURRENT_CLASS_ID || null;
    const CURRENT_USER_ID = document.getElementById('currentUserId').value; 

    if (!CURRENT_CLASS_ID) {
        console.error('CLASS ID tidak ditemukan.');
        return;
    }

    const csrfConfig = {
        tokenName: window.CSRF_TOKEN_NAME,
        tokenHash: csrfEl ? csrfEl.value : ''
    };

    const refleksiConfig = {
        baseUrl: window.BASE_URL,
        entityName: 'Refleksi',
        
        // PENTING: readOnly false agar fitur modal handler aktif
        readOnly: false, 

        // ID Elemen DOM
        modalId: 'refleksiModal',
        formId: 'refleksiForm', 
        
        // --- PERBAIKAN DISINI ---
        // Tambahkan modalLabelId agar CrudHandler bisa menemukan elemen judul modal
        modalLabelId: 'refleksiModalLabel', 
        // ------------------------

        tableId: 'rekapTable',
        btnAddId: null, 
        
        tableParentSelector: '.card-body', 

        csrf: csrfConfig,
        
        urls: {
            load: `siswa/pbl/get_my_recap/${CURRENT_CLASS_ID}`,
            save: 'siswa/pbl/dummy_save', // Dummy URL agar tidak error
            delete: null 
        },

        modalTitles: { 
            add: 'Detail Refleksi', 
            edit: 'Detail Refleksi' 
        },

        // --- MAPPING DATA JSON KE TABEL HTML ---
        dataMapper: (student, index) => {
            const scoreQuiz = (parseFloat(student.quiz_score) || 0) + (parseFloat(student.tts_score) || 0);
            const scoreObs  = parseFloat(student.obs_score) || 0;
            const scoreEssay = parseFloat(student.essay_score) || 0;
            const totalScore = scoreQuiz + scoreObs + scoreEssay;

            let actionBtn = '';
            
            // Cek apakah baris ini milik user yang sedang login?
            if (student.user_id === CURRENT_USER_ID) {
                const hasReflection = (student.teacher_reflection && student.teacher_reflection.trim() !== "") || 
                                      (student.student_feedback && student.student_feedback.trim() !== "");
                
                if (hasReflection) {
                    actionBtn = `
                        <button type="button" class="btn btn-sm btn-success btn-edit" 
                            data-reflection="${student.teacher_reflection || '-'}"
                            data-feedback="${student.student_feedback || '-'}">
                            <i class="bi bi-envelope-paper"></i> Lihat Refleksi
                        </button>
                    `;
                } else {
                    actionBtn = `<span class="badge bg-secondary text-white fw-normal">Belum ada feedback</span>`;
                }
            } else {
                actionBtn = `<span class="text-muted small">-</span>`;
            }

            const nameDisplay = (student.user_id === CURRENT_USER_ID) 
                ? `<span class="fw-bold text-success">${student.student_name} (Anda)</span>` 
                : student.student_name;

            return [
                index + 1,
                nameDisplay,
                `<span class="badge bg-secondary">${scoreQuiz}</span>`,
                `<span class="badge bg-info text-dark">${scoreObs}</span>`,
                `<span class="badge bg-success">${scoreEssay}</span>`,
                `<span class="fw-bold text-primary fs-6">${totalScore}</span>`,
                actionBtn
            ];
        },

        // --- POPULATE MODAL VIEW ---
        formPopulator: (form, data) => {
            const viewReflection = document.getElementById('viewTeacherReflection');
            const viewFeedback = document.getElementById('viewStudentFeedback');

            if (viewReflection) viewReflection.textContent = data.reflection;
            if (viewFeedback) viewFeedback.textContent = data.feedback;
        }
    };

    new CrudHandler(refleksiConfig).init();
});