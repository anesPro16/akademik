<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Murid_model extends CI_Model {

	protected $table = 'students';

  /**
   * Menyimpan data siswa baru (profil)
   */
  public function insert($payload)
  {
      return $this->db->insert($this->table, $payload);
  }

  /**
   * Menghapus data siswa (profil) berdasarkan user_id
   */
  public function delete_by_user_id($user_id)
  {
      return $this->db->delete($this->table, ['user_id' => $user_id]);
  }
    
  public function get($id){ return $this->db->where('id',$id)->get($this->table)->row(); }
  public function get_all_with_user_and_class(){
    $this->db->select('s.*, u.username, u.name as user_name, c.name as class_name');
    $this->db->from('students s');
    $this->db->join('users u','u.id = s.user_id','left');
    $this->db->join('classes c','c.id = s.class_id','left');
    return $this->db->get()->result();
  }

  public function get_sekolah_by_guru($user_id)
  {
    $this->db->select('s.id, s.name, s.code'); // Menggunakan 'name' sesuai skema tabel classes
    $this->db->from('classes as s');
    $this->db->join('students as t', 's.id = t.class_id');
    $this->db->where('t.user_id', $user_id);
    
    return $this->db->get()->result();
  }

  public function get_class_details($class_id)
  {
      // Ambil info kelas
    $class = $this->db->get_where('classes', [
      'id' => $class_id, 
    ])->row();

    if (!$class) return null;

      // Hitung jumlah siswa di kelas
    $this->db->where('class_id', $class_id);
    $class->student_count = $this->db->count_all_results('students');

    return $class;
  }

}

/* End of file Murid_model.php */
/* Location: ./application/models/Murid_model.php */