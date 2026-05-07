<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;

require_once APPPATH . 'third_party/dompdf/autoload.inc.php';

class Docxgenerator extends Authenticated_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->model('Docxgenerator_model');
    }

    // ================================================================
    // INDEX
    // ================================================================

    public function index()
    {
        $id_user = $this->session->userdata('user_id');
        $role    = $this->session->userdata('role');

        $data['title']    = 'DOCX Generator';
        $data['kepala']   = $this->Docxgenerator_model->get_kepala_default();
        $data['anggaran'] = $this->Docxgenerator_model->get_all_anggaran();

        if ($role === 'operator') {
            $data['documents'] = $this->Docxgenerator_model->get_doc_user($id_user);
            $data['content']   = $this->load->view('docxgenerator_view_op', $data, TRUE);
        } elseif ($role === 'ppk') {
            $data['documents'] = $this->Docxgenerator_model->get_doc_ppk($id_user);
            $data['content']   = $this->load->view('docxgenerator_view_ppk', $data, TRUE);
        } else {
            redirect('home');
        }

        $this->load->view('layouts/main', $data);
    }

    // ================================================================
    // GENERATE — hanya simpan ke DB, TIDAK generate file
    // ================================================================

    public function generate()
    {
        $this->cek_role(['operator', 'ppk']);

        // ----- Ambil input POST -----
        $unit_organisasi = $this->input->post('unit_organisasi', true);
        $program         = $this->input->post('program', true);
        $kegiatan        = $this->input->post('kegiatan', true);
        $kro             = $this->input->post('kro', true);
        $ro              = $this->input->post('ro', true);
        $komponen        = $this->input->post('komponen', true);
        $kode_anggaran   = $this->input->post('kode_anggaran', true);
        $akun_anggaran   = $this->input->post('akun_anggaran', true);
        $tahun_anggaran  = $this->input->post('tahun_anggaran', true);
        $dasar_hukum     = $this->input->post('dasar_hukum', true);
        $gambaran_umum   = $this->input->post('gambaran_umum', true);
        $maksud_tujuan   = $this->input->post('maksud_tujuan', true);
        $keluaran        = $this->input->post('keluaran', true);
        $nama_kegiatan   = $this->input->post('nama_kegiatan', true);
        $waktu           = $this->input->post('waktu', true);
        $tanggal_bayar   = $this->input->post('tanggal_bayar', true);
        $lokasi          = $this->input->post('lokasi', true);
        $vol             = $this->input->post('vol', true);
        $satuan          = $this->input->post('satuan', true);
        $biaya           = $this->input->post('biaya', true);
        $kepala          = $this->input->post('kepala', true);
        $nip_kepala      = $this->input->post('nip_kepala', true);

        // ----- Validasi wilayah -----
        $regency_id = $this->input->post('regency_id', true);
        $wil = $this->Docxgenerator_model->get_regency_with_province($regency_id);
        if (!$wil) {
            show_error('Kab/Kota tidak valid atau tidak ditemukan di database wilayah.');
        }
        $kota_kegiatan = $wil['regency_name'];
        $provinsi      = $wil['province_name'];

        // ----- Validasi PPK -----
        $ppk_id = $this->input->post('ppk', true);
        $ppk    = $this->Docxgenerator_model->get_ppk_by_id($ppk_id);
        if (!$ppk) {
            show_error('PPK tidak valid atau tidak ditemukan');
        }

        $baseName = 'dokumen_' . time();

        // ----- Simpan ke DB saja, belum generate file -----
        $this->_insert_document([
            'unit_organisasi' => $unit_organisasi,
            'program'         => $program,
            'kegiatan'        => $kegiatan,
            'kro'             => $kro,
            'ro'              => $ro,
            'komponen'        => $komponen,
            'kode_anggaran'   => $kode_anggaran,
            'akun_anggaran'   => $akun_anggaran,
            'kota_kegiatan'   => $kota_kegiatan,
            'provinsi'        => $provinsi,
            'tahun_anggaran'  => $tahun_anggaran,
            'dasar_hukum'     => $dasar_hukum,
            'gambaran_umum'   => $gambaran_umum,
            'maksud_tujuan'   => $maksud_tujuan,
            'keluaran'        => $keluaran,
            'nama_kegiatan'   => $nama_kegiatan,
            'waktu'           => $waktu,
            'tanggal_bayar'   => $tanggal_bayar,
            'lokasi'          => $lokasi,
            'vol'             => $vol,
            'satuan'          => $satuan,
            'biaya'           => $biaya,
            'ppk_id'          => $ppk_id,
            'kepala'          => $kepala,
            'nip_kepala'      => $nip_kepala,
            'file_doc'        => $baseName,
            'id_creator'      => $this->session->userdata('user_id'),
        ]);

        $this->session->set_flashdata('success', 'Dokumen berhasil disimpan dan menunggu persetujuan PPK');
        redirect('docxgenerator');
    }

    // ================================================================
    // REVISI — update DB + regenerate file
    // ================================================================

    public function revisi()
    {
        $this->cek_role(['operator']);

        $id_dokumen = $this->input->post('id_dokumen', true);

        $doc = $this->Docxgenerator_model->get_document_by_id($id_dokumen);
        if (!$doc) {
            show_error('Dokumen tidak ditemukan');
        }

        $baseName = $doc->file_doc;

        $unit_organisasi = $this->input->post('unit_organisasi', true);
        $program         = $this->input->post('program', true);
        $kegiatan        = $this->input->post('kegiatan', true);
        $kro             = $this->input->post('kro', true);
        $ro              = $this->input->post('ro', true);
        $komponen        = $this->input->post('komponen', true);
        $kode_anggaran   = $this->input->post('kode_anggaran', true);
        $akun_anggaran   = $this->input->post('akun_anggaran', true);
        $tahun_anggaran  = $this->input->post('tahun_anggaran', true);
        $dasar_hukum     = $this->input->post('dasar_hukum', true);
        $gambaran_umum   = $this->input->post('gambaran_umum', true);
        $maksud_tujuan   = $this->input->post('maksud_tujuan', true);
        $keluaran        = $this->input->post('keluaran', true);
        $nama_kegiatan   = $this->input->post('nama_kegiatan', true);
        $waktu           = $this->input->post('waktu', true);
        $tanggal_bayar   = $this->input->post('tanggal_bayar', true);
        $lokasi          = $this->input->post('lokasi', true);
        $vol             = $this->input->post('vol', true);
        $satuan          = $this->input->post('satuan', true);
        $biaya           = $this->input->post('biaya', true);

        // Wilayah
        $regency_id = $this->input->post('regency_id', true);
        if ($regency_id && is_numeric($regency_id)) {
            $wil = $this->Docxgenerator_model->get_regency_with_province($regency_id);
            if (!$wil) {
                show_error('Kab/Kota tidak valid atau tidak ditemukan di database wilayah.');
            }
            $kota_kegiatan = $wil['regency_name'];
            $provinsi      = $wil['province_name'];
        } else {
            $kota_kegiatan = $doc->kota_kegiatan;
            $provinsi      = $doc->provinsi;
        }

        // PPK & Kepala dari data lama (disabled di form)
        $ppk = $this->Docxgenerator_model->get_ppk_by_id($doc->ppk_id);
        if (!$ppk) {
            show_error('PPK tidak valid atau tidak ditemukan');
        }
        $kepala     = $doc->kepala;
        $nip_kepala = $doc->nip_kepala;

        // Update DB
        $this->Docxgenerator_model->update_document($id_dokumen, [
            'unit_organisasi' => $unit_organisasi,
            'program'         => $program,
            'kegiatan'        => $kegiatan,
            'kro'             => $kro,
            'ro'              => $ro,
            'komponen'        => $komponen,
            'kode_anggaran'   => $kode_anggaran,
            'akun_anggaran'   => $akun_anggaran,
            'kota_kegiatan'   => $kota_kegiatan,
            'provinsi'        => $provinsi,
            'tahun_anggaran'  => $tahun_anggaran,
            'dasar_hukum'     => $dasar_hukum,
            'gambaran_umum'   => $gambaran_umum,
            'maksud_tujuan'   => $maksud_tujuan,
            'keluaran'        => $keluaran,
            'nama_kegiatan'   => $nama_kegiatan,
            'waktu'           => $waktu,
            'tanggal_bayar'   => $tanggal_bayar,
            'lokasi'          => $lokasi,
            'vol'             => $vol,
            'satuan'          => $satuan,
            'biaya'           => $biaya,
            'updated_at'      => date('Y-m-d H:i:s'),
            'status'          => 'ajuan_baru',
        ]);

        // Insert log
        $nip_login = $this->session->userdata('nip');
        $penerima  = $this->Docxgenerator_model->getPengirimTerakhir($id_dokumen);
        $this->Docxgenerator_model->insertLog([
            'id_dokumen' => $id_dokumen,
            'pengirim'   => $nip_login,
            'penerima'   => $penerima ? $penerima->pengirim : null,
            'status'     => 'ajuan_revisi',
            'pesan'      => 'Dokumen telah direvisi dan diajukan kembali'
        ]);

        // Hitung biaya untuk generate file
        $vol_num         = (float) preg_replace('/[^\d.]/', '', (string) $vol);
        $biaya_num       = (float) preg_replace('/[^\d.]/', '', (string) $biaya);
        $total_biaya     = $vol_num * $biaya_num;
        $terbilang_total = $this->terbilang_rupiah($total_biaya);

        setlocale(LC_TIME, 'id_ID.UTF-8');
        $tanggal_buat = strftime('%d %B %Y');

        // Timpa file lama
        $this->_generate_files([
            'baseName'        => $baseName,
            'unit_organisasi' => $unit_organisasi,
            'program'         => $program,
            'kegiatan'        => $kegiatan,
            'kro'             => $kro,
            'ro'              => $ro,
            'komponen'        => $komponen,
            'kode_anggaran'   => $kode_anggaran,
            'akun_anggaran'   => $akun_anggaran,
            'kota_kegiatan'   => $kota_kegiatan,
            'provinsi'        => $provinsi,
            'tahun_anggaran'  => $tahun_anggaran,
            'dasar_hukum'     => $dasar_hukum,
            'gambaran_umum'   => $gambaran_umum,
            'maksud_tujuan'   => $maksud_tujuan,
            'keluaran'        => $keluaran,
            'nama_kegiatan'   => $nama_kegiatan,
            'waktu'           => $waktu,
            'tanggal_bayar'   => $tanggal_bayar,
            'lokasi'          => $lokasi,
            'vol'             => $vol,
            'satuan'          => $satuan,
            'biaya'           => $biaya,
            'biaya_fmt'       => $this->format_angka_rupiah($biaya),
            'total_biaya'     => $total_biaya,
            'total_biaya_fmt' => $this->format_angka_rupiah($total_biaya),
            'terbilang_total' => $terbilang_total,
            'nama_ppk'        => $ppk['nama'],
            'nip_ppk'         => $ppk['nip'],
            'kepala'          => $kepala,
            'nip_kepala'      => $nip_kepala,
            'tanggal_buat'    => $tanggal_buat,
        ]);

        $this->session->set_flashdata('success', 'Dokumen berhasil direvisi');
        redirect('docxgenerator/timeline_dok/' . $id_dokumen);
    }

    // ================================================================
    // TINDAK LANJUT PPK — approve = generate DOCX & PDF
    // ================================================================

    public function tindak_lanjut_ppk()
    {
        $this->cek_role(['ppk']);
        header('Content-Type: application/json');

        $id_dokumen = (int) $this->input->post('id_dokumen');
        $aksi       = $this->input->post('aksi');
        $pesan      = $this->input->post('pesan');
        $nip_login  = $this->session->userdata('nip');

        if (!$id_dokumen || !$aksi) {
            echo json_encode(['status' => false, 'message' => 'Data tidak valid']);
            return;
        }

        $dokumen = $this->Docxgenerator_model->get_document_by_id($id_dokumen);
        if (!$dokumen) {
            echo json_encode(['status' => false, 'message' => 'Dokumen tidak ditemukan']);
            return;
        }

        $penerima = $this->Docxgenerator_model->getPengirimTerakhir($id_dokumen);

        $this->db->trans_begin();

        $this->Docxgenerator_model->updateStatus($id_dokumen, $aksi);
        $this->Docxgenerator_model->insertLog([
            'id_dokumen' => $id_dokumen,
            'pengirim'   => $nip_login,
            'penerima'   => $penerima ? $penerima->pengirim : null,
            'status'     => $aksi,
            'pesan'      => $pesan
        ]);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            echo json_encode(['status' => false, 'message' => 'Gagal tindak lanjut']);
            return;
        }

        $this->db->trans_commit();

        // ===== Generate file hanya jika PPK menyetujui =====
        if ($aksi === 'disetujui') {
            $ppk = $this->Docxgenerator_model->get_ppk_by_id($dokumen->ppk_id);
            if (!$ppk) {
                echo json_encode(['status' => false, 'message' => 'PPK tidak ditemukan, file tidak bisa digenerate']);
                return;
            }

            $vol_num         = (float) preg_replace('/[^\d.]/', '', (string) $dokumen->vol);
            $biaya_num       = (float) preg_replace('/[^\d.]/', '', (string) $dokumen->biaya);
            $total_biaya     = $vol_num * $biaya_num;
            $terbilang_total = $this->terbilang_rupiah($total_biaya);

            setlocale(LC_TIME, 'id_ID.UTF-8');
            $tanggal_buat = strftime('%d %B %Y');

            $fileNames = $this->_generate_files([
                'baseName'        => $dokumen->file_doc,
                'unit_organisasi' => $dokumen->unit_organisasi,
                'program'         => $dokumen->program,
                'kegiatan'        => $dokumen->kegiatan,
                'kro'             => $dokumen->kro,
                'ro'              => $dokumen->ro,
                'komponen'        => $dokumen->komponen,
                'kode_anggaran'   => $dokumen->kode_anggaran,
                'akun_anggaran'   => $dokumen->akun_anggaran,
                'kota_kegiatan'   => $dokumen->kota_kegiatan,
                'provinsi'        => $dokumen->provinsi,
                'tahun_anggaran'  => $dokumen->tahun_anggaran,
                'dasar_hukum'     => $dokumen->dasar_hukum,
                'gambaran_umum'   => $dokumen->gambaran_umum,
                'maksud_tujuan'   => $dokumen->maksud_tujuan,
                'keluaran'        => $dokumen->keluaran,
                'nama_kegiatan'   => $dokumen->nama_kegiatan,
                'waktu'           => $dokumen->waktu,
                'tanggal_bayar'   => $dokumen->tanggal_bayar,
                'lokasi'          => $dokumen->lokasi,
                'vol'             => $dokumen->vol,
                'satuan'          => $dokumen->satuan,
                'biaya'           => $dokumen->biaya,
                'biaya_fmt'       => $this->format_angka_rupiah($dokumen->biaya),
                'total_biaya'     => $total_biaya,
                'total_biaya_fmt' => $this->format_angka_rupiah($total_biaya),
                'terbilang_total' => $terbilang_total,
                'nama_ppk'        => $ppk['nama'],
                'nip_ppk'         => $ppk['nip'],
                'kepala'          => $dokumen->kepala,
                'nip_kepala'      => $dokumen->nip_kepala,
                'tanggal_buat'    => $tanggal_buat,
            ]);

            echo json_encode([
                'status'     => true,
                'message'    => 'Dokumen disetujui dan file berhasil digenerate',
                'file_docx'  => $fileNames['docx'],
                'file_pdf'   => $fileNames['pdf'],
            ]);
            return;
        }

        echo json_encode(['status' => true, 'message' => 'Berhasil']);
    }

    // ================================================================
    // PRIVATE: Insert dokumen ke DB
    // ================================================================

    private function _insert_document(array $data)
    {
        return $this->Docxgenerator_model->save_document_data([
            'unit_organisasi' => $data['unit_organisasi'],
            'program'         => $data['program'],
            'kegiatan'        => $data['kegiatan'],
            'kro'             => $data['kro'],
            'ro'              => $data['ro'],
            'komponen'        => $data['komponen'],
            'kode_anggaran'   => $data['kode_anggaran'],
            'akun_anggaran'   => $data['akun_anggaran'],
            'kota_kegiatan'   => $data['kota_kegiatan'],
            'provinsi'        => $data['provinsi'],
            'tahun_anggaran'  => $data['tahun_anggaran'],
            'dasar_hukum'     => $data['dasar_hukum'],
            'gambaran_umum'   => $data['gambaran_umum'],
            'maksud_tujuan'   => $data['maksud_tujuan'],
            'keluaran'        => $data['keluaran'],
            'nama_kegiatan'   => $data['nama_kegiatan'],
            'waktu'           => $data['waktu'],
            'tanggal_bayar'   => $data['tanggal_bayar'],
            'lokasi'          => $data['lokasi'],
            'vol'             => $data['vol'],
            'satuan'          => $data['satuan'],
            'biaya'           => $data['biaya'],
            'ppk_id'          => $data['ppk_id'],
            'kepala'          => $data['kepala'],
            'nip_kepala'      => $data['nip_kepala'],
            'file_doc'        => $data['file_doc'],
            'id_creator'      => $data['id_creator'],
            'created_at'      => date('Y-m-d H:i:s'),
            'status'          => 'draft',
        ]);
    }

    // ================================================================
    // PRIVATE: Generate DOCX + PDF, return nama file
    // ================================================================

    private function _generate_files(array $data)
    {
        $baseName = $data['baseName'];

        // Parse multiline string jadi array
        $parseLines = function ($str) {
            if (empty($str)) return [];
            return array_values(array_filter(
                array_map('trim', preg_split("/\r\n|\n|\r/", $str))
            ));
        };

        $dashum           = $parseLines($data['dasar_hukum']);
        $maksud_tujuanArr = $parseLines($data['maksud_tujuan']);
        $keluaranArr      = $parseLines($data['keluaran']);

        // ==================== DOCX ====================
        $templatePath = APPPATH . 'templates/template_kak.docx';
        if (!file_exists($templatePath)) {
            show_error('Template DOCX tidak ditemukan');
        }

        $template = new TemplateProcessor($templatePath);

        // Dasar Hukum
        $count_dh = count($dashum);
        if ($count_dh === 1) {
            $template->cloneBlock('DH1_BLOCK', 1, true, true);
            $template->cloneBlock('DASAR_HUKUM_BLOCK', 0, true, true);
        } elseif ($count_dh > 1) {
            $template->cloneBlock('DH1_BLOCK', 0, true, true);
            $template->cloneBlock('DASAR_HUKUM_BLOCK', $count_dh, true, true);
        } else {
            $template->cloneBlock('DH1_BLOCK', 0, true, true);
            $template->cloneBlock('DASAR_HUKUM_BLOCK', 0, true, true);
        }
        foreach ($dashum as $i => $item) {
            $template->setValue('item_dh#' . ($i + 1), $item);
        }

        // Maksud Tujuan
        $count_mt = count($maksud_tujuanArr);
        if ($count_mt === 1) {
            $template->cloneBlock('MT_SATU', 1, true, true);
            $template->cloneBlock('MAKSUD_TUJUAN_BLOCK', 0, true, true);
        } elseif ($count_mt > 1) {
            $template->cloneBlock('MT_SATU', 0, true, true);
            $template->cloneBlock('MAKSUD_TUJUAN_BLOCK', $count_mt, true, true);
        } else {
            $template->cloneBlock('MT_SATU', 0, true, true);
            $template->cloneBlock('MAKSUD_TUJUAN_BLOCK', 0, true, true);
        }
        foreach ($maksud_tujuanArr as $i => $item) {
            $template->setValue('item_mt#' . ($i + 1), $item);
        }

        // Keluaran
        $count_kl = count($keluaranArr);
        if ($count_kl === 1) {
            $template->cloneBlock('KELUARAN_SATU', 1, true, true);
            $template->cloneBlock('KELUARAN_BLOCK', 0, true, true);
        } elseif ($count_kl > 1) {
            $template->cloneBlock('KELUARAN_SATU', 0, true, true);
            $template->cloneBlock('KELUARAN_BLOCK', $count_kl, true, true);
        } else {
            $template->cloneBlock('KELUARAN_SATU', 0, true, true);
            $template->cloneBlock('KELUARAN_BLOCK', 0, true, true);
        }
        foreach ($keluaranArr as $i => $item) {
            $template->setValue('item_kl#' . ($i + 1), $item);
        }

        // Set semua nilai
        $template->setValue('unit_organisasi', $data['unit_organisasi']);
        $template->setValue('program',         $data['program']);
        $template->setValue('kegiatan',        $data['kegiatan']);
        $template->setValue('kro',             $data['kro']);
        $template->setValue('ro',              $data['ro']);
        $template->setValue('komponen',        $data['komponen']);
        $template->setValue('kode_anggaran',   $data['kode_anggaran']);
        $template->setValue('akun_anggaran',   $data['akun_anggaran']);
        $template->setValue('kota_kegiatan',   $data['kota_kegiatan']);
        $template->setValue('prov_kegiatan',   $data['provinsi']);
        $template->setValue('tahun_anggaran',  $data['tahun_anggaran']);
        $template->setValue('dasar_hukum',     $dashum[0] ?? '');
        $template->setValue('gambaran_umum',   $data['gambaran_umum'] ?: '-');
        $template->setValue('maksud_tujuan',   $maksud_tujuanArr[0] ?? '');
        $template->setValue('keluaran',        $keluaranArr[0] ?? '');
        $template->setValue('nama_kegiatan',   $data['nama_kegiatan']);
        $template->setValue('waktu',           $data['waktu']);
        $template->setValue('tanggal_bayar',   $data['tanggal_bayar']);
        $template->setValue('lokasi',          $data['lokasi']);
        $template->setValue('vol',             $data['vol']);
        $template->setValue('satuan',          $data['satuan']);
        $template->setValue('biaya',           $data['biaya_fmt']);
        $template->setValue('total_biaya',     $data['total_biaya_fmt']);
        $template->setValue('terbilang_total', $data['terbilang_total']);
        $template->setValue('tanggal_buat',    $data['tanggal_buat']);
        $template->setValue('nama_ppk',        $data['nama_ppk']);
        $template->setValue('nip_ppk',         $data['nip_ppk']);
        $template->setValue('nama_kepala',     $data['kepala']);
        $template->setValue('nip_kepala',      $data['nip_kepala']);

        // Simpan DOCX
        $outputDirDocx = FCPATH . 'storage/docx/';
        if (!is_dir($outputDirDocx)) {
            mkdir($outputDirDocx, 0777, true);
        }
        $docxName = $baseName . '.docx';
        $template->saveAs($outputDirDocx . $docxName);

        // ==================== PDF ====================
        $outputDirPdf = FCPATH . 'storage/pdf/';
        if (!is_dir($outputDirPdf)) {
            mkdir($outputDirPdf, 0777, true);
        }
        $pdfName = $baseName . '.pdf';
        $pdfPath = $outputDirPdf . $pdfName;

        $this->generate_pdf_dompdf([
            'unit_organisasi'  => $data['unit_organisasi'],
            'program'          => $data['program'],
            'kegiatan'         => $data['kegiatan'],
            'kro'              => $data['kro'],
            'ro'               => $data['ro'],
            'komponen'         => $data['komponen'],
            'kode_anggaran'    => $data['kode_anggaran'],
            'akun_anggaran'    => $data['akun_anggaran'],
            'kota_kegiatan'    => $data['kota_kegiatan'],
            'provinsi'         => $data['provinsi'],
            'tahun_anggaran'   => $data['tahun_anggaran'],
            'gambaran_umum'    => $data['gambaran_umum'],
            'nama_kegiatan'    => $data['nama_kegiatan'],
            'waktu'            => $data['waktu'],
            'tanggal_bayar'    => $data['tanggal_bayar'],
            'lokasi'           => $data['lokasi'],
            'vol'              => $data['vol'],
            'satuan'           => $data['satuan'],
            'biaya_fmt'        => $data['biaya_fmt'],
            'total_biaya_fmt'  => $data['total_biaya_fmt'],
            'terbilang_total'  => $data['terbilang_total'],
            'dashum'           => $dashum,
            'maksud_tujuanArr' => $maksud_tujuanArr,
            'keluaranArr'      => $keluaranArr,
            'nama_ppk'         => $data['nama_ppk'],
            'nip_ppk'          => $data['nip_ppk'],
            'nama_kepala'      => $data['kepala'],
            'nip_kepala'       => $data['nip_kepala'],
            'tanggal_buat'     => $data['tanggal_buat'],
        ], $pdfPath);

        return [
            'docx' => $docxName,
            'pdf'  => $pdfName,
        ];
    }

    // ================================================================
    // PRIVATE: Generate PDF dengan DomPDF
    // ================================================================

    private function generate_pdf_dompdf(array $pdfData, string $pdfPath): bool
    {
        try {
            $html = $this->load->view('pdf/template_kak_pdf', $pdfData, TRUE);

            $options = new \Dompdf\Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isRemoteEnabled', false);
            $options->set('defaultFont', 'Arial');

            $dompdf = new \Dompdf\Dompdf($options);
            $dompdf->loadHtml($html, 'UTF-8');
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            file_put_contents($pdfPath, $dompdf->output());
            return true;

        } catch (\Exception $e) {
            log_message('error', 'DomPDF generate failed: ' . $e->getMessage());
            return false;
        }
    }

    // ================================================================
    // METHODS LAINNYA (tidak berubah)
    // ================================================================

    public function timeline_dok($id)
    {
        $this->cek_role(['operator', 'ppk']);

        $data['id_dokumen'] = $id;
        $data['title']      = 'DOCX Generator';
        $data['timeline']   = $this->Docxgenerator_model->get_logs_dokumen($id);
        $data['kepala']     = $this->Docxgenerator_model->get_kepala_default();
        $data['documents']  = $this->Docxgenerator_model->get_doc_by_id($id);
        $data['anggaran']   = $this->Docxgenerator_model->get_all_anggaran();
        $data['content']    = $this->load->view('timeline', $data, TRUE);

        $this->load->view('layouts/main', $data);
    }

    public function download($file)
    {
        $this->cek_role(['operator', 'ppk', 'kepala']);
        $path = FCPATH . 'storage/docx/' . $file;

        if (!file_exists($path)) {
            show_404();
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Disposition: attachment; filename="' . $file . '"');
        header('Content-Length: ' . filesize($path));
        readfile($path);
        exit;
    }

    public function download_pdf($file)
    {
        $this->cek_role(['operator', 'ppk', 'kepala']);
        $path = FCPATH . 'storage/pdf/' . $file;

        if (!file_exists($path)) {
            show_404();
        }

        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $file . '"');
        header('Content-Length: ' . filesize($path));
        readfile($path);
        exit;
    }

    public function search_regencies()
    {
        $q    = $this->input->get('q', true);
        $rows = $this->Docxgenerator_model->search_regencies($q, 20);

        $out = [];
        foreach ($rows as $r) {
            $out[] = [
                'id'          => $r['id'],
                'text'        => $r['regency_name'],
                'province_id' => $r['province_id'],
                'province'    => $r['province_name'],
            ];
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($out));
    }

    public function search_ppk()
    {
        $q    = $this->input->get('q', true);
        $data = $this->Docxgenerator_model->search_ppk($q);

        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function delete()
    {
        $this->cek_role(['operator']);

        $id = (int) $this->input->post('id');
        if (!$id) {
            show_404();
        }

        $doc = $this->Docxgenerator_model->get_by_id($id);
        if (!$doc) {
            show_404();
        }

        $filePath = FCPATH . 'storage/docx/' . $doc->file_doc . '.docx';
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        $this->Docxgenerator_model->delete($id);

        $this->session->set_flashdata('success', 'Dokumen berhasil dihapus');
        redirect('docxgenerator');
    }

    public function pengajuan($id_doc)
    {
        $this->cek_role(['operator', 'ppk']);

        $nip     = $this->session->userdata('nip');
        $dokumen = $this->Docxgenerator_model->getByFile($id_doc);

        if (!$dokumen) {
            show_error('Dokumen tidak ditemukan');
        }

        if ($dokumen->status !== 'draft') {
            show_error('Dokumen tidak dapat diajukan');
        }

        $penerima = $this->Docxgenerator_model->getPenerimaByDokumen($dokumen->id);

        $this->db->trans_begin();

        $this->Docxgenerator_model->updateStatus($dokumen->id, 'ajuan_baru');
        $this->Docxgenerator_model->insertLog([
            'id_dokumen' => $dokumen->id,
            'pengirim'   => $nip,
            'penerima'   => $penerima->nip,
            'status'     => 'ajuan_baru',
            'pesan'      => 'Dokumen diajukan untuk direview'
        ]);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('danger', 'Gagal pengajuan dokumen');
        } else {
            $this->db->trans_commit();
            $this->session->set_flashdata('success', 'Dokumen berhasil diajukan');
        }

        redirect('docxgenerator');
    }

    public function tindak_lanjut()
    {
        $this->cek_role(['operator', 'ppk']);

        $id_dokumen = (int) $this->input->post('id_dokumen');
        $aksi       = $this->input->post('aksi');
        $pesan      = $this->input->post('pesan');
        $nip_login  = $this->session->userdata('nip');

        if (!$id_dokumen || !$aksi) {
            echo json_encode(['status' => false, 'message' => 'Data tidak valid']);
            return;
        }

        $dokumen = $this->Docxgenerator_model->get_by_id($id_dokumen);
        if (!$dokumen) {
            echo json_encode(['status' => false, 'message' => 'Dokumen tidak ditemukan']);
            return;
        }

        $penerima = $this->Docxgenerator_model->getPengirimTerakhir($id_dokumen);

        $this->db->trans_begin();

        $this->Docxgenerator_model->updateStatus($id_dokumen, $aksi);
        $this->Docxgenerator_model->insertLog([
            'id_dokumen' => $id_dokumen,
            'pengirim'   => $nip_login,
            'penerima'   => $penerima ? $penerima->pengirim : null,
            'status'     => $aksi,
            'pesan'      => $pesan
        ]);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            echo json_encode(['status' => false, 'message' => 'Gagal tindak lanjut']);
        } else {
            $this->db->trans_commit();
            echo json_encode(['status' => true, 'message' => 'Berhasil']);
        }
    }

    public function get_log($id)
    {
        $data = $this->Docxgenerator_model->get_logs_dokumen($id);

        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function view_docx($filename)
    {
        $this->cek_role(['operator', 'ppk']);

        $filename = basename($filename) . '.docx';
        $token    = $this->input->get('token');

        if (!$this->isValidToken($filename, $token)) {
            show_error('Unauthorized', 401);
        }

        $path = FCPATH . 'storage/docx/' . $filename;
        if (!file_exists($path)) {
            show_404();
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Disposition: inline; filename="' . $filename . '"');
        header('Content-Length: ' . filesize($path));
        readfile($path);
        exit;
    }

    private function isValidToken($filename, $token)
    {
        if (!$token) return false;
        $secret   = 'KUNCI_RAHASIA_APP';
        $expected = hash_hmac('sha256', $filename, $secret);
        return hash_equals($expected, $token);
    }

    public function debug_html_pdf($id_dokumen)
    {
        $this->cek_role(['operator', 'ppk']);

        $doc = $this->Docxgenerator_model->get_document_by_id($id_dokumen);
        if (!$doc) {
            show_error('Dokumen dengan ID ' . $id_dokumen . ' tidak ditemukan.');
        }

        $ppk = $this->Docxgenerator_model->get_ppk_by_id($doc->ppk_id);
        if (!$ppk) {
            show_error('PPK tidak ditemukan untuk dokumen ini.');
        }

        $parseLines = function ($str) {
            if (empty($str)) return [];
            return array_values(array_filter(
                array_map('trim', preg_split("/\r\n|\n|\r/", $str))
            ));
        };

        $vol_num         = (float) preg_replace('/[^\d.]/', '', (string) $doc->vol);
        $biaya_num       = (float) preg_replace('/[^\d.]/', '', (string) $doc->biaya);
        $total_biaya     = $vol_num * $biaya_num;
        $terbilang_total = $this->terbilang_rupiah($total_biaya);

        $this->load->view('pdf/template_kak_pdf', [
            'unit_organisasi'  => $doc->unit_organisasi,
            'program'          => $doc->program,
            'kegiatan'         => $doc->kegiatan,
            'kro'              => $doc->kro,
            'ro'               => $doc->ro,
            'komponen'         => $doc->komponen,
            'kode_anggaran'    => $doc->kode_anggaran,
            'akun_anggaran'    => $doc->akun_anggaran,
            'kota_kegiatan'    => $doc->kota_kegiatan,
            'provinsi'         => $doc->provinsi,
            'tahun_anggaran'   => $doc->tahun_anggaran,
            'gambaran_umum'    => $doc->gambaran_umum,
            'nama_kegiatan'    => $doc->nama_kegiatan,
            'waktu'            => $doc->waktu,
            'tanggal_bayar'    => $doc->tanggal_bayar,
            'lokasi'           => $doc->lokasi,
            'vol'              => $doc->vol,
            'satuan'           => $doc->satuan,
            'biaya_fmt'        => $this->format_angka_rupiah($doc->biaya),
            'total_biaya_fmt'  => $this->format_angka_rupiah($total_biaya),
            'terbilang_total'  => $terbilang_total,
            'dashum'           => $parseLines($doc->dasar_hukum),
            'maksud_tujuanArr' => $parseLines($doc->maksud_tujuan),
            'keluaranArr'      => $parseLines($doc->keluaran),
            'nama_ppk'         => $ppk['nama'],
            'nip_ppk'          => $ppk['nip'],
            'nama_kepala'      => $doc->kepala,
            'nip_kepala'       => $doc->nip_kepala,
            'tanggal_buat'     => date('d F Y', strtotime($doc->created_at)),
        ]);
    }

    // ================================================================
    // HELPERS
    // ================================================================

    public function terbilang($angka)
    {
        $angka    = abs((int) $angka);
        $bilangan = ['', 'satu', 'dua', 'tiga', 'empat', 'lima', 'enam', 'tujuh', 'delapan', 'sembilan', 'sepuluh', 'sebelas'];

        if ($angka < 12)              return ' ' . $bilangan[$angka];
        elseif ($angka < 20)          return $this->terbilang($angka - 10) . ' belas';
        elseif ($angka < 100)         return $this->terbilang(intval($angka / 10)) . ' puluh' . $this->terbilang($angka % 10);
        elseif ($angka < 200)         return ' seratus' . $this->terbilang($angka - 100);
        elseif ($angka < 1000)        return $this->terbilang(intval($angka / 100)) . ' ratus' . $this->terbilang($angka % 100);
        elseif ($angka < 2000)        return ' seribu' . $this->terbilang($angka - 1000);
        elseif ($angka < 1000000)     return $this->terbilang(intval($angka / 1000)) . ' ribu' . $this->terbilang($angka % 1000);
        elseif ($angka < 1000000000)  return $this->terbilang(intval($angka / 1000000)) . ' juta' . $this->terbilang($angka % 1000000);
        elseif ($angka < 1000000000000) return $this->terbilang(intval($angka / 1000000000)) . ' miliar' . $this->terbilang($angka % 1000000000);
        elseif ($angka < 1000000000000000) return $this->terbilang(intval($angka / 1000000000000)) . ' triliun' . $this->terbilang($angka % 1000000000000);
        else return ' angka terlalu besar';
    }

    public function terbilang_rupiah($angka)
    {
        if ($angka == 0) return 'Nol rupiah';
        return ucfirst(trim($this->terbilang($angka))) . ' rupiah';
    }

    public function format_angka_rupiah($angka)
    {
        if ($angka === null || $angka === '') return '0';
        $angka = preg_replace('/[^\d]/', '', (string) $angka);
        return number_format((float) $angka, 0, ',', '.');
    }
}