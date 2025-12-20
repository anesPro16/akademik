/**
 * pbl_esai_detail_siswa.js
 * Menangani pengiriman dan pembaruan jawaban esai siswa.
 */

document.addEventListener('DOMContentLoaded', () => {
    
    // Asumsi dependensi: Bootstrap, SweetAlert2 (Swal)

    const form = document.getElementById('submissionForm');
    const submitButton = document.getElementById('submitButton');
    const submissionIdField = document.getElementById('submissionId');
    const statusText = document.getElementById('statusText');
    const feedbackCard = document.getElementById('feedbackCard');
    const feedbackContent = document.getElementById('feedbackContent');
    const csrfName = window.CSRF_TOKEN_NAME;
    let csrfHash = document.querySelector(`input[name="${csrfName}"]`).value;
    const saveUrl = `${window.BASE_URL}siswa/pbl_esai/submit_answer`;
    
    // --- Helper Functions ---
    
    const showToast = (icon, title) => {
        Swal.fire({
            icon: icon,
            title: title,
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });
    };

    const updateCsrfToken = (newHash) => {
        if (newHash) {
            csrfHash = newHash;
            // Update semua input CSRF di halaman
            document.querySelectorAll(`input[name="${csrfName}"]`).forEach(token => {
                token.value = newHash;
            });
        }
    };

    const updateStatusUI = (submission) => {
        const grade = submission.grade;
        const updatedAt = new Date(submission.updated_at).toLocaleString('id-ID', {
            day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit'
        });

        if (grade !== null) {
            statusText.innerHTML = `
                <span class="text-success fw-bold">Sudah Dinilai!</span> | Nilai Anda: 
                <span class="badge bg-success fs-6">${grade}</span> | Terakhir Diperbarui: ${updatedAt}
            `;
            if (submission.feedback) {
                feedbackContent.innerText = submission.feedback;
                feedbackCard.style.display = 'block';
            }
        } else {
            statusText.innerHTML = `
                <span class="text-warning fw-bold">Sudah Dikirim!</span> Menunggu penilaian guru. | Terakhir Dikirim: ${updatedAt}
            `;
            feedbackCard.style.display = 'none';
        }
    };

    // --- Submission Logic ---

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        submitButton.disabled = true;
        submitButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Mengirim...';

        const formData = new FormData(form);
        formData.set(csrfName, csrfHash); // Setel hash CSRF

        try {
            // Fetch API untuk mengirim data
            const response = await fetch(saveUrl, {
                method: 'POST',
                body: formData
            });

            if (!response.ok) {
                throw new Error('Network response was not ok');
            }

            const result = await response.json();
            updateCsrfToken(result.csrf_hash);

            if (result.status === 'success') {
                showToast('success', result.message);
                
                // Perbarui ID submission (jika ini adalah kiriman pertama)
                if (!submissionIdField.value && result.data && result.data.id) {
                    submissionIdField.value = result.data.id;
                }
                
                // Perbarui status UI
                if (result.data) {
                    updateStatusUI(result.data);
                }

            } else {
                Swal.fire('Gagal!', result.message, 'error');
            }

        } catch (error) {
            console.error('Submission Error:', error);
            Swal.fire('Error', 'Terjadi kesalahan saat mengirim jawaban.', 'error');
        } finally {
            submitButton.disabled = false;
            submitButton.innerHTML = '<i class="bi bi-send-fill"></i> Kirim Jawaban';
        }
    });

    // Catatan: CrudHandler tidak digunakan karena ini bukan manajemen data tabel, 
    // melainkan operasi formulir tunggal (Create/Update).
});