<?php
class Komentar_model extends CI_Model
{
    private $table = 'komentar';

    public function get_by_id($id)
    {
        return $this->db->get_where($this->table, ['id' => $id])->row();
    }

    public function get_by_forum($forum_id)
    {
        return $this->db->select('komentar.*, user.name as nama_user')
        ->from('komentar')
        ->join('user', 'user.id = komentar.user_id', 'left')
        ->where('forum_id', $forum_id)
        ->order_by('komentar.tanggal', 'ASC')
        ->get()->result();
    }

    public function insert($data)
    {
        return $this->db->insert($this->table, $data);
    }

    public function delete($id)
    {
        return $this->db->delete($this->table, ['id' => $id]);
    }
}
