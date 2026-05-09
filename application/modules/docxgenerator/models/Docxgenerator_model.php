<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Docxgenerator_model extends CI_Model {

    protected $table = 'generated_documents';

    /* =========================
     * GET ALL DOCUMENTS
     * ========================= */
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

/*    public function get_doc_by_id($id)
    {
        return $this->db
        ->where('id', $id)
        ->order_by('created_at', 'DESC')
        ->get($this->table)
        ->result();
    }

*/    
    public function get_doc_by_id($id)
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
        ->where('generated_documents.id', $id)
        ->order_by('generated_documents.created_at', 'DESC')
        ->get()
        ->result();
    }

    public function get_doc_user($id)
    {
        return $this->db
        ->select('
            generated_documents.*,
            pegawai.nama as nama_ppk,
            pegawai.nip as nip_ppk
            ')            
        ->from('generated_documents')
        ->join('pegawai', 'generated_documents.ppk_id = pegawai.id', 'left')
        ->where('generated_documents.id_creator', $id)
        ->order_by('generated_documents.created_at', 'DESC')
        ->get()
        ->result();
    }

    public function get_doc_ppk($id)
    {
        return $this->db
        ->select('
            generated_documents.*,
            creator.nama as nama_creator
            ')
        ->from('generated_documents')
            ->join('pegawai creator', 'creator.id = generated_documents.id_creator', 'left') // sesuaikan ID / NIP
            ->where('generated_documents.ppk_id', $id)
            ->where('generated_documents.status !=', 'draft')
            ->order_by('generated_documents.created_at', 'DESC')
            ->get()
            ->result();
        }


    /* =========================
     * GET BY ID
     * ========================= */
    public function get_by_id($id)
    {
        return $this->db
        ->where('id', $id)
        ->get($this->table)
        ->row();
    }

    /* =========================
     * DELETE DOCUMENT
     * ========================= */
    public function delete($id)
    {
        return $this->db
        ->where('id', $id)
        ->delete($this->table);
    }

    /* =========================
     * OPTIONAL: CHECK FILE EXIST
     * ========================= */
    public function get_files($id)
    {
        return $this->db
        ->select('file_docx, file_pdf')
        ->where('id', $id)
        ->get($this->table)
        ->row();
    }


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

    public function getByFile($id)
    {
        return $this->db
        ->where('id', $id)
        ->get('generated_documents')
        ->row();
    }

    public function getPenerimaByDokumen($id_dokumen)
    {
        return $this->db
        ->select('p.nip')
        ->from('generated_documents gd')
        ->join('pegawai p', 'p.id = gd.ppk_id')
        ->where('gd.id', $id_dokumen)
        ->get()
        ->row();
    }



    public function updateStatus($id_dokumen, $status)
    {
        return $this->db
        ->where('id', $id_dokumen)
        ->update('generated_documents', [
            'status' => $status,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }

    /*public function insertLog($data)
    {
        return $this->db->insert('log_dokumen', [
            'id_dokumen' => $data['id_dokumen'],
            'pengirim'   => $data['pengirim'],
            'penerima'   => $data['penerima'],
            'status'     => $data['status'],
            'pesan'      => $data['pesan']
        ]);
    }
*/
    public function insertLog($data)
    {
        $this->db->trans_start();

    // insert log
        $this->db->insert('log_dokumen', [
            'id_dokumen' => $data['id_dokumen'],
            'pengirim'   => $data['pengirim'],
            'penerima'   => $data['penerima'],
            'status'     => $data['status'],
            'pesan'      => $data['pesan']
        ]);

    // update status dokumen
        $this->db->where('id', $data['id_dokumen']);
        $this->db->update('generated_documents', [
            'status' => $data['status']
        ]);

        $this->db->trans_complete();

        return $this->db->trans_status();
    }

    public function get_logs_dokumen($id)
    {
        return $this->db
        ->select('log_dokumen.*, generated_documents.file_doc')
        ->from('log_dokumen')
        ->join('generated_documents', 'generated_documents.id = log_dokumen.id_dokumen', 'left')
        ->where('log_dokumen.id_dokumen', $id)
        ->order_by('log_dokumen.created_at', 'ASC')
        ->get()
        ->result();
    }

    public function getPengirimTerakhir($id_dokumen)
    {
        return $this->db
        ->where('id_dokumen', $id_dokumen)
        ->order_by('created_at', 'DESC')
        ->limit(1)
        ->get('log_dokumen')
        ->row();
    }

    public function update_document($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('generated_documents', $data); // sesuaikan nama tabel
    }

    public function get_document_by_id($id)
    {
        return $this->db->get_where('generated_documents', ['id' => $id])->row();
    }

    public function get_ttd_user($nip)
    {
        $query = $this->db
        ->select('ttd')
        ->where('nip', $nip)
        ->limit(1)
        ->get('pegawai')
        ->row();

        return $query ? $query->ttd : null;
    }
}


