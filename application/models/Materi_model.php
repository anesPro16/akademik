<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Materi_model extends CI_Model
{
    protected $table = 'materi';

    public function insert($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update($id, $data)
    {
        return $this->db->where('id', $id)->update($this->table, $data);
    }

    public function delete($id)
    {
        return $this->db->where('id', $id)->delete($this->table);
    }

    public function get($id)
    {
        return $this->db->get_where($this->table, ['id' => $id])->row();
    }

    public function all()
    {
        return $this->db->order_by('created_at', 'DESC')->get($this->table)->result();
    }

    public function by_guru($guru_id)
    {
        return $this->db->get_where($this->table, ['created_by' => $guru_id])->result();
    }
}
