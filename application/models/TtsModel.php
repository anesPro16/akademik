<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TtsModel extends CI_Model {

    // === CRUD untuk tabel TTS ===
    public function get_all()
    {
        return $this->db->order_by('id', 'DESC')->get('tts')->result();
    }

    public function get($id)
    {
        return $this->db->get_where('tts', ['id' => $id])->row();
    }

    public function insert($data)
    {
        return $this->db->insert('tts', $data);
    }

    public function delete($id)
    {
        return $this->db->delete('tts', ['id' => $id]);
    }

    public function find($id)
    {
        return $this->db->get_where('tts', ['id' => $id])->row();
    }

    public function get_questions($tts_id)
    {
        return $this->db->where('tts_id', $tts_id)
        ->order_by('arah', 'ASC')
        ->order_by('nomor', 'ASC')
        ->get('tts_questions')->result();
    }

    public function insert_question($data)
    {
        return $this->db->insert('tts_questions', $data);
    }

    public function delete_question($id)
    {
        return $this->db->delete('tts_questions', ['id' => $id]);
    }

    // Auto nomor pertanyaan
    public function get_next_number($tts_id, $arah)
    {
        $this->db->select_max('nomor');
        $this->db->where(['tts_id' => $tts_id, 'arah' => $arah]);
        $row = $this->db->get('tts_questions')->row();
        return $row ? $row->nomor + 1 : 1;
    }

}
