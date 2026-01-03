<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan extends CI_Controller {

	public function __construct()
  {
    parent::__construct();
    is_logged_in();
  }

	/* =========================
   * GURU – DAFTAR Kelas
   * ========================= */
  public function index()
  {
    $user = $this->session->userdata();
    $role_id = $user['role_id'];
    $user_id = $user['user_id'];

    // 1. Validasi apakah user adalah Guru
    if (!$this->User_model->check_is_teacher($role_id)) {
        show_error('Akses ditolak. Halaman ini khusus Guru.', 403);
    }

    // 2. Ambil Data Kelas milik Guru tersebut
    // Kita asumsikan Guru_model menghandle logika pengambilan kelas berdasarkan user_id guru
    $data = [
        'title'      => 'Pilih Kelas',
        'user'       => $user,
        'kelas_list' => $this->Guru_model->get_all_classes($user_id), 
        'url_name'   => 'guru'
    ];

    $this->load->view('templates/header', $data);
    $this->load->view('templates/sidebar', $data);
    $this->load->view('guru/laporan', $data);
    $this->load->view('templates/footer');
  }

  public function detail_kelas($class_id = null)
  {
    if (!$class_id) {
      redirect('guru/dashboard');
    }

    $data['title'] = 'Laporan Hasil Belajar';
    $data['class_id'] = $class_id;
    $data['user'] = $this->session->userdata();
    $data['url_name'] = 'guru';
    $role_id = $this->session->userdata('role_id');    
    $data['is_admin_or_guru'] = $this->User_model->check_is_teacher($role_id);

    $data['exam_subjects'] = ['Matematika', 'IPA', 'IPS', 'Bahasa Indonesia', 'Bahasa Inggris', 'PPKN'];

    $this->load->view('templates/header', $data);
    $this->load->view('templates/sidebar', $data);
    $this->load->view('guru/pbl_tahap5', $data);
    $this->load->view('templates/footer');
  }

}

/* End of file Laporan.php */
/* Location: ./application/controllers/Guru/Laporan.php */