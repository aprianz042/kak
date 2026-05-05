<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Anggaran_model extends CI_Model
{
    protected $table = 'anggaran';

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    // =============================
    // GET SEMUA DATA
    // =============================
    public function get_all()
    {
        return $this->db
            ->order_by('id', 'ASC')
            ->get($this->table)
            ->result();
    }

    // =============================
    // GET DATA BY ID
    // =============================
    public function get_by_id($id)
    {
        return $this->db
            ->where('id', $id)
            ->get($this->table)
            ->row();
    }

    // =============================
    // INSERT DATA
    // =============================
    public function insert_data($data)
    {
        return $this->db->insert($this->table, $data);
    }

    // =============================
    // UPDATE DATA
    // =============================
    public function update_data($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }

    // =============================
    // DELETE DATA
    // =============================
    public function delete_data($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete($this->table);
    }
}