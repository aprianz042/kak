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
}
