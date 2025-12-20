<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pbl_refleksi_akhir_model extends CI_Model
{
	private $table_reflections = 'pbl_final_reflections';
	private $table_prompts = 'pbl_reflection_prompts';
	private $table_submissions = 'pbl_reflection_submissions';
	private $table_users = 'users';

	/**
	 * Mengambil detail aktivitas refleksi utama
	 */
	public function get_reflection_details($reflection_id)
	{
		return $this->db->where('id', $reflection_id)
			->get($this->table_reflections)
			->row();
	}

	/**
	 * Mengambil semua prompt/pertanyaan untuk satu refleksi
	 */
	public function get_prompts($reflection_id)
	{
		return $this->db->where('reflection_id', $reflection_id)
			->order_by('created_at', 'ASC')
			->get($this->table_prompts)
			->result();
	}

	/**
	 * Menyimpan prompt baru
	 */
	public function insert_prompt($data)
	{
		return $this->db->insert($this->table_prompts, $data);
	}

	/**
	 * Memperbarui prompt
	 */
	public function update_prompt($id, $data)
	{
		return $this->db->where('id', $id)->update($this->table_prompts, $data);
	}

	/**
	 * Menghapus prompt
	 */
	public function delete_prompt($id)
	{
		return $this->db->where('id', $id)->delete($this->table_prompts);
	}

	/**
	 * Mengambil DAFTAR SEMUA siswa yang sudah mengumpulkan refleksi (GURU)
	 * Mengembalikan BANYAK baris data.
	 */
	public function get_submissions($reflection_id)
	{
		$this->db->select('s.*, u.name as student_name'); // Asumsi kolom 'name' di users
		$this->db->from($this->table_submissions . ' as s');
		$this->db->join($this->table_users . ' as u', 's.user_id = u.id');
		$this->db->where('s.reflection_id', $reflection_id);
		$this->db->order_by('s.updated_at', 'DESC');
		return $this->db->get()->result();
	}

	public function get_submission_detail($submission_id)
	{
		return $this->db->where('id', $submission_id)
			->get($this->table_submissions)
			->row();
	}

	// Cek apakah siswa sudah mengirim refleksi
  public function get_submission($reflection_id, $user_id)
  {
    return $this->db->where('reflection_id', $reflection_id)
      ->where('user_id', $user_id)
      ->get($this->table_submissions)
      ->row();
  }

      // Simpan jawaban (Insert atau Update)
    public function save_submission($data)
    {
        // Cek eksistensi
        $existing = $this->get_submission($data['reflection_id'], $data['user_id']);

        if ($existing) {
            $this->db->where('id', $existing->id);
            $this->db->update($this->table_submissions, [
                'submission_content' => $data['submission_content']
                // updated_at otomatis via DB trigger atau set manual
            ]);
            return 'updated';
        } else {
            $this->db->insert($this->table_submissions, $data);
            return 'inserted';
        }
    }
}

/* End of file Pbl_refleksi_akhir_model.php */
/* Location: ./application/models/Pbl_refleksi_akhir_model.php */