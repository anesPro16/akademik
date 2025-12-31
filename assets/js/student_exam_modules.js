import CrudHandler from './crud_handler.js';

document.addEventListener('DOMContentLoaded', () => {
    const CURRENT_CLASS_ID = window.CURRENT_CLASS_ID;

    // Helper Format Tanggal
    const formatTime = (dateString) => {
        const options = { day: 'numeric', month: 'short', hour: '2-digit', minute: '2-digit' };
        return new Date(dateString).toLocaleDateString('id-ID', options);
    };

    const studentExamConfig = {
        baseUrl: window.BASE_URL,
        entityName: 'Ujian',
        tableId: 'studentExamTable',
        
        // Mode Read Only: Tidak butuh Form/Modal ID
        readOnly: true, 

        csrf: { 
            tokenName: window.CSRF_TOKEN_NAME, 
            tokenHash: document.querySelector(`input[name="${window.CSRF_TOKEN_NAME}"]`)?.value || '' 
        },

        urls: {
            // Arahkan ke method get_student_exams
            load: `exam/get_student_exams/${CURRENT_CLASS_ID}`,
        },

        // Custom Mapper untuk tampilan Siswa
        dataMapper: (item, index) => {
            // Hitung sisa waktu simple (kosmetik)
            const endTime = new Date(item.end_time);
            const now = new Date();
            const diffMs = endTime - now;
            const diffHrs = Math.floor((diffMs % 86400000) / 3600000);
            const diffMins = Math.round(((diffMs % 86400000) % 3600000) / 60000);
            
            let timeBadge = '';
            if (diffMs > 0) {
                timeBadge = `<span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split"></i> Sisa: ${diffHrs}j ${diffMins}m</span>`;
            }

            // Tombol Aksi: Masuk ke halaman Detail/Konfirmasi
            const btnStart = `<a href="${window.BASE_URL}exam/confirmation/${item.exam_id}" class="btn btn-primary btn-sm w-100">
                <i class="bi bi-play-circle"></i> Kerjakan
            </a>`;

            return [
                index + 1,
                `<div class="fw-bold text-dark">${item.exam_name}</div>`,
                `<span class="badge bg-${item.type === 'UTS' ? 'info' : 'success'}">${item.type}</span>`,
                `<div>
                    <div class="small text-muted">Selesai: ${formatTime(item.end_time)}</div>
                    ${timeBadge}
                </div>`,
                btnStart
            ];
        }
    };

    new CrudHandler(studentExamConfig).init();
});