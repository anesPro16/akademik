<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pbl_forum_model extends CI_Model
{
	private $table_topics = 'pbl_discussion_topics';
	private $table_posts = 'pbl_forum_posts';
	private $table_users = 'users'; // Asumsi tabel users

	/**
	 * Mengambil detail topik diskusi utama
	 */
	public function get_topic_details($topic_id)
	{
		return $this->db->where('id', $topic_id)
			->get($this->table_topics)
			->row();
	}

	/**
	 * Mengambil semua postingan untuk satu topik,
	 * di-join dengan nama pengguna
	 */
	public function get_posts($topic_id)
	{
		$this->db->select('p.*, u.name'); // Asumsi kolom 'name'
		$this->db->from($this->table_posts . ' as p');
		$this->db->join($this->table_users . ' as u', 'p.user_id = u.id');
		$this->db->where('p.topic_id', $topic_id);
		$this->db->order_by('p.created_at', 'ASC'); // Tampilkan yang terlama dulu
		return $this->db->get()->result();
	}

	/**
	 * Menyimpan postingan baru
	 */
	public function insert_post($data)
	{
		return $this->db->insert($this->table_posts, $data);
	}

	/**
	 * Memperbarui postingan (hanya jika user_id cocok)
	 */
	public function update_post($post_id, $user_id, $data)
	{
		$this->db->where('id', $post_id);
		$this->db->where('user_id', $user_id); // Keamanan: Hanya pemilik yang bisa edit
		return $this->db->update($this->table_posts, $data);
	}

	/**
	 * Menghapus postingan
	 * (Kita biarkan Guru bisa hapus semua, atau hanya pemilik)
	 */
	public function delete_post($post_id)
	{
		// (Implementasi sederhana: guru bisa hapus semua)
		return $this->db->where('id', $post_id)->delete($this->table_posts);
	}

    /**
     * (Opsional) Cek pemilik postingan sebelum update/delete
     */
    public function get_post_owner($post_id)
    {
        return $this->db->select('user_id')
            ->where('id', $post_id)
            ->get($this->table_posts)
            ->row();
    }
}

/* End of file Pbl_forum_model.php */
/* Location: ./application/models/Pbl_forum_model.php */