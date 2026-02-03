<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Docxgenerator_model extends CI_Model {

    public function search_regencies($q, $limit = 20)
    {
        $q = trim($q);
        if (mb_strlen($q) < 2) return [];

        return $this->db
        ->select('r.id, r.name AS regency_name, p.id AS province_id, p.name AS province_name')
        ->from('reg_regencies r')
        ->join('reg_provinces p', 'p.id = r.province_id', 'left')
        ->like('r.name', $q)
        ->order_by('r.name', 'ASC')
        ->limit((int)$limit)
        ->get()
        ->result_array();
    }

    public function get_regency_with_province($regency_id)
    {
        return $this->db
        ->select('r.id, r.name AS regency_name, p.id AS province_id, p.name AS province_name')
        ->from('reg_regencies r')
        ->join('reg_provinces p', 'p.id = r.province_id', 'left')
        ->where('r.id', $regency_id)
        ->get()
        ->row_array();
    }

    public function search_ppk($keyword = null, $limit = 20)
    {
        $this->db->where('role', 'ppk');

        if ($keyword) {
            $this->db->like('nama', $keyword);
        }

        $query = $this->db
        ->order_by('nama', 'ASC')
        ->limit((int)$limit)
        ->get('pegawai')
        ->result();

        $result = [];
        foreach ($query as $row) {
            $result[] = [
                'id'   => $row->id,
                'text' => $row->nama,
                'nip'  => $row->nip
            ];
        }

        return $result;
    }

    public function get_ppk_by_id($id)
    {
        return $this->db
        ->where('id', (int)$id)
        ->where('role', 'ppk')
        ->get('pegawai')
        ->row_array();
    }

    public function get_kepala_default()
    {
        return $this->db
        ->where('role', 'kepala')
        ->limit(1)
        ->get('pegawai')
        ->row();
    }


    public function get_all_anggaran()
    {
        return $this->db
        ->select('id, kode_akun, nama_kegiatan, pagu')
        ->order_by('kode_akun', 'ASC')
        ->get('anggaran')
        ->result();
    }

    public function get_anggaran_by_id($id)
    {
        return $this->db
        ->where('id', $id)
        ->get('anggaran')
        ->row();
    }

    public function save_document_data($data)
    {
        $this->db->insert('generated_documents', $data);
        return $this->db->insert_id();  // Mengembalikan ID dari insert yang baru
    }




}


