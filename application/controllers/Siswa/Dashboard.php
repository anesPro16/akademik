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
    $role_id = $this->session->userdata('role_id');
    
    // Validasi Role (Logic tetap)
    $allowed_roles = ['Guru', 'Admin'];
    $data['is_admin_or_guru'] = $this->User_model->check_user_role($role_id, $allowed_roles);
    
    // Definisikan variabel logic untuk view (Sesuai JS yang diminta)
    $data['can_manage_students'] = $data['is_admin_or_guru']; // Asumsi: Guru/Admin boleh kelola
    $data['role_controller'] = $data['is_admin_or_guru'] ? 'guru' : 'siswa'; 

    // Ambil detail kelas (Model yang sudah diperbaiki)
    $data['kelas'] = $this->Murid_model->get_class_details($class_id);

    if (!$data['kelas']) {
        show_404(); // Atau redirect dengan flashdata error
    }

    // Ambil daftar siswa untuk modal (hanya jika admin/guru)
    $data['siswa_list'] = [];
    if ($data['is_admin_or_guru']) {
        $data['siswa_list'] = $this->User_model->get_students_by_role_name('siswa');
    }
    
    $data['title'] = 'Detail Kelas: ' . $data['kelas']->name;
    $data['user'] = $this->session->userdata();

    $this->load->view('templates/header', $data);
    $this->load->view('templates/sidebar');
    $this->load->view('Siswa/class_detail', $data); 
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
