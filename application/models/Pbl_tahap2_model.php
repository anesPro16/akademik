<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pbl_tahap2_model extends CI_Model
{
	private $table_quizzes = 'pbl_quizzes';
	private $table_quiz_questions = 'pbl_quiz_questions';
	private $tts_table = 'pbl_tts';

    // === QUIZ ===
	public function get_quizzes($class_id)
	{
		$this->db->where('class_id', $class_id);
		$this->db->order_by('created_at', 'DESC');
		return $this->db->get($this->table_quizzes)->result();
	}

	public function insert_quiz($data) { return $this->db->insert($this->table_quizzes, $data); }
	public function update_quiz($id, $data) { return $this->db->where('id', $id)->update($this->table_quizzes, $data); }
	public function delete_quiz($id) { return $this->db->where('id', $id)->delete($this->table_quizzes); }

	// --- [FUNGSI BARU] ---
	public function get_quiz_by_id($quiz_id)
	{
		return $this->db->where('id', $quiz_id)->get($this->table_quizzes)->row();
	}

	public function get_questions_by_id($id)
	{
		return $this->db->where('id', $id)->get($this->table_quiz_questions)->row();
	}

	// --- [FUNGSI BARU] ---
	public function get_questions_by_quiz_id($quiz_id)
	{
		return $this->db->where('quiz_id', $quiz_id)
			->order_by('created_at', 'DESC')
			->get($this->table_quiz_questions)
			->result();
	}

	// --- [FUNGSI BARU] ---
	public function insert_quiz_question($data)
	{
		return $this->db->insert($this->table_quiz_questions, $data);
	}

	// --- [FUNGSI BARU] ---
	public function update_quiz_question($id, $data)
	{
		return $this->db->where('id', $id)->update($this->table_quiz_questions, $data);
	}

	// --- [FUNGSI BARU] ---
	public function delete_quiz_question($id)
	{
		return $this->db->where('id', $id)->delete($this->table_quiz_questions);
	}

	/**
	 * Memasukkan beberapa pertanyaan sekaligus ke database.
	 * Digunakan untuk fitur import.
	 * @param array $data Array yang berisi data pertanyaan
	 * @return bool
	 */
	public function insert_quiz_question_batch($data)
	{
		if (empty($data)) {
			return false;
		}
		return $this->db->insert_batch($this->table_quiz_questions, $data);
	}

    // === TTS ===
	public function get_tts($class_id)
	{
		$this->db->where('class_id', $class_id);
		$this->db->order_by('created_at', 'DESC');
		return $this->db->get($this->tts_table)->result();
	}

	public function insert_tts($data) { return $this->db->insert($this->tts_table, $data); }
	public function update_tts($id, $data) { return $this->db->where('id', $id)->update($this->tts_table, $data); }
	public function delete_tts($id) { return $this->db->where('id', $id)->delete($this->tts_table); }
}


/* End of file Pbl_tahap2_model.php */
/* Location: ./application/models/Pbl_tahap2_model.php */