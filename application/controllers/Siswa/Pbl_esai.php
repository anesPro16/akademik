<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pbl_esai extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		is_logged_in(); // Pastikan otentikasi siswa
		$this->load->model('Pbl_esai_model');

		// Ambil user_id dari sesi (Asumsi data user disimpan di sesi)
		$this->user_id = $this->session->userdata('user_id'); 
		if (!$this->user_id) {
			// Jika user_id tidak ada (belum login), redirect ke halaman login
			redirect('auth/login'); 
		}
	}

	/**
	 * Halaman Detail Esai untuk Siswa (Menampilkan Pertanyaan & Form Jawab)
	 */
	public function detail($essay_id = null)
	{
		if (!$essay_id) redirect('siswa/pbl'); // Ganti ke halaman PBL Siswa

		$essay = $this->Pbl_esai_model->get_essay_details($essay_id);
		if (!$essay) show_404();

		$submission = $this->Pbl_esai_model->get_student_submission($essay_id, $this->user_id);
		$questions = $this->Pbl_esai_model->get_questions($essay_id);

		$data['title'] = 'Jawab Esai: ' . $essay->title;
		$data['essay'] = $essay;
		$data['questions'] = $questions;
		$data['submission'] = $submission; // Jawaban siswa saat ini (jika ada)
		$data['class_id'] = $essay->class_id;
		$data['user'] = $this->session->userdata();

		$this->load->view('templates/header', $data);
		$this->load->view('siswa/pbl_esai_detail', $data);
		$this->load->view('templates/footer');
	}

	/**
	 * AJAX: Menyimpan/Memperbarui Jawaban Siswa
	 */
	public function submit_answer()
	{
		$this->form_validation->set_rules('essay_id', 'Essay ID', 'required');
		$this->form_validation->set_rules('submission_content', 'Jawaban Esai', 'required|trim');

		if ($this->form_validation->run() === FALSE) {
			$this->output
				->set_content_type('application/json')
				->set_output(json_encode(['status' => 'error', 'message' => validation_errors()]));
			return;
		}

		$essay_id = $this->input->post('essay_id');
		$content = $this->input->post('submission_content');
		$submission_id = $this->input->post('submission_id'); // ID Submission, bisa kosong/null

		// Cek apakah esai ini ada
		$essay = $this->Pbl_esai_model->get_essay_details($essay_id);
		if (!$essay) {
			$this->output
				->set_content_type('application/json')
				->set_output(json_encode(['status' => 'error', 'message' => 'Esai tidak ditemukan.']));
			return;
		}

		$success = $this->Pbl_esai_model->save_student_submission($essay_id, $this->user_id, $content, $submission_id);

		if ($success) {
			$msg = $submission_id ? 'Jawaban berhasil diperbarui.' : 'Jawaban berhasil dikirim.';
			// Ambil ulang data submission terbaru (opsional, untuk tampilan real-time)
			$new_submission = $this->Pbl_esai_model->get_student_submission($essay_id, $this->user_id);

			echo json_encode([
				'status' => 'success',
				'message' => $msg,
				'data' => $new_submission,
				'csrf_hash' => $this->security->get_csrf_hash()
			]);
		} else {
			echo json_encode([
				'status' => 'error',
				'message' => 'Gagal menyimpan jawaban. Silakan coba lagi.',
				'csrf_hash' => $this->security->get_csrf_hash()
			]);
		}
	}
}


/* End of file Pbl_esai.php */
/* Location: ./application/controllers/Siswa/Pbl_esai.php */