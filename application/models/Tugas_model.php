<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Tugas_model extends CI_Model
{
    // Ambil semua tugas dari guru
    public function get_all_tugas()
    {
        return $this->db->get('tugas')->result();
    }

    // Ambil detail tugas
    public function get_tugas($id)
    {
        return $this->db->get_where('tugas', ['id' => $id])->row();
    }

    // Simpan tugas yang diunggah siswa
    public function insert_pengumpulan($data)
    {
        return $this->db->insert('pengumpulan_tugas', $data);
    }

    // Cek apakah siswa sudah mengumpulkan tugas ini
    public function cek_pengumpulan($tugas_id, $siswa_id)
    {
        return $this->db->get_where('pengumpulan_tugas', [
            'tugas_id' => $tugas_id,
            'siswa_id' => $siswa_id
        ])->row();
    }
    // Ambil semua pengumpulan tugas siswa (join ke tabel tugas)
    public function get_all_pengumpulan()
    {
        $this->db->select('p.*, t.judul, t.deadline');
        $this->db->from('pengumpulan_tugas p');
        $this->db->join('tugas t', 'p.tugas_id = t.id', 'left');
        $this->db->order_by('p.uploaded_at', 'DESC');
        return $this->db->get()->result();
    }
}
