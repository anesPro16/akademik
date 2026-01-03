<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Refleksi_model extends CI_Model {

	public function __construct() {
		parent::__construct();
	}

  /**
   * 1. Ambil daftar Nama Ujian (Mata Pelajaran) yang unik di kelas ini
   * Digunakan untuk membuat Header Tabel (IPA, MTK, IPS, dst)
   */
  public function getExamSubjects($class_id)
  {
    $this->db->select('exam_name');
    $this->db->distinct();
    $this->db->from('exams');
    $this->db->where('class_id', $class_id);
    $this->db->where('is_active', 0);
    $this->db->order_by('exam_name', 'ASC');
    return $this->db->get()->result();
  }

  /**
   * Mengambil rekap nilai siswa + data refleksi jika ada
   * UPDATE: Menggunakan tabel 'students' sebagai penghubung
   */
  public function getAllStudentScores($class_id)
    {
        $this->db->select("
            u.id as user_id, 
            u.name as student_name, 
            u.image,
            
            -- Data Refleksi (Tahap 5)
            MAX(r.id) as reflection_id,
            MAX(r.teacher_reflection) as teacher_reflection,
            MAX(r.student_feedback) as student_feedback,

            -- 1. DATA UJIAN (UTS/UAS)
            -- Format: Matematika::UTS::85||Matematika::UAS::90
            (
                SELECT GROUP_CONCAT(CONCAT(e.exam_name, '::', e.type, '::', ea.score) SEPARATOR '||')
                FROM exam_attempts ea
                JOIN exams e ON e.exam_id = ea.exam_id
                WHERE ea.user_id = u.id 
                AND e.class_id = '$class_id'
                AND ea.status = 'finished' 
            ) as exam_data,

            -- 2. DATA KUIS (PBL)
            -- Join ke tabel pbl_quizzes untuk ambil kolom 'description'
            -- Format: Matematika::80||IPA::90
            (
                SELECT GROUP_CONCAT(CONCAT(pq.description, '::', pqr.score) SEPARATOR '||')
                FROM pbl_quiz_results pqr
                JOIN pbl_quizzes pq ON pq.id = pqr.quiz_id
                WHERE pqr.user_id = u.id 
                AND pq.class_id = '$class_id'
            ) as quiz_data,

            -- 3. DATA OBSERVASI (PBL)
            -- Join ke tabel pbl_observation_slots untuk ambil kolom 'description'
            (
                SELECT GROUP_CONCAT(CONCAT(pos.description, '::', por.score) SEPARATOR '||')
                FROM pbl_observation_results por
                JOIN pbl_observation_slots pos ON pos.id = por.observation_slot_id
                WHERE por.user_id = u.id 
                AND pos.class_id = '$class_id'
            ) as obs_data,

            -- 4. DATA ESAI (PBL)
            -- Join ke tabel pbl_solution_essays untuk ambil kolom 'description'
            (
                SELECT GROUP_CONCAT(CONCAT(pse.description, '::', pes.grade) SEPARATOR '||')
                FROM pbl_essay_submissions pes
                JOIN pbl_solution_essays pse ON pse.id = pes.essay_id
                WHERE pes.user_id = u.id 
                AND pse.class_id = '$class_id'
                AND pes.grade IS NOT NULL
            ) as essay_data
        ");

        $this->db->from('students s'); 
        $this->db->join('users u', 'u.id = s.user_id'); 
        $this->db->join('pbl_reflections r', 'r.user_id = u.id AND r.class_id = s.class_id', 'left');
        $this->db->where('s.class_id', $class_id);
        $this->db->group_by('u.id'); 

        return $this->db->get()->result();
    }

    public function get_student_score_data($user_id, $class_id)
    {
      $this->db->select("
        u.id as user_id, 
        u.name as student_name,
        
        -- Data Refleksi Guru untuk Siswa ini
        MAX(r.teacher_reflection) as teacher_reflection,
        MAX(r.student_feedback) as student_feedback,

        -- 1. DATA UJIAN (UTS/UAS)
        -- Format: Matematika::UTS::85||Matematika::UAS::90
        (
            SELECT GROUP_CONCAT(CONCAT(e.exam_name, '::', e.type, '::', ea.score) SEPARATOR '||')
            FROM exam_attempts ea
            JOIN exams e ON e.exam_id = ea.exam_id
            WHERE ea.user_id = u.id 
            AND e.class_id = '$class_id'
            AND ea.status = 'finished' 
        ) as exam_data,

        -- 2. DATA KUIS (PBL) - Ambil Subjects
        -- Format: Matematika::80||IPA::90
        (
            SELECT GROUP_CONCAT(CONCAT(pq.description, '::', pqr.score) SEPARATOR '||')
            FROM pbl_quiz_results pqr
            JOIN pbl_quizzes pq ON pq.id = pqr.quiz_id
            WHERE pqr.user_id = u.id 
            AND pq.class_id = '$class_id'
        ) as quiz_data,

        -- 3. DATA OBSERVASI (PBL)
        (
            SELECT GROUP_CONCAT(CONCAT(pos.description, '::', por.score) SEPARATOR '||')
            FROM pbl_observation_results por
            JOIN pbl_observation_slots pos ON pos.id = por.observation_slot_id
            WHERE por.user_id = u.id 
            AND pos.class_id = '$class_id'
        ) as obs_data,

        -- 4. DATA ESAI (PBL)
        (
            SELECT GROUP_CONCAT(CONCAT(pse.description, '::', pes.grade) SEPARATOR '||')
            FROM pbl_essay_submissions pes
            JOIN pbl_solution_essays pse ON pse.id = pes.essay_id
            WHERE pes.user_id = u.id 
            AND pse.class_id = '$class_id'
            AND pes.grade IS NOT NULL
        ) as essay_data
    ");

    $this->db->from('users u'); 
    $this->db->join('students s', 's.user_id = u.id');
    $this->db->join('pbl_reflections r', 'r.user_id = u.id AND r.class_id = s.class_id', 'left');
    
    $this->db->where('u.id', $user_id);
    $this->db->where('s.class_id', $class_id);
    $this->db->group_by('u.id'); 

    return $this->db->get()->row(); // Return 1 Row Object
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