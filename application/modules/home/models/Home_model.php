<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home_model extends CI_Model
{
    private $table = 'generated_documents';

    public function count_draft()
    {
        return $this->db->where('status', 'draft')
                        ->count_all_results($this->table);
    }

    public function count_disetujui()
    {
        return $this->db->where('status', 'disetujui')
                        ->count_all_results($this->table);
    }

    public function count_ditolak()
    {
        return $this->db->where('status', 'ditolak')
                        ->count_all_results($this->table);
    }

    public function count_lainnya()
    {
        return $this->db->where_not_in('status', ['draft', 'disetujui', 'ditolak'])
                        ->count_all_results($this->table);
    }

    public function count_by_ppk()
    {
        $this->db->select('
            g.ppk_id,
            p.nama AS nama_ppk,
            SUM(CASE WHEN g.status = "draft" THEN 1 ELSE 0 END) AS total_draft,
            SUM(CASE WHEN g.status NOT IN ("draft", "disetujui", "ditolak") THEN 1 ELSE 0 END) AS total_lainnya,
            SUM(CASE WHEN g.status = "disetujui" THEN 1 ELSE 0 END) AS total_disetujui,
            SUM(CASE WHEN g.status = "ditolak" THEN 1 ELSE 0 END) AS total_ditolak
        ');
        $this->db->from('generated_documents g');
        $this->db->join('pegawai p', 'p.id = g.ppk_id', 'left');
        $this->db->group_by('g.ppk_id');

        return $this->db->get()->result();
    }
    public function get_realisasi()
    {
        $this->db->select('
            a.id,
            a.kode_akun,
            a.nama_kegiatan,
            a.pagu,
            COALESCE(SUM(CASE WHEN g.status = \'disetujui\' THEN g.biaya ELSE 0 END), 0) AS realisasi,
            ROUND((COALESCE(SUM(CASE WHEN g.status = \'disetujui\' THEN g.biaya ELSE 0 END), 0) / a.pagu) * 100, 2) AS persentase
        ');
        $this->db->from('anggaran a');
        $this->db->join('generated_documents g', 'g.kode_anggaran = a.kode_akun', 'left');
        $this->db->group_by('a.id');

        return $this->db->get()->result();
    }
}