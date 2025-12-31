<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Exam extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('Exam_model');
    $this->load->model('User_model');
    $this->load->helper(['security']);
    date_default_timezone_set('Asia/Jakarta');
  }

  /* =========================
   * GURU – DAFTAR UJIAN
   * ========================= */
  public function index($class_id = null)
  {
    if (!$class_id) redirect('guru/dashboard');

    $user = $this->session->userdata();
    $role_id = $user['role_id'];

    // Validasi guru
    if (!$this->User_model->check_is_teacher($role_id)) {
      show_error('Akses ditolak', 403);
    }
    $teacher_id = $this->Exam_model->getTeacherId($user['user_id']);
    // Validasi kelas milik guru
    if (!$this->Exam_model->is_teacher_class($class_id, $teacher_id)) {
      show_error('Bukan kelas Anda', 403);
    }

    // [BARU] Data Mapel untuk Dropdown
    $subjects = ['Matematika', 'IPA', 'IPS', 'Bahasa Indonesia', 'Bahasa Inggris', 'PPKN'];

    $data = [
      'title'    => 'Manajemen Ujian',
      'class_id' => $class_id,
      'user'     => $user,
      'subjects' => $subjects, 
      'url_name' => 'guru'
    ];

    $this->load->view('templates/header', $data);
    $this->load->view('exam/index', $data);
    $this->load->view('templates/footer');
  }

  private function _auto_update_status($class_id)
  {
    // Set is_active = 0 jika end_time < waktu sekarang
    $now = date('Y-m-d H:i:s');
    $this->db->where('class_id', $class_id)
	    ->where('end_time <', $now)
	    ->where('is_active', 1)
	    ->update('exams', ['is_active' => 0]);
  }

  /* =========================
   * AJAX – GET UJIAN
   * ========================= */
  public function get_exams($class_id)
  {
  	$this->_auto_update_status($class_id);

    $data = $this->Exam_model->get_by_class($class_id);

    $this->output
      ->set_content_type('application/json')
      ->set_output(json_encode($data));
  }

  /* =========================
   * AJAX – SIMPAN UJIAN
   * ========================= */
  public function save()
  {
    $class_id = $this->input->post('class_id', true);
    $start_time = $this->input->post('start_time', true);
    $end_time   = $this->input->post('end_time', true);
    $id = $this->input->post('exam_id');
    $now        = date('Y-m-d H:i:s');

    if (!$id && $start_time < $now) {
        echo json_encode(['status'=>'error', 'message'=>'Waktu mulai tidak boleh kurang dari waktu sekarang!', 'csrf_hash'=>$this->security->get_csrf_hash()]);
        return;
    }

    if ($end_time <= $start_time) {
        echo json_encode(['status'=>'error', 'message'=>'Waktu selesai harus lebih besar dari waktu mulai!', 'csrf_hash'=>$this->security->get_csrf_hash()]);
        return;
    }

    // Proteksi kelas
    // $user_id = $this->session->userdata('user_id');
    $user = $this->session->userdata();
    $teacher_id = $this->Exam_model->getTeacherId($user['user_id']);
    if (!$this->Exam_model->is_teacher_class($class_id, $teacher_id)) {
      echo json_encode([
        'status' => 'error',
        'message' => 'Akses ditolak',
        'csrf_hash' => $this->security->get_csrf_hash()
      ]);
      return;
    }

    $payload = [
      'class_id'   => $class_id,
      'exam_name'  => $this->input->post('exam_name', true),
      'type'       => $this->input->post('type', true),
      'start_time' => $start_time,
      'end_time'   => $end_time,
      'is_active'  => 1
    ];

    if ($id) {
      $exam = $this->Exam_model->get_by_id($id);
      if (!$exam) {
        echo json_encode(['status'=>'error','message'=>'Ujian tidak ditemukan','csrf_hash'=>$this->security->get_csrf_hash()]);
        return;
      }
      $this->Exam_model->update($id, $payload);
      $msg = 'Ujian diperbarui';
    } else {
      $payload['exam_id'] = generate_ulid();
      $this->Exam_model->insert($payload);
      $msg = 'Ujian ditambahkan';
    }

    echo json_encode([
      'status' => 'success',
      'message' => $msg,
      'csrf_hash' => $this->security->get_csrf_hash()
    ]);
  }

  /* =========================
   * AJAX – HAPUS
   * ========================= */
  public function delete()
  {
    $id = $this->input->post('id');

    $exam = $this->Exam_model->get_by_id($id);
    if (!$exam) {
      echo json_encode(['status'=>'error','message'=>'Data tidak ada','csrf_hash'=>$this->security->get_csrf_hash()]);
      return;
    }

    $this->Exam_model->delete($id);

    echo json_encode([
      'status' => 'success',
      'message' => 'Ujian dihapus',
      'csrf_hash' => $this->security->get_csrf_hash()
    ]);
  }

  public function questions($exam_id = null)
{
    if (!$exam_id) show_404();

    $user = $this->session->userdata();
    $teacher_id = $this->Exam_model->getTeacherId($user['user_id']);

    $exam = $this->Exam_model->get_exam_with_class($exam_id);
    if (!$exam) show_404();

    // validasi ujian milik guru
    if (!$this->Exam_model->is_teacher_class($exam->class_id, $teacher_id)) {
        show_error('Akses ditolak', 403);
    }

    $data = [
        'title' => 'Soal Ujian',
        'exam'  => $exam,
        'user'     => $user,
    ];

    $this->load->view('templates/header', $data);
    $this->load->view('exam/questions', $data);
    $this->load->view('templates/footer');
}

public function get_questions($exam_id)
{
    $data = $this->Exam_model->get_questions($exam_id);
    echo json_encode($data);
}

public function save_question()
{
    $id = $this->input->post('id');
    $payload = [
        'exam_id' => $this->input->post('exam_id'),
        'question' => $this->input->post('question', true),
        'option_a' => $this->input->post('option_a', true),
        'option_b' => $this->input->post('option_b', true),
        'option_c' => $this->input->post('option_c', true),
        'option_d' => $this->input->post('option_d', true),
        'correct_answer' => $this->input->post('correct_answer', true)
    ];

    if ($id) {
        $this->Exam_model->update_question($id, $payload);
        $msg = 'Soal diperbarui';
    } else {
        $payload['id'] = generate_ulid();
        $this->Exam_model->insert_question($payload);
        $msg = 'Soal ditambahkan';
    }

    echo json_encode([
        'status' => 'success',
        'message' => $msg,
        'csrf_hash' => $this->security->get_csrf_hash()
    ]);
}

public function delete_question()
{
    $id = $this->input->post('id');
    $this->Exam_model->delete_question($id);

    echo json_encode([
        'status' => 'success',
        'message' => 'Soal dihapus',
        'csrf_hash' => $this->security->get_csrf_hash()
    ]);
}

	public function save_questions_batch()
{
    // Validasi Session & Guru (Sama seperti sebelumnya)
    $user = $this->session->userdata();
    $teacher_id = $this->Exam_model->getTeacherId($user['user_id']);
    
    $exam_id = $this->input->post('exam_id');
    $exam = $this->Exam_model->get_exam_with_class($exam_id);
    
    if (!$exam || !$this->Exam_model->is_teacher_class($exam->class_id, $teacher_id)) {
        echo json_encode(['status' => 'error', 'message' => 'Akses ditolak']);
        return;
    }

    // Ambil data array dari form
    $questions = $this->input->post('question'); // Ini array
    $opt_a = $this->input->post('option_a');
    $opt_b = $this->input->post('option_b');
    $opt_c = $this->input->post('option_c');
    $opt_d = $this->input->post('option_d');
    $correct = $this->input->post('correct_answer');

    $batch_data = [];
    $timestamp = date('Y-m-d H:i:s');

    if (!empty($questions)) {
        foreach ($questions as $key => $val) {
            // Pastikan soal tidak kosong
            if (trim($val) == '') continue;

            $batch_data[] = [
                'id' => generate_ulid(), // Pastikan helper generate_ulid dipanggil unik tiap iterasi
                'exam_id' => $exam_id,
                'question' => $val,
                'option_a' => $opt_a[$key],
                'option_b' => $opt_b[$key],
                'option_c' => $opt_c[$key],
                'option_d' => $opt_d[$key],
                'correct_answer' => $correct[$key],
                'created_at' => $timestamp
            ];
        }
    }

    if (count($batch_data) > 0) {
        $this->Exam_model->insert_batch_questions($batch_data);
        $msg = count($batch_data) . ' soal berhasil ditambahkan.';
        $status = 'success';
    } else {
        $msg = 'Tidak ada data soal yang disimpan.';
        $status = 'error';
    }

    echo json_encode([
        'status' => $status,
        'message' => $msg,
        'csrf_hash' => $this->security->get_csrf_hash()
    ]);
}

	/* =========================
 * SISWA – AREA
 * ========================= */

public function student_list($class_id = null)
{
    if (!$class_id) show_404();
    
    $user = $this->session->userdata();
    // Asumsi: role_id 2 adalah siswa (sesuaikan dengan logic auth Anda)
    /*if ($user['role_id'] != 2) { 
        show_error('Akses khusus siswa', 403);
    }*/

    $data = [
        'title'    => 'Daftar Ujian Aktif',
        'class_id' => $class_id,
        'user'     => $user,
        'url_name' => 'siswa' // Untuk helper link di view
    ];

    $this->load->view('templates/header', $data);
    $this->load->view('exam/student_list', $data);
    $this->load->view('templates/footer');
}

// JSON Provider untuk Tabel Siswa
public function get_student_exams($class_id)
{
    // 1. Jalankan auto update status dulu agar data basi tidak muncul
    $this->_auto_update_status($class_id);

    // 2. Ambil hanya yang aktif
    $data = $this->Exam_model->get_active_exams_by_class($class_id);

    $this->output
      ->set_content_type('application/json')
      ->set_output(json_encode($data));
}

// Halaman Detail / Konfirmasi Sebelum Mulai
public function confirmation($exam_id = null)
{
    if (!$exam_id) show_404();

    // Validasi dasar
    $exam = $this->Exam_model->get_exam_with_class($exam_id);
    if (!$exam) show_404();

    // Cek apakah ujian masih aktif
    if ($exam->is_active == 0 || strtotime($exam->end_time) < time()) {
        $this->session->set_flashdata('error', 'Ujian ini sudah tidak aktif atau waktu habis.');
        redirect('siswa/pbl/exam/student_list/' . $exam->class_id);
    }
    
    // Cek apakah waktu mulai sudah masuk
    if (strtotime($exam->start_time) > time()) {
        $this->session->set_flashdata('error', 'Ujian belum dimulai.');
        redirect('siswa/pbl/exam/student_list/' . $exam->class_id);
    }

    $data = [
        'title' => 'Detail Ujian',
        'exam'  => $exam
    ];

    $this->load->view('templates/header', $data);
    $this->load->view('exam/confirmation', $data);
    $this->load->view('templates/footer');
}




}



/* End of file Exam.php */
/* Location: ./application/controllers/Exam.php */