<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pbl_esai extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		// is_logged_in();
		$this->load->model('Pbl_esai_model'); // Model BARU
		$this->load->library('form_validation');
		$this->load->library('session');
		$this->load->helper('security');
		$this->load->helper('url');
		// $this->load->helper('ulid');
	}

	/**
	 * Halaman Detail Esai (Menampilkan List Jawaban Siswa)
	 */
	public function detail($essay_id = null)
	{
		if (!$essay_id) redirect('guru/pbl');

		$essay = $this->Pbl_esai_model->get_essay_details($essay_id);
		if (!$essay) show_404();

		$data['title'] = 'Review Esai: ' . $essay->title;
		$data['essay'] = $essay;
		$data['class_id'] = $essay->class_id; // Ambil class_id dari esai
		$data['user'] = $this->session->userdata();

		$this->load->view('templates/header', $data);
		$this->load->view('guru/pbl_esai_detail', $data); // View Detail BARU
		$this->load->view('templates/footer');
	}

	/* ===== AJAX UNTUK CRUD NILAI/FEEDBACK ===== */

	/**
	 * AJAX: Mengambil semua jawaban siswa untuk CrudHandler
	 */
	public function get_submissions($essay_id)
	{
		$data = $this->Pbl_esai_model->get_submissions($essay_id);
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($data));
	}

	/**
	 * AJAX: Menyimpan Nilai & Feedback (Ini adalah 'Update' CrudHandler)
	 */
	public function save_feedback()
	{
		$this->form_validation->set_rules('id', 'Submission ID', 'required');
		$this->form_validation->set_rules('grade', 'Nilai', 'numeric');
		$this->form_validation->set_rules('feedback', 'Feedback', 'trim');

		if ($this->form_validation->run() === FALSE) {
			$this->output
				->set_content_type('application/json')
				->set_output(json_encode(['status' => 'error', 'message' => validation_errors()]));
			return;
		}

		$submission_id = $this->input->post('id');
		
		$payload = [
			'grade' => $this->input->post('grade'),
			'feedback' => $this->input->post('feedback')
		];

		// (Tambahkan cek keamanan jika perlu:
		// $submission = $this->Pbl_esai_model->get_submission_by_id($submission_id);
		// if($submission->class_id != $this->session->userdata('class_id')... )
		
		$this->Pbl_esai_model->save_feedback($submission_id, $payload);
		$msg = 'Nilai & Feedback berhasil disimpan.';
		
		echo json_encode([
			'status' => 'success',
			'message' => $msg,
			'csrf_hash' => $this->security->get_csrf_hash()
		]);
	}

	/* ===== AJAX UNTUK CRUD PERTANYAAN ESAI (QUESTION) ===== */

	/**
	 * AJAX: Mengambil semua pertanyaan esai
	 */
	public function get_questions($essay_id)
	{
		$data = $this->Pbl_esai_model->get_questions($essay_id);
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($data));
	}

	/**
	 * AJAX: Menyimpan (Create/Update) pertanyaan esai
	 */
	public function save_question()
	{
		$this->form_validation->set_rules('essay_id', 'Essay ID', 'required');
		$this->form_validation->set_rules('question_number', 'Nomor Pertanyaan', 'required|numeric|greater_than[0]');
		$this->form_validation->set_rules('question_text', 'Teks Pertanyaan', 'required|trim');
		$this->form_validation->set_rules('weight', 'Bobot Nilai', 'required|numeric|greater_than[0]');

		if ($this->form_validation->run() === FALSE) {
			$this->output
				->set_content_type('application/json')
				->set_output(json_encode(['status' => 'error', 'message' => validation_errors(), 'csrf_hash' => $this->security->get_csrf_hash()]));
			return;
		}

		$id = $this->input->post('id'); // Bisa null untuk Add
		
		$payload = [
			'essay_id' => $this->input->post('essay_id'),
			'question_number' => $this->input->post('question_number'),
			'question_text' => $this->input->post('question_text'),
			'weight' => $this->input->post('weight')
		];

		$this->Pbl_esai_model->save_question($payload, $id);

		$msg = $id ? 'Pertanyaan berhasil diperbarui.' : 'Pertanyaan baru berhasil ditambahkan.';
		
		echo json_encode([
			'status' => 'success',
			'message' => $msg,
			'csrf_hash' => $this->security->get_csrf_hash()
		]);
	}

	/**
	 * AJAX: Menghapus pertanyaan esai
	 */
	public function delete_question()
	{
		$this->form_validation->set_rules('id', 'ID Pertanyaan', 'required');

		if ($this->form_validation->run() === FALSE) {
			$this->output
				->set_content_type('application/json')
				->set_output(json_encode(['status' => 'error', 'message' => validation_errors()]));
			return;
		}

		$id = $this->input->post('id');
		
		if ($this->Pbl_esai_model->delete_question($id)) {
			$msg = 'Pertanyaan berhasil dihapus.';
			$status = 'success';
		} else {
			$msg = 'Gagal menghapus pertanyaan.';
			$status = 'error';
		}
		
		echo json_encode([
			'status' => $status,
			'message' => $msg,
			'csrf_hash' => $this->security->get_csrf_hash()
		]);
	}
	
}

/* End of file Pbl_esai.php */
/* Location: ./application/controllers/Guru/Pbl_esai.php */