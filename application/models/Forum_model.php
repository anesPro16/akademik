<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Forum_model extends CI_Model {

  private $table = 'forum';

  public function get_all_by_guru($guru_id) {
    return $this->db->where('dibuat_oleh', $guru_id)
    ->order_by('tanggal', 'DESC')
    ->get($this->table)
    ->result();
  }

  public function get_all_forum($keyword = null)
  {
    $this->db->select('forum.*, user.name as nama_guru');
    $this->db->from('forum');
    $this->db->join('user', 'user.id = forum.dibuat_oleh', 'left');

    if (!empty($keyword)) {
      $this->db->like('forum.judul', $keyword);
      $this->db->or_like('user.name', $keyword);
    }

    $this->db->order_by('forum.tanggal', 'DESC');
    return $this->db->get()->result();
  }



  public function get_by_id($id) {
    return $this->db->select('forum.*, user.name AS nama_guru')
    ->from('forum')
    ->join('user', 'user.id = forum.dibuat_oleh', 'left')
    ->where('forum.id', $id)
    ->get()
    ->row();
  }

  public function insert($data) {
    $this->db->insert($this->table, $data);
    return $this->db->insert_id();
  }

  public function update($id, $data) {
    $this->db->where('id', $id)->update($this->table, $data);
  }

  public function delete($id) {
    $this->db->delete($this->table, ['id' => $id]);
  }
}
