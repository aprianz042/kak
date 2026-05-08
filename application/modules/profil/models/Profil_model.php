<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profil_model extends CI_Model {

    protected $table = 'pegawai';

    public function get_by_id($id)
    {
        return $this->db
        ->where('id', $id)
        ->get($this->table)
        ->row();
    }

    public function update($id, $data)
    {
        return $this->db
        ->where('id', $id)
        ->update($this->table, $data);
    }

}
