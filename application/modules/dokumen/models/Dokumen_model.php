<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dokumen_model extends CI_Model {

    protected $table = 'generated_documents';

    public function get_all()
    {
        return $this->db
        ->select('
                 generated_documents.*,
                 pegawai.nama as nama_ppk,
                 pegawai.nip as nip_ppk,
                 anggaran.kode_akun,
                 anggaran.nama_kegiatan as nama_anggaran
                 ')
        ->from('generated_documents')
        ->join('pegawai', 'pegawai.id = generated_documents.ppk_id', 'left')
        ->join('anggaran', 'anggaran.kode_akun = generated_documents.kode_anggaran', 'left')
        ->order_by('generated_documents.created_at', 'DESC')
        ->get()
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
        return $this->db->where('email', $email)->get('dokumen')->row();
    }

    public function cek_email_edit($email, $id)
    {
        return $this->db->where('email', $email)
        ->where('id !=', $id)
        ->get('dokumen')
        ->row();
    }

}
