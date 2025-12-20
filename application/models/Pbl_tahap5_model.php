<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pbl_tahap5_model extends CI_Model
{
	// Definisikan tabel untuk Tahap 5
	private $table_reflections = 'pbl_final_reflections';
	private $table_closing_tts = 'pbl_closing_tts';

	/* ===== REFLEKSI AKHIR FUNCTIONS ===== */
	public function get_reflections($class_id)
	{
		return $this->db->where('class_id', $class_id)
			->order_by('created_at', 'DESC')
			->get($this->table_reflections)
			->result();
	}

	public function get_reflection($id)
	{
		return $this->db->where('id', $id)->get($this->table_reflections)->row();
	}

	public function insert_reflection($data)
	{
		return $this->db->insert($this->table_reflections, $data);
	}

	public function update_reflection($id, $data)
	{
		return $this->db->where('id', $id)->update($this->table_reflections, $data);
	}

	public function delete_reflection($id)
	{
		return $this->db->where('id', $id)->delete($this->table_reflections);
	}

	/* ===== TTS PENUTUP FUNCTIONS ===== */
	public function get_closing_tts_list($class_id)
	{
		return $this->db->where('class_id', $class_id)
			->order_by('created_at', 'DESC')
			->get($this->table_closing_tts)
			->result();
	}

	public function get_closing_tts($id)
	{
		return $this->db->where('id', $id)->get($this->table_closing_tts)->row();
	}

	public function insert_closing_tts($data)
	{
		return $this->db->insert($this->table_closing_tts, $data);
	}

	public function update_closing_tts($id, $data)
	{
		return $this->db->where('id', $id)->update($this->table_closing_tts, $data);
	}

	public function delete_closing_tts($id)
	{
		return $this->db->where('id', $id)->delete($this->table_closing_tts);
	}
}

/* End of file Pbl_tahap5_model.php */
/* Location: ./application/models/Pbl_tahap5_model.php */