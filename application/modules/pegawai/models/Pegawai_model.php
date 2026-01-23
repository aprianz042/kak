<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pegawai_model extends CI_Model {

    protected $table = 'pegawai';

    public function get_all()
    {
        return $this->db
        ->order_by('created_at', 'DESC')
        ->get($this->table)
        ->result();
    }

    public function get_by_id($id)
    {
        return $this->db
        ->where('id', $id)
        ->get($this->table)
        ->row();
    }

    public function insert($data)
    {
        return $this->db->insert($this->table, $data);
    }

    public function update($id, $data)
    {
        return $this->db
        ->where('id', $id)
        ->update($this->table, $data);
    }

    public function delete($id)
    {
        return $this->db
        ->where('id', $id)
        ->delete($this->table);
    }

    public function cek_nip($nip, $exclude_id = null)
    {
        $this->db->where('nip', $nip);
        if ($exclude_id) {
            $this->db->where('id !=', $exclude_id);
        }
        return $this->db->get($this->table)->row();
    }

    public function cek_email($email)
    {
        return $this->db->where('email', $email)->get('pegawai')->row();
    }

    public function cek_email_edit($email, $id)
    {
        return $this->db->where('email', $email)
        ->where('id !=', $id)
        ->get('pegawai')
        ->row();
    }

}
