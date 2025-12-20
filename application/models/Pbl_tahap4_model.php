<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pbl_tahap4_model extends CI_Model {

	// Definisikan tabel untuk Tahap 4
	private $table_solution_essays = 'pbl_solution_essays';
	private $table_evaluation_quizzes = 'pbl_evaluation_quizzes';

	/* SOLUTION ESSAY FUNCTIONS */
	public function get_solution_essays($class_id)
	{
		return $this->db->where('class_id', $class_id)
			->order_by('created_at', 'DESC')
			->get($this->table_solution_essays)
			->result();
	}

	public function get_solution_essay($id)
	{
		return $this->db->where('id', $id)->get($this->table_solution_essays)->row();
	}

	public function insert_solution_essay($data)
	{
		return $this->db->insert($this->table_solution_essays, $data);
	}

	public function update_solution_essay($id, $data)
	{
		return $this->db->where('id', $id)->update($this->table_solution_essays, $data);
	}

	public function delete_solution_essay($id)
	{
		return $this->db->where('id', $id)->delete($this->table_solution_essays);
	}

	/* EVALUATION QUIZ FUNCTIONS */
	public function get_evaluation_quizzes($class_id)
	{
		return $this->db->where('class_id', $class_id)
			->order_by('created_at', 'DESC')
			->get($this->table_evaluation_quizzes)
			->result();
	}

	public function get_evaluation_quiz($id)
	{
		return $this->db->where('id', $id)->get($this->table_evaluation_quizzes)->row();
	}

	public function insert_evaluation_quiz($data)
	{
		return $this->db->insert($this->table_evaluation_quizzes, $data);
	}

	public function update_evaluation_quiz($id, $data)
	{
		return $this->db->where('id', $id)->update($this->table_evaluation_quizzes, $data);
	}

	public function delete_evaluation_quiz($id)
	{
		return $this->db->where('id', $id)->delete($this->table_evaluation_quizzes);
	}

}

/* End of file Pbl_tahap4_model.php */
/* Location: ./application/models/Pbl_tahap4_model.php */