<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Refleksi_model extends CI_Model {

	public function __construct() {
		parent::__construct();
	}

  /**
   * Mengambil rekap nilai siswa + data refleksi jika ada
   * UPDATE: Menggunakan tabel 'students' sebagai penghubung
   */
  public function getAllStudentScores($class_id)
    {
        // PERBAIKAN: Gunakan MAX() pada kolom tabel pbl_reflections (alias r)
        // untuk mengatasi error ONLY_FULL_GROUP_BY
        $this->db->select('
            u.id as user_id, 
            u.name as student_name, 
            u.image,
            
            -- Nilai Tahap 2 (Quiz & TTS)
            COALESCE((SELECT SUM(score) FROM pbl_quiz_results WHERE user_id = u.id), 0) as quiz_score,

            -- Nilai Tahap 3 (Observasi)
            COALESCE((SELECT SUM(score) FROM pbl_observation_results WHERE user_id = u.id), 0) as obs_score,

            -- Nilai Tahap 4 (Esai)
            COALESCE((SELECT SUM(grade) FROM pbl_essay_submissions WHERE user_id = u.id), 0) as essay_score,

            -- Data Refleksi (Tahap 5) - DIBUNGKUS MAX()
            MAX(r.id) as reflection_id,
            MAX(r.teacher_reflection) as teacher_reflection,
            MAX(r.student_feedback) as student_feedback
        ');

        $this->db->from('students s'); 
        $this->db->join('users u', 'u.id = s.user_id'); 
        
        // Join ke tabel refleksi
        $this->db->join('pbl_reflections r', 'r.user_id = u.id AND r.class_id = s.class_id', 'left');

        $this->db->where('s.class_id', $class_id);
        
        // Grouping per siswa untuk mencegah duplikasi data
        $this->db->group_by('u.id'); 
        
        // Untuk amannya, tambahkan kolom yang dependent ke group by (opsional di modern mysql jika PK sudah ada, tapi bagus untuk kompatibilitas)
        // $this->db->group_by(['u.name', 'u.image']); 

        return $this->db->get()->result();
    }

  public function save_reflection($data)
  {
    // Cek apakah data sudah ada
  	$exists = $this->db->get_where('pbl_reflections', [
  		'class_id' => $data['class_id'],
  		'user_id'  => $data['user_id']
  	])->row();

  	if ($exists) {
        // Update jika sudah ada
  		$this->db->where('id', $exists->id);
  		return $this->db->update('pbl_reflections', [
  			'teacher_reflection' => $data['teacher_reflection'],
  			'student_feedback'   => $data['student_feedback']
  		]);
  	} else {
      // Insert baru jika belum ada
  		if (empty($data['id'])) {
  			$data['id'] = generate_ulid(); 
  		}

  		return $this->db->insert('pbl_reflections', $data);
  	}
  }
}

/* End of file Refleksi_model.php */
/* Location: ./application/models/Refleksi_model.php */