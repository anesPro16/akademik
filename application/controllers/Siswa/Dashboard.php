<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		is_logged_in();
	}

	public function index()
	{
		$data['title'] = 'Dashboard Siswa';
		$data['user'] = $this->session->userdata();
		$user_id = $this->session->userdata('user_id');

    // Ambil daftar sekolah dari model
		$data['sekolah_list'] = $this->Murid_model->get_sekolah_by_guru($user_id);
		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('dashboard/siswa', $data);
		$this->load->view('templates/footer');
	}

	/**
   * [PAGE LOAD] Menampilkan halaman detail kelas (daftar siswa).
   */
  public function class_detail($class_id = null)
  {
    if (!$class_id) redirect('guru/dashboard');
    
    $user_id = $this->session->userdata('user_id');
    
    // 1. Dapatkan role_id dari session (Tugas Controller)
    $role_id = $this->session->userdata('role_id');
    
    // 2. Definisikan peran yang diizinkan untuk aksi ini
    $allowed_roles = ['Guru', 'Admin'];

    // 3. Panggil model untuk validasi (Tugas Model)
    $data['is_admin_or_guru'] = $this->User_model->check_user_role(
        $role_id, 
        $allowed_roles
    );
    
    // 1. Ambil detail kelas (pastikan milik guru ini)
    $data['kelas'] = $this->Murid_model->get_class_details($class_id);

    // 2. Ambil daftar siswa yang 'tersedia' (untuk modal dropdown)
    // $data['siswa_list'] = $this->Guru_model->get_available_students();
    $data['siswa_list'] = $this->User_model->get_by_role_name('siswa');
    
    $data['title'] = 'Detail Kelas: ' . $data['kelas']->name;
    $data['user'] = $this->session->userdata();

    // Muat view baru
    $this->load->view('templates/header', $data);
    $this->load->view('templates/sidebar');
    $this->load->view('Siswa/class_detail', $data); // <-- VIEW BARU
    $this->load->view('templates/footer');
  }

  /**
   * [AJAX LOAD] Mengambil daftar siswa UNTUK KELAS INI (untuk CrudHandler).
   */
  public function getStudentListForClass($class_id)
  {
    // Anda mungkin ingin validasi bahwa $class_id ini milik guru yg login
    $data = $this->Guru_model->get_students_in_class($class_id);
    $this->output
      ->set_content_type('application/json')
      ->set_output(json_encode($data));
  }

}

/* End of file Dashboard.php */
/* Location: ./application/controllers/Siswa/Dashboard.php */
