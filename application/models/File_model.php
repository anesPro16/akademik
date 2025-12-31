<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class File_model extends CI_Model {

  public function get_observasi_file($filename)
  {
    return $this->db
      ->select('u.id, u.user_id, u.observation_slot_id, s.class_id')
      ->from('pbl_observation_uploads u')
      ->join('pbl_observation_slots s', 's.id = u.observation_slot_id')
      ->where('u.file_name', $filename)
      ->get()
      ->row();
  }
}


/* End of file File_model.php */
/* Location: ./application/models/File_model.php */