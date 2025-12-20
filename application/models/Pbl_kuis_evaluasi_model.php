<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pbl_kuis_evaluasi_model extends CI_Model
{
	private $table_quizzes = 'pbl_evaluation_quizzes';
	private $table_questions = 'pbl_evaluation_quiz_questions';

	/**
	 * Mengambil detail kuis evaluasi utama
	 */
	public function get_quiz_details($quiz_id)
	{
		return $this->db->where('id', $quiz_id)
			->get($this->table_quizzes)
			->row();
	}

	/**
	 * Mengambil semua pertanyaan untuk satu kuis
	 */
	public function get_questions($quiz_id)
	{
		return $this->db->where('quiz_id', $quiz_id)
			->order_by('created_at', 'ASC')
			->get($this->table_questions)
			->result();
	}

	/**
	 * Menyimpan pertanyaan baru
	 */
	public function insert_question($data)
	{
		return $this->db->insert($this->table_questions, $data);
	}

	/**
	 * Memperbarui pertanyaan
	 */
	public function update_question($id, $data)
	{
		return $this->db->where('id', $id)->update($this->table_questions, $data);
	}

	/**
	 * Menghapus pertanyaan
	 */
	public function delete_question($id)
	{
		return $this->db->where('id', $id)->delete($this->table_questions);
	}
}

/* End of file Pbl_kuis_evaluasi_model.php */
/* Location: ./application/models/Pbl_kuis_evaluasi_model.php */