<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pbl extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    is_logged_in();
  }

  public function index($class_id = null)
  {
    if (!$class_id) redirect('guru/dashboard');
    $data['title'] = 'Tahap 1 - Orientasi Masalah';
    $data['url_name'] = 'guru';
    $data['class_id'] = $class_id;
    $data['user'] = $this->session->userdata();

    $role_id = $this->session->userdata('role_id');    
    $data['is_admin_or_guru'] = $this->User_model->check_is_teacher($role_id);

    $this->load->view('templates/header', $data);
    $this->load->view('guru/pbl_orientasi', $data);
    $this->load->view('templates/footer');
  }

  public function get_data($class_id)
  {
    $data = $this->Pbl_orientasi->get_all($class_id);

    $this->output
      ->set_content_type('application/json')
      ->set_output(json_encode($data));
  }

  public function save()
  {
    $id = $this->input->post('id');
    $class_id = $this->input->post('class_id');
    $title = $this->input->post('title');
    $reflection = $this->input->post('reflection');
    $file_path = '';

    // Upload file (opsional)
    if (!empty($_FILES['file']['name'])) {
      $config['upload_path'] = './uploads/pbl/';
      $config['allowed_types'] = 'jpg|jpeg|png|mp4|mp3|wav|pdf';
      $config['max_size'] = 10240;
      $config['file_name'] = generate_ulid();
      if (!is_dir($config['upload_path'])) mkdir($config['upload_path'], 0777, true);

      $this->upload->initialize($config);
      if ($this->upload->do_upload('file')) {
          $file_path = 'uploads/pbl/' . $this->upload->data('file_name');
      } else {
          echo json_encode(['status' => false, 'msg' => $this->upload->display_errors()]);
          return;
      }
    }

    if ($id == '') {
      // Create
      $data = [
          'id' => generate_ulid(),
          'class_id' => $class_id,
          'title' => $title,
          'reflection' => $reflection,
          'file_path' => $file_path,
          'created_at' => date('Y-m-d H:i:s')
      ];
      $insert = $this->Pbl_orientasi->insert($data);
      $status = $insert ? ['status' => true, 'msg' => 'Data berhasil ditambahkan'] : ['status' => false, 'msg' => 'Gagal menambah data'];
      $msg = 'Data berhasil ditambahkan';
    } else {
      $getData = $this->Pbl_orientasi->get_orientasi($id);
      if (!$getData) {
        echo json_encode(['status'=>'error','message'=>'materi tidak ada!', 'csrf_hash' => $this->security->get_csrf_hash()]);
        return;
      }
      // Update
      $data = [
          'title' => $title,
          'reflection' => $reflection
      ];
      if ($file_path) $data['file_path'] = $file_path;
      $update = $this->Pbl_orientasi->update($id, $data);
      $status = $update ? ['status' => true, 'msg' => 'Data berhasil diperbarui'] : ['status' => false, 'msg' => 'Gagal memperbarui data'];
      $msg = 'Data berhasil diperbarui';
    }

    echo json_encode([
    	'status' => 'success',
    	'message' => $msg,
    	'csrf_hash' => $this->security->get_csrf_hash()
    ]);
  }

  public function delete($id)
  {
  	$getData = $this->Pbl_orientasi->get_orientasi($id);
  	if (!$getData) {
  		echo json_encode(['status'=>'error','message'=>'Gagal hapus materi!', 'csrf_hash' => $this->security->get_csrf_hash()]);
  		return;
  	}

    $result = $this->Pbl_orientasi->delete($id);
    if ($result) {
  		$message = 'Materi dihapus';
  		$status = 'success';
  	}
  	
    echo json_encode([
        'status' => $status,
        'message' => $message,
        'csrf_hash' => $this->security->get_csrf_hash()
    ]);
  }

  public function tahap2($class_id = null)
	{
	  if (!$class_id) redirect('guru/dashboard');
	  $data['title'] = 'Tahap 2 – Organisasi Belajar';
	  $data['class_id'] = $class_id;
	  $data['user'] = $this->session->userdata();
    $data['url_name'] = 'guru';
    $role_id = $this->session->userdata('role_id');    
    $data['is_admin_or_guru'] = $this->User_model->check_is_teacher($role_id);

    $data['subjects'] = ['Matematika', 'IPA', 'IPS', 'Bahasa Indonesia', 'Bahasa Inggris', 'PPKN'];

	  $this->load->view('templates/header', $data);
	  $this->load->view('guru/pbl_tahap2', $data);
	  $this->load->view('templates/footer');
	}

	/*  CRUD KUIS  */
	public function get_quizzes($class_id)
	{
	  $data = $this->Pbl_tahap2_model->get_quizzes($class_id);
	  $this->output
         ->set_content_type('application/json')
         ->set_output(json_encode($data));
	}

	public function save_quiz()
	{
	  $id = $this->input->post('id');
	  $payload = [
	      'class_id' => $this->input->post('class_id'),
	      'title' => $this->input->post('title'),
	      'description' => $this->input->post('description')
	  ];
	  if ($id) {
      $getQuiz = $this->Pbl_tahap2_model->get_quiz_by_id($id);
      if (!$getQuiz) {
        echo json_encode(['status'=>'error','message'=>'Kuis tidak ada!', 'csrf_hash' => $this->security->get_csrf_hash()]);
        return;
      }
	    $this->Pbl_tahap2_model->update_quiz($id, $payload);
	    $msg = 'Kuis diperbarui';
	  } else {
	    $payload['id'] = generate_ulid();
	    $this->Pbl_tahap2_model->insert_quiz($payload);
	    $msg = 'Kuis ditambahkan';
	  }
	  
	  echo json_encode([
        'status' => 'success',
        'message' => $msg,
        'csrf_hash' => $this->security->get_csrf_hash()
    ]);
	}

	public function delete_quiz()
  {
    $id = $this->input->post('id'); // Ambil dari POST, bukan URL
    
    $getQuiz = $this->Pbl_tahap2_model->get_quiz_by_id($id);
    if (!$getQuiz) {
      echo json_encode(['status'=>'error','message'=>'Gagal hapus Kuis!', 'csrf_hash' => $this->security->get_csrf_hash()]);
      return;
    }

    $this->Pbl_tahap2_model->delete_quiz($id);

    echo json_encode([
      'status' => 'success',
      'message' => 'kuis dihapus',
      'csrf_hash' => $this->security->get_csrf_hash()
    ]);
  }


  /**
   *  Halaman utama untuk Tahap 3
   */
  public function tahap3($class_id = null)
  {
    if (!$class_id) {
      redirect('guru/dashboard'); // Arahkan ke dashboard jika class_id tidak ada
    }

    $data['title'] = 'Tahap 3 – Observasi';
    $data['class_id'] = $class_id;
    $data['user'] = $this->session->userdata();

    $data['url_name'] = 'guru';
    $role_id = $this->session->userdata('role_id');    
    $data['is_admin_or_guru'] = $this->User_model->check_is_teacher($role_id);

    $data['subjects'] = ['Matematika', 'IPA', 'IPS', 'Bahasa Indonesia', 'Bahasa Inggris', 'PPKN'];

    $this->load->view('templates/header', $data);
    $this->load->view('guru/pbl_tahap3', $data);
    $this->load->view('templates/footer');
  }

  /*   CRUD RUANG OBSERVASI  */
  public function get_observations($class_id)
  {
    $data = $this->Pbl_tahap3_model->get_observations($class_id);
    $this->output
      ->set_content_type('application/json')
      ->set_output(json_encode($data));
  }

  public function save_observation()
  {
    $this->form_validation->set_rules('title', 'Judul', 'required');

    if ($this->form_validation->run() === FALSE) {
      $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode(['status' => 'error', 'message' => validation_errors()]));
      return;
    }

    $id = $this->input->post('id');
    $payload = [
      'class_id' => $this->input->post('class_id'),
      'title' => $this->input->post('title'),
      'description' => $this->input->post('description')
    ];

    if ($id) {
      $getData = $this->Pbl_tahap3_model->get_observation($id);
      if (!$getData) {
        echo json_encode(['status'=>'error','message'=>'Observasi tidak ada!', 'csrf_hash' => $this->security->get_csrf_hash()]);
        return;
      }
      $this->Pbl_tahap3_model->update_observation($id, $payload);
      $msg = 'Ruang Observasi diperbarui';
    } else {
      $payload['id'] = generate_ulid();
      $this->Pbl_tahap3_model->insert_observation($payload);
      $msg = 'Ruang Observasi ditambahkan';
    }
    echo json_encode([
      'status' => 'success',
      'message' => $msg,
      'csrf_hash' => $this->security->get_csrf_hash()
    ]);
  }

  public function delete_observation($id = null)
  {
    $getData = $this->Pbl_tahap3_model->get_observation($id);
    if (!$getData) {
      echo json_encode(['status'=>'error','message'=>'Gagal hapus observasi!', 'csrf_hash' => $this->security->get_csrf_hash()]);
      return;
    }
      
    if ($id) {
      $this->Pbl_tahap3_model->delete_observation($id);
      $msg = 'Ruang Observasi dihapus.';
      $status = 'success';
    }

    echo json_encode([
      'status' => $status,
      'message' => $msg,
      'csrf_hash' => $this->security->get_csrf_hash()
    ]);
  }


  /**
   *  Halaman utama untuk Tahap 4
   */
  public function tahap4($class_id = null)
  {
    if (!$class_id) {
      redirect('guru/dashboard');
    }

    $data['title'] = 'Tahap 4 – Pengembangan Solusi';
    $data['class_id'] = $class_id;
    $data['user'] = $this->session->userdata();
    $data['url_name'] = 'guru';
    $role_id = $this->session->userdata('role_id');    
    $data['is_admin_or_guru'] = $this->User_model->check_is_teacher($role_id);

    $data['subjects'] = ['Matematika', 'IPA', 'IPS', 'Bahasa Indonesia', 'Bahasa Inggris', 'PPKN'];

    $this->load->view('templates/header', $data);
    // $this->load->view('templates/sidebar');
    $this->load->view('guru/pbl_tahap4', $data);
    $this->load->view('templates/footer');
  }

  /*   CRUD ESAI SOLUSI  */
  public function get_solution_essays($class_id)
  {
    $data = $this->Pbl_tahap4_model->get_solution_essays($class_id);
    $this->output
      ->set_content_type('application/json')
      ->set_output(json_encode($data));
  }

  public function save_solution_essay()
  {
    $this->form_validation->set_rules('title', 'Judul Esai', 'required');

    if ($this->form_validation->run() === FALSE) {
      $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode(['status' => 'error', 'message' => validation_errors()]));
      return;
    }

    $id = $this->input->post('id');
    $payload = [
      'class_id' => $this->input->post('class_id'),
      'title' => $this->input->post('title'),
      'description' => $this->input->post('description')
    ];

    if ($id) {
      $getData = $this->Pbl_tahap4_model->get_solution_essay($id);
      if (!$getData) {
        echo json_encode(['status'=>'error','message'=>'Esai tidak ada!', 'csrf_hash' => $this->security->get_csrf_hash()]);
        return;
      }
      $this->Pbl_tahap4_model->update_solution_essay($id, $payload);
      $msg = 'Aktivitas Esai diperbarui';
    } else {
      $payload['id'] = generate_ulid();
      $this->Pbl_tahap4_model->insert_solution_essay($payload);
      $msg = 'Aktivitas Esai ditambahkan';
    }
    echo json_encode([
      'status' => 'success',
      'message' => $msg,
      'csrf_hash' => $this->security->get_csrf_hash()
    ]);
  }

  public function delete_solution_essay($id = null)
  {
    $getData = $this->Pbl_tahap4_model->get_solution_essay($id);
    if (!$getData) {
      echo json_encode(['status'=>'error','message'=>'Gagal hapus Esai!', 'csrf_hash' => $this->security->get_csrf_hash()]);
      return;
    }

    if ($id) {
      $this->Pbl_tahap4_model->delete_solution_essay($id);
      $msg = 'Aktivitas Esai dihapus.';
      $status = 'success';
    }
    echo json_encode([
      'status' => $status,
      'message' => $msg,
      'csrf_hash' => $this->security->get_csrf_hash()
    ]);
  }

  /*   CRUD KUIS EVALUASI  */
  public function get_evaluation_quizzes($class_id)
  {
    $data = $this->Pbl_tahap4_model->get_evaluation_quizzes($class_id);
    $this->output
      ->set_content_type('application/json')
      ->set_output(json_encode($data));
  }

  public function save_evaluation_quiz()
  {
    $this->form_validation->set_rules('title', 'Judul Kuis', 'required');

    if ($this->form_validation->run() === FALSE) {
      $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode(['status' => 'error', 'message' => validation_errors()]));
      return;
    }

    $id = $this->input->post('id');
    $payload = [
      'class_id' => $this->input->post('class_id'),
      'title' => $this->input->post('title'),
      'description' => $this->input->post('description')
    ];

    if ($id) {
      $getData = $this->Pbl_tahap4_model->get_evaluation_quiz($id);
      if (!$getData) {
        echo json_encode(['status'=>'error','message'=>'Kuis tidak ada!', 'csrf_hash' => $this->security->get_csrf_hash()]);
        return;
      }

      $this->Pbl_tahap4_model->update_evaluation_quiz($id, $payload);
      $msg = 'Kuis Evaluasi diperbarui';
    } else {
      $payload['id'] = generate_ulid();
      $this->Pbl_tahap4_model->insert_evaluation_quiz($payload);
      $msg = 'Kuis Evaluasi ditambahkan';
    }
    echo json_encode([
      'status' => 'success',
      'message' => $msg,
      'csrf_hash' => $this->security->get_csrf_hash()
    ]);
  }

  public function delete_evaluation_quiz($id = null)
  {
    $getData = $this->Pbl_tahap4_model->get_evaluation_quiz($id);
    if (!$getData) {
      echo json_encode(['status'=>'error','message'=>'Gagal hapus kuis!', 'csrf_hash' => $this->security->get_csrf_hash()]);
      return;
    }

    if ($id) {
      $this->Pbl_tahap4_model->delete_evaluation_quiz($id);
      $msg = 'Kuis Evaluasi dihapus.';
      $status = 'success';
    }
    echo json_encode([
      'status' => $status,
      'message' => $msg,
      'csrf_hash' => $this->security->get_csrf_hash()
    ]);
  }

  /**
   * Halaman utama untuk Tahap 5
   */
  public function tahap5($class_id = null)
  {
    if (!$class_id) {
      redirect('guru/dashboard');
    }

    $data['title'] = 'Tahap 5 – Refleksi Akhir';
    $data['class_id'] = $class_id;
    $data['user'] = $this->session->userdata();
    $data['url_name'] = 'guru';
    $role_id = $this->session->userdata('role_id');    
    $data['is_admin_or_guru'] = $this->User_model->check_is_teacher($role_id);

    $data['exam_subjects'] = ['Matematika', 'IPA', 'IPS', 'Bahasa Indonesia', 'Bahasa Inggris', 'PPKN'];

    $this->load->view('templates/header', $data);
    $this->load->view('guru/pbl_tahap5', $data);
    $this->load->view('templates/footer');
  }

  public function get_student_recap($class_id)
  {
    $this->load->model('Refleksi_model');
    $students = $this->Refleksi_model->getAllStudentScores($class_id);
    
    // Return JSON langsung untuk ditangkap fetch JS
    echo json_encode($students);
  }

  public function save_reflection()
  {
    $this->form_validation->set_rules('user_id', 'ID Siswa', 'required');
    $this->form_validation->set_rules('class_id', 'ID Kelas', 'required');

    if ($this->form_validation->run() === FALSE) {
      $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode([
          'status' => 'error', 
          'message' => validation_errors(),
          'csrf_hash' => $this->security->get_csrf_hash() // Update CSRF
        ]));
      return;
    }

  $this->load->model('Refleksi_model');

  // 3. Siapkan Data
  $data = [
    'class_id' => $this->input->post('class_id'),
    'user_id' => $this->input->post('user_id'),
    'teacher_reflection' => $this->input->post('teacher_reflection'),
    'student_feedback' => $this->input->post('student_feedback'),
  ];

  // 4. Simpan ke Database
  // Model akan menangani logika Insert (jika baru) atau Update (jika sudah ada)
  $saved = $this->Refleksi_model->save_reflection($data);

  // 5. Return JSON sukses + CSRF Hash baru
  if ($saved) {
    echo json_encode([
      'status' => 'success',
      'message' => 'Refleksi dan Feedback berhasil disimpan.',
      'csrf_hash' => $this->security->get_csrf_hash()
    ]);
  } else {
      echo json_encode([
        'status' => 'error',
        'message' => 'Gagal menyimpan data ke database.',
        'csrf_hash' => $this->security->get_csrf_hash()
      ]);
    }
  }

  // --- AJAX: Save Grade ---
  public function save_grade()
  {
    $this->form_validation->set_rules('final_score', 'Nilai Akhir', 'required|numeric|greater_than_equal_to[0]|less_than_equal_to[100]');
    $this->form_validation->set_rules('feedback', 'Feedback', 'required|trim');
    $this->form_validation->set_rules('status', 'Status', 'required|in_list[draft,published]');

    if ($this->form_validation->run() === FALSE) {
        echo json_encode(['status' => 'error', 'message' => validation_errors()]);
        return;
    }

    $data = [
      'class_id'    => $this->input->post('class_id'),
      'user_id'     => $this->input->post('user_id'),
      'final_score' => $this->input->post('final_score'),
      'feedback'    => $this->input->post('feedback'),
      'status'      => $this->input->post('status')
    ];

    if ($this->Pbl_tahap5_model->save_grade($data)) {
      echo json_encode([
        'status' => 'success', 
        'message' => 'Nilai berhasil disimpan.',
        'csrf_hash' => $this->security->get_csrf_hash()
      ]);
    } else {
      echo json_encode([
        'status' => 'error', 
        'message' => 'Gagal menyimpan database.',
        'csrf_hash' => $this->security->get_csrf_hash()
      ]);
    }
  }

  
}

/* End of file Pbl.php */
/* Location: ./application/controllers/Guru/Pbl.php */