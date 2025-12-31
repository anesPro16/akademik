<?php
class Exam_model extends CI_Model
{
  private $table = 'exams';

  public function get_by_class($class_id)
  {
    return $this->db
      ->where('class_id', $class_id)
      ->order_by('created_at', 'DESC')
      ->get($this->table)
      ->result();
  }

  public function get_by_id($id)
  {
    return $this->db
      ->where('exam_id', $id)
      ->get($this->table)
      ->row();
  }

  public function insert($data)
  {
    return $this->db->insert($this->table, $data);
  }

  public function update($id, $data)
  {
    return $this->db
      ->where('exam_id', $id)
      ->update($this->table, $data);
  }

  public function delete($id)
  {
    return $this->db
      ->where('exam_id', $id)
      ->delete($this->table);
  }

 	public function getTeacherId($user_id)
{
    $query = $this->db
        ->select('id')
        ->from('teachers')
        ->where('user_id', $user_id)
        ->get();

    // Cek apakah data ditemukan
    if ($query->num_rows() > 0) {
        return $query->row()->id; // Mengambil value 'id' dari baris pertama
    }

    return null; // Atau return false jika data tidak ditemukan
}

  /* =========================
   * VALIDASI KELAS GURU
   * ========================= */
  public function is_teacher_class($class_id, $teacher_id)
	{
	    return $this->db
	        ->select('classes.id')
	        ->from('classes')
	        // 1. JOIN tabel classes dan teachers berdasarkan ID Guru
	        ->join('teachers', 'teachers.id = classes.teacher_id') 
	        
	        // 2. Cek apakah ID kelas sesuai
	        ->where('classes.id', $class_id)
	        
	        // 3. Cek apakah ID di tabel teachers cocok dengan ID yang dioper
	        // Kita TIDAK menggunakan teachers.user_id, tapi teachers.id
	        ->where('teachers.id', $teacher_id) 
	        
	        ->get()
	        ->num_rows() > 0;
	}

	public function get_exam_with_class($exam_id)
	{
	    return $this->db
	        ->select('exams.*, classes.id as class_id')
	        ->from('exams')
	        ->join('classes', 'classes.id = exams.class_id')
	        ->where('exams.exam_id', $exam_id)
	        ->get()->row();
	}

	/* ===== QUESTIONS ===== */

	public function get_questions($exam_id)
	{
	    return $this->db
	        ->where('exam_id', $exam_id)
	        ->order_by('created_at', 'ASC')
	        ->get('exam_questions')
	        ->result();
	}

	public function insert_question($data)
	{
	    return $this->db->insert('exam_questions', $data);
	}

	public function update_question($id, $data)
	{
	    return $this->db->where('id', $id)->update('exam_questions', $data);
	}

	public function delete_question($id)
	{
	    return $this->db->where('id', $id)->delete('exam_questions');
	}

	public function insert_batch_questions($data)
	{
	    return $this->db->insert_batch('exam_questions', $data);
	}

	// Tambahkan di dalam class Exam_model

	public function get_active_exams_by_class($class_id)
	{
	    $now = date('Y-m-d H:i:s');
	    
	    return $this->db
	        ->where('class_id', $class_id)
	        ->where('is_active', 1) 
	        ->where('start_time <=', $now) // Hanya tampil jika waktu mulai sudah lewat/sekarang
	        ->where('end_time >=', $now)   // Dan waktu selesai belum lewat
	        ->order_by('end_time', 'ASC')  // Yang mau habis duluan ditaruh atas
	        ->get('exams')
	        ->result();
	}


}


/* End of file Exam_model.php */
/* Location: ./application/models/Exam_model.php */