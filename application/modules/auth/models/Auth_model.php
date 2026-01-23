<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_model extends CI_Model {

    public function get_user_by_nip($nip)
    {
        return $this->db
        ->where('nip', $nip)
        ->get('pegawai')
        ->row();
    }
}
