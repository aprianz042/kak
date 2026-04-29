<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;

// Include DomPDF autoloader
require_once APPPATH . 'third_party/dompdf/autoload.inc.php';

class Docxgenerator extends Authenticated_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->model('Docxgenerator_model');
    }


    public function index()
    {
        $id_user = $this->session->userdata('user_id');
        $nip = $this->session->userdata('nip');
        $role = $this->session->userdata('role');
        $data['title'] = 'DOCX Generator';
        $data['kepala'] = $this->Docxgenerator_model->get_kepala_default();
        $data['anggaran'] = $this->Docxgenerator_model->get_all_anggaran();


        if ($role == "operator") 
        {
            $data['documents'] = $this->Docxgenerator_model->get_doc_user($nip);            
            $data['content'] = $this->load->view('docxgenerator_view_op', $data, TRUE);
        }
        elseif ($role == "ppk")
        {
            $data['documents'] = $this->Docxgenerator_model->get_doc_ppk($id_user);
            $data['content'] = $this->load->view('docxgenerator_view_ppk', $data, TRUE);
        }
        elseif ($role == "kepala")
        {
            $data['content'] = $this->load->view('docxgenerator_view_ppk', $data, TRUE);
        }
        elseif ($role == "admin")
        {
            $data['documents'] = $this->Docxgenerator_model->get_all(); 
            $data['content'] = $this->load->view('docxgenerator_view_ppk', $data, TRUE);
        }
        else
        {
            $data['content'] = $this->load->view('docxgenerator_view_ppk', $data, TRUE);
        }

        $this->load->view('layouts/main', $data);
    }

    public function generate()
    {
        $baseName = 'dokumen_' . time();

        $unit_organisasi = $this->input->post('unit_organisasi', true);
        $program = $this->input->post('program', true);
        $kegiatan = $this->input->post('kegiatan', true);
        $kro = $this->input->post('kro', true);
        $ro = $this->input->post('ro', true);
        $komponen = $this->input->post('komponen', true);
        $kode_anggaran = $this->input->post('kode_anggaran', true);
        $akun_anggaran = $this->input->post('akun_anggaran', true);

        $regency_id = $this->input->post('regency_id', true);

        $wil = $this->Docxgenerator_model->get_regency_with_province($regency_id);
        if (!$wil) {
            show_error('Kab/Kota tidak valid atau tidak ditemukan di database wilayah.');
        }

        $kota_kegiatan = $wil['regency_name'];
        $provinsi = $wil['province_name'];

        $tahun_anggaran = $this->input->post('tahun_anggaran', true);
        $dasar_hukum = $this->input->post('dasar_hukum', true);
        $gambaran_umum = $this->input->post('gambaran_umum', true);
        $maksud_tujuan = $this->input->post('maksud_tujuan', true);
        $keluaran = $this->input->post('keluaran', true);
        $nama_kegiatan = $this->input->post('nama_kegiatan', true);
        $waktu = $this->input->post('waktu', true);
        $tanggal_bayar = $this->input->post('tanggal_bayar', true);
        $lokasi = $this->input->post('lokasi', true);
        $vol = $this->input->post('vol', true);
        $satuan = $this->input->post('satuan', true);
        $biaya = $this->input->post('biaya', true);

        $ppk_id = $this->input->post('ppk', true);
        $ppk = $this->Docxgenerator_model->get_ppk_by_id($ppk_id);

        if (!$ppk) {
            show_error('PPK tidak valid atau tidak ditemukan');
        }


        $kepala = $this->input->post('kepala', true);
        $nip_kepala = $this->input->post('nip_kepala', true);



        // format tanggal Indonesia
        setlocale(LC_TIME, 'id_ID.UTF-8');
        $tanggal_buat = strftime('%d %B %Y');

        $templatePath = APPPATH . 'templates/template_kak.docx';
        if (!file_exists($templatePath)) {
            show_error('Template DOCX tidak ditemukan');
        }

        $template = new TemplateProcessor($templatePath);

        // Proses untuk memasukkan data ke dalam database (sebelum pembuatan dokumen)
        $documentData = [
            'unit_organisasi' => $unit_organisasi,
            'program' => $program,
            'kegiatan' => $kegiatan,
            'kro' => $kro,
            'ro' => $ro,
            'komponen' => $komponen,
            'kode_anggaran' => $kode_anggaran,
            'akun_anggaran' => $akun_anggaran,
            'kota_kegiatan' => $kota_kegiatan,
            'provinsi' => $provinsi,
            'tahun_anggaran' => $tahun_anggaran,
            'dasar_hukum' => $dasar_hukum,
            'gambaran_umum' => $gambaran_umum,
            'maksud_tujuan' => $maksud_tujuan,
            'keluaran' => $keluaran,
            'nama_kegiatan' => $nama_kegiatan,
            'waktu' => $waktu,
            'tanggal_bayar' => $tanggal_bayar,
            'lokasi' => $lokasi,
            'vol' => $vol,
            'satuan' => $satuan,
            'biaya' => $biaya,
            'ppk_id' => $ppk_id,
            'kepala' => $kepala,
            'nip_kepala' => $nip_kepala,
            'created_at' => date('Y-m-d H:i:s'),
            'file_doc' => $baseName,
            'nip_creator' => $this->session->userdata('nip'),
            'status' => 'draft'
        ];

        // Simpan data dokumen ke dalam database
        $documentId = $this->Docxgenerator_model->save_document_data($documentData);

        // ===== DASAR HUKUM =====
        $dashum = [];
        
        if (!empty($dasar_hukum)) {
            $dashum = array_values(array_filter(
                array_map('trim', preg_split("/\r\n|\n|\r/", $dasar_hukum))
            ));
        }

        $count_dh = count($dashum);

        if ($count_dh === 1) 
        {
            $template->cloneBlock('DH1_BLOCK', 1, true, true);
            foreach ($dashum as $i => $item_dh) 
            {
                $template->setValue('item_dh#' . ($i + 1), $item_dh);
            }
            $template->cloneBlock('DASAR_HUKUM_BLOCK', 0, true, true);
        } 
        elseif ($count_dh > 1) 
        {
            $template->cloneBlock('DH1_BLOCK', 0, true, true);
            $template->cloneBlock('DASAR_HUKUM_BLOCK', $count_dh, true, true);
            foreach ($dashum as $i => $item_dh) 
            {
                $template->setValue('item_dh#' . ($i + 1), $item_dh);
            }
        } 
        else 
        {
            $template->cloneBlock('DH1_BLOCK', 0, true, true);
            $template->cloneBlock('DASAR_HUKUM_BLOCK', 0, true, true);
        }

        // ===== MAKSUD TUJUAN =====
        $maksud_tujuanArr = [];
        
        if (!empty($maksud_tujuan)) {
            $maksud_tujuanArr = array_values(array_filter(
                array_map('trim', preg_split("/\r\n|\n|\r/", $maksud_tujuan))
            ));
        }

        $count_mt = count($maksud_tujuanArr);

        if ($count_mt === 1) 
        {
            $template->cloneBlock('MT_SATU', 1, true, true);
            foreach ($maksud_tujuanArr as $i => $item_mt) 
            {
                $template->setValue('item_mt#' . ($i + 1), $item_mt);
            }
            $template->cloneBlock('MAKSUD_TUJUAN_BLOCK', 0, true, true);
        } 
        elseif ($count_mt > 1) 
        {
            $template->cloneBlock('MT_SATU', 0, true, true);
            $template->cloneBlock('MAKSUD_TUJUAN_BLOCK', $count_mt, true, true);
            foreach ($maksud_tujuanArr as $i => $item_mt) 
            {
                $template->setValue('item_mt#' . ($i + 1), $item_mt);
            }
        } 
        else 
        {
            $template->cloneBlock('MT_SATU', 0, true, true);
            $template->cloneBlock('MAKSUD_TUJUAN_BLOCK', 0, true, true);
        }



        // ===== KELUARAN =====
        $keluaranArr = [];
        
        if (!empty($keluaran)) {
            $keluaranArr = array_values(array_filter(
                array_map('trim', preg_split("/\r\n|\n|\r/", $keluaran))
            ));
        }

        $count_kl = count($keluaranArr);

        if ($count_kl === 1) 
        {
            $template->cloneBlock('KELUARAN_SATU', 1, true, true);
            foreach ($keluaranArr as $i => $item_kl) 
            {
                $template->setValue('item_kl#' . ($i + 1), $item_kl);
            }
            $template->cloneBlock('KELUARAN_BLOCK', 0, true, true);
        } 
        elseif ($count_kl > 1) 
        {
            $template->cloneBlock('KELUARAN_SATU', 0, true, true);
            $template->cloneBlock('KELUARAN_BLOCK', $count_kl, true, true);
            foreach ($keluaranArr as $i => $item_kl) 
            {
                $template->setValue('item_kl#' . ($i + 1), $item_kl);
            }
        } 
        else 
        {
            $template->cloneBlock('KELUARAN_SATU', 0, true, true);
            $template->cloneBlock('KELUARAN_BLOCK', 0, true, true);
        }

        $vol_num = (float) preg_replace('/[^\d.]/', '', (string)$vol);
        $biaya_num = (float) preg_replace('/[^\d.]/', '', (string)$biaya);
        $total_biaya = $vol_num * $biaya_num;
        $terbilang_total = $this->terbilang_rupiah($total_biaya);
        

        $template->setValue('unit_organisasi', $unit_organisasi);
        $template->setValue('program', $program);
        $template->setValue('kegiatan', $kegiatan);
        $template->setValue('kro', $kro);
        $template->setValue('ro', $ro);
        $template->setValue('komponen', $komponen);
        $template->setValue('kode_anggaran', $kode_anggaran);
        $template->setValue('akun_anggaran', $akun_anggaran);
        $template->setValue('kota_kegiatan', $kota_kegiatan);
        $template->setValue('prov_kegiatan', $provinsi);
        $template->setValue('tahun_anggaran', $tahun_anggaran);
        $template->setValue('dasar_hukum', $item_dh);
        $template->setValue('gambaran_umum', $gambaran_umum ?: '-');
        $template->setValue('maksud_tujuan', $item_mt);
        $template->setValue('keluaran', $item_kl);
        $template->setValue('nama_kegiatan', $nama_kegiatan);
        $template->setValue('waktu', $waktu);
        $template->setValue('tanggal_bayar', $tanggal_bayar);
        $template->setValue('lokasi', $lokasi);
        $template->setValue('vol', $vol);
        $template->setValue('satuan', $satuan);
        $template->setValue('biaya', $this->format_angka_rupiah($biaya));

        $template->setValue('total_biaya', $this->format_angka_rupiah($total_biaya));
        $template->setValue('terbilang_total', $terbilang_total);

        $template->setValue('tanggal_buat', $tanggal_buat);


        $template->setValue('nama_ppk', $ppk['nama']);
        $template->setValue('nip_ppk', $ppk['nip']);

        $template->setValue('nama_kepala', $kepala);
        $template->setValue('nip_kepala', $nip_kepala);


        // ===== SIMPAN DOCX =====
        $outputDirDocx = FCPATH . 'storage/docx/';
        if (!is_dir($outputDirDocx)) {
            mkdir($outputDirDocx, 0777, true);
        }

        $docxName = $baseName . '.docx';
        $docxPath = $outputDirDocx . $docxName;

        $template->saveAs($docxPath);

        // ===== KONVERSI KE PDF =====
        $outputDirPdf = FCPATH . 'storage/pdf/';
        if (!is_dir($outputDirPdf)) {
            mkdir($outputDirPdf, 0777, true);
        }

        $pdfName = $baseName . '.pdf';
        $pdfPath = $outputDirPdf . $pdfName;

        // SETTING DOMPDF RENDERER
        Settings::setPdfRendererName(Settings::PDF_RENDERER_DOMPDF);
        Settings::setPdfRendererPath(APPPATH . 'third_party/dompdf');  // <-- PATH SESUAI PILIHANMU

        try {
            $phpWord = IOFactory::load($docxPath);
            $writer  = IOFactory::createWriter($phpWord, 'PDF');
            $writer->save($pdfPath);
        } catch (Exception $e) {
            // fallback: cuma simpan DOCX kalau PDF gagal
            log_message('error', 'PDF convert failed: ' . $e->getMessage());
        }

        $this->session->set_flashdata('success', 'Dokumen berhasil dibuat');
        $this->session->set_flashdata('file_docx', $docxName);
        $this->session->set_flashdata('file_pdf', $pdfName);

        redirect('docxgenerator');
    }

    public function revisi()
    {
        $id_dokumen = $this->input->post('id_dokumen', true);

        // ambil data dokumen lama
        $doc = $this->Docxgenerator_model->get_document_by_id($id_dokumen);
        if (!$doc) {
            show_error('Dokumen tidak ditemukan');
        }

        $baseName = $doc->file_doc; // ✅ pakai nama file lama, bukan buat baru

        $unit_organisasi = $this->input->post('unit_organisasi', true);
        $program         = $this->input->post('program', true);
        $kegiatan        = $this->input->post('kegiatan', true);
        $kro             = $this->input->post('kro', true);
        $ro              = $this->input->post('ro', true);
        $komponen        = $this->input->post('komponen', true);
        $kode_anggaran   = $this->input->post('kode_anggaran', true);
        $akun_anggaran   = $this->input->post('akun_anggaran', true);

        $regency_id = $this->input->post('regency_id', true);

        // cek apakah yang dikirim ID angka atau string nama kota
        if ($regency_id && is_numeric($regency_id)) 
        {
            // ✅ ID valid → cari dari DB wilayah
            $wil = $this->Docxgenerator_model->get_regency_with_province($regency_id);
            if (!$wil) {
                show_error('Kab/Kota tidak valid atau tidak ditemukan di database wilayah.');
            }
            $kota_kegiatan = $wil['regency_name'];
            $provinsi      = $wil['province_name'];
        } 
        else 
        {
            // ✅ string nama kota atau kosong → pakai data lama dari DB
            $kota_kegiatan = $doc->kota_kegiatan;
            $provinsi      = $doc->provinsi;
        }


        $tahun_anggaran = $this->input->post('tahun_anggaran', true);
        $dasar_hukum    = $this->input->post('dasar_hukum', true);
        $gambaran_umum  = $this->input->post('gambaran_umum', true);
        $maksud_tujuan  = $this->input->post('maksud_tujuan', true);
        $keluaran       = $this->input->post('keluaran', true);
        $nama_kegiatan  = $this->input->post('nama_kegiatan', true);
        $waktu          = $this->input->post('waktu', true);
        $tanggal_bayar  = $this->input->post('tanggal_bayar', true);
        $lokasi         = $this->input->post('lokasi', true);
        $vol            = $this->input->post('vol', true);
        $satuan         = $this->input->post('satuan', true);
        $biaya          = $this->input->post('biaya', true);

        // PPK & Kepala ambil dari data lama (disabled di form, tidak ikut POST)
        $ppk = $this->Docxgenerator_model->get_ppk_by_id($doc->ppk_id);
        if (!$ppk) {
            show_error('PPK tidak valid atau tidak ditemukan');
        }

        $kepala     = $doc->kepala;
        $nip_kepala = $doc->nip_kepala;

        // format tanggal Indonesia
        setlocale(LC_TIME, 'id_ID.UTF-8');
        $tanggal_buat = strftime('%d %B %Y');

        $templatePath = APPPATH . 'templates/template_kak.docx';
        if (!file_exists($templatePath)) {
            show_error('Template DOCX tidak ditemukan');
        }

        $template = new TemplateProcessor($templatePath);
        
        // ✅ Update data dokumen di database
        $documentData = [
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
            'status'          => 'ajuan_baru', // ✅ setelah revisi kembali ke ajuan_baru
        ];

        $this->Docxgenerator_model->update_document($id_dokumen, $documentData);

        // ===== DASAR HUKUM =====
        $dashum = [];
        if (!empty($dasar_hukum)) {
            $dashum = array_values(array_filter(
                array_map('trim', preg_split("/\r\n|\n|\r/", $dasar_hukum))
            ));
        }
        $count_dh = count($dashum);

        if ($count_dh === 1) {
            $template->cloneBlock('DH1_BLOCK', 1, true, true);
            foreach ($dashum as $i => $item_dh) {
                $template->setValue('item_dh#' . ($i + 1), $item_dh);
            }
            $template->cloneBlock('DASAR_HUKUM_BLOCK', 0, true, true);
        } elseif ($count_dh > 1) {
            $template->cloneBlock('DH1_BLOCK', 0, true, true);
            $template->cloneBlock('DASAR_HUKUM_BLOCK', $count_dh, true, true);
            foreach ($dashum as $i => $item_dh) {
                $template->setValue('item_dh#' . ($i + 1), $item_dh);
            }
        } else {
            $template->cloneBlock('DH1_BLOCK', 0, true, true);
            $template->cloneBlock('DASAR_HUKUM_BLOCK', 0, true, true);
        }

        // ===== MAKSUD TUJUAN =====
        $maksud_tujuanArr = [];
        if (!empty($maksud_tujuan)) {
            $maksud_tujuanArr = array_values(array_filter(
                array_map('trim', preg_split("/\r\n|\n|\r/", $maksud_tujuan))
            ));
        }
        $count_mt = count($maksud_tujuanArr);

        if ($count_mt === 1) {
            $template->cloneBlock('MT_SATU', 1, true, true);
            foreach ($maksud_tujuanArr as $i => $item_mt) {
                $template->setValue('item_mt#' . ($i + 1), $item_mt);
            }
            $template->cloneBlock('MAKSUD_TUJUAN_BLOCK', 0, true, true);
        } elseif ($count_mt > 1) {
            $template->cloneBlock('MT_SATU', 0, true, true);
            $template->cloneBlock('MAKSUD_TUJUAN_BLOCK', $count_mt, true, true);
            foreach ($maksud_tujuanArr as $i => $item_mt) {
                $template->setValue('item_mt#' . ($i + 1), $item_mt);
            }
        } else {
            $template->cloneBlock('MT_SATU', 0, true, true);
            $template->cloneBlock('MAKSUD_TUJUAN_BLOCK', 0, true, true);
        }

        // ===== KELUARAN =====
        $keluaranArr = [];
        if (!empty($keluaran)) {
            $keluaranArr = array_values(array_filter(
                array_map('trim', preg_split("/\r\n|\n|\r/", $keluaran))
            ));
        }
        $count_kl = count($keluaranArr);

        if ($count_kl === 1) {
            $template->cloneBlock('KELUARAN_SATU', 1, true, true);
            foreach ($keluaranArr as $i => $item_kl) {
                $template->setValue('item_kl#' . ($i + 1), $item_kl);
            }
            $template->cloneBlock('KELUARAN_BLOCK', 0, true, true);
        } elseif ($count_kl > 1) {
            $template->cloneBlock('KELUARAN_SATU', 0, true, true);
            $template->cloneBlock('KELUARAN_BLOCK', $count_kl, true, true);
            foreach ($keluaranArr as $i => $item_kl) {
                $template->setValue('item_kl#' . ($i + 1), $item_kl);
            }
        } else {
            $template->cloneBlock('KELUARAN_SATU', 0, true, true);
            $template->cloneBlock('KELUARAN_BLOCK', 0, true, true);
        }

        $vol_num       = (float) preg_replace('/[^\d.]/', '', (string)$vol);
        $biaya_num     = (float) preg_replace('/[^\d.]/', '', (string)$biaya);
        $total_biaya   = $vol_num * $biaya_num;
        $terbilang_total = $this->terbilang_rupiah($total_biaya);

        $template->setValue('unit_organisasi', $unit_organisasi);
        $template->setValue('program', $program);
        $template->setValue('kegiatan', $kegiatan);
        $template->setValue('kro', $kro);
        $template->setValue('ro', $ro);
        $template->setValue('komponen', $komponen);
        $template->setValue('kode_anggaran', $kode_anggaran);
        $template->setValue('akun_anggaran', $akun_anggaran);
        $template->setValue('kota_kegiatan', $kota_kegiatan);
        $template->setValue('prov_kegiatan', $provinsi);
        $template->setValue('tahun_anggaran', $tahun_anggaran);
        $template->setValue('gambaran_umum', $gambaran_umum ?: '-');
        $template->setValue('nama_kegiatan', $nama_kegiatan);
        $template->setValue('waktu', $waktu);
        $template->setValue('tanggal_bayar', $tanggal_bayar);
        $template->setValue('lokasi', $lokasi);
        $template->setValue('vol', $vol);
        $template->setValue('satuan', $satuan);
        $template->setValue('biaya', $this->format_angka_rupiah($biaya));
        $template->setValue('total_biaya', $this->format_angka_rupiah($total_biaya));
        $template->setValue('terbilang_total', $terbilang_total);
        $template->setValue('tanggal_buat', $tanggal_buat);
        $template->setValue('nama_ppk', $ppk['nama']);
        $template->setValue('nip_ppk', $ppk['nip']);
        $template->setValue('nama_kepala', $kepala);
        $template->setValue('nip_kepala', $nip_kepala);

        // ===== TIMPA FILE DOCX LAMA =====
        $outputDirDocx = FCPATH . 'storage/docx/';
        $docxPath      = $outputDirDocx . $baseName . '.docx';
        $template->saveAs($docxPath); // ✅ timpa file lama

        // ===== TIMPA FILE PDF LAMA =====
        $outputDirPdf = FCPATH . 'storage/pdf/';
        $pdfPath      = $outputDirPdf . $baseName . '.pdf';

        Settings::setPdfRendererName(Settings::PDF_RENDERER_DOMPDF);
        Settings::setPdfRendererPath(APPPATH . 'third_party/dompdf');

        try {
            $phpWord = IOFactory::load($docxPath);
            $writer  = IOFactory::createWriter($phpWord, 'PDF');
            $writer->save($pdfPath); // ✅ timpa file lama
        } catch (Exception $e) {
            log_message('error', 'PDF convert failed: ' . $e->getMessage());
        }

        $this->session->set_flashdata('success', 'Dokumen berhasil direvisi');
        redirect('docxgenerator/timeline/' . $id_dokumen);
    }

    public function timeline_dok($id)
    {
        $id_user = $this->session->userdata('user_id');
        $nip = $this->session->userdata('nip');
        $role = $this->session->userdata('role');
        $data['title'] = 'DOCX Generator';
        $data['timeline'] = $this->Docxgenerator_model->get_logs_dokumen($id);
        $data['kepala'] = $this->Docxgenerator_model->get_kepala_default();
        $data['documents'] = $this->Docxgenerator_model->get_doc_by_id($id);   
        $data['anggaran'] = $this->Docxgenerator_model->get_all_anggaran();
        $data['content'] = $this->load->view('timeline', $data, TRUE);
        $this->load->view('layouts/main', $data);
    }


    public function download_file($filename)
    {
        $filename = basename($filename);
        $downloadName = $filename . '.docx';

        $path = FCPATH . 'storage/docx/' . $downloadName;

        if (!file_exists($path)) {
            show_404();
        }

        $this->load->helper('download');

    // INI KUNCINYA
        $data = file_get_contents($path);

        force_download($downloadName, $data);
    }

    public function download($file)
    {
        $path = FCPATH . 'storage/docx/' . $file;

        if (!file_exists($path)) {
            show_404();
        }

        header("Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document");
        header("Content-Disposition: attachment; filename=\"$file\"");
        header("Content-Length: " . filesize($path));
        readfile($path);
        exit;
    }

    public function download_pdf($file)
    {
        $path = FCPATH . 'storage/pdf/' . $file;

        if (!file_exists($path)) {
            show_404();
        }

        header("Content-Type: application/pdf");
        header("Content-Disposition: attachment; filename=\"$file\"");
        header("Content-Length: " . filesize($path));
        readfile($path);
        exit;
    }

    public function search_regencies()
    {
        $q = $this->input->get('q', true);
        $rows = $this->Docxgenerator_model->search_regencies($q, 20);

        $out = [];
        foreach ($rows as $r) 
        {
            $out[] = [
                'id' => $r['id'],
            'text' => $r['regency_name'],     // yang tampil di dropdown
            'province_id' => $r['province_id'],
            'province' => $r['province_name'],];
        }

        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($out));
    }

    public function terbilang($angka)
    {
        $angka = abs((int)$angka);

        $bilangan = [
            '',
            'satu',
            'dua',
            'tiga',
            'empat',
            'lima',
            'enam',
            'tujuh',
            'delapan',
            'sembilan',
            'sepuluh',
            'sebelas'
        ];

        if ($angka < 12) {
            return ' ' . $bilangan[$angka];
        } elseif ($angka < 20) {
            return $this->terbilang($angka - 10) . ' belas';
        } elseif ($angka < 100) {
            return $this->terbilang(intval($angka / 10)) . ' puluh' . $this->terbilang($angka % 10);
        } elseif ($angka < 200) {
            return ' seratus' . $this->terbilang($angka - 100);
        } elseif ($angka < 1000) {
            return $this->terbilang(intval($angka / 100)) . ' ratus' . $this->terbilang($angka % 100);
        } elseif ($angka < 2000) {
            return ' seribu' . $this->terbilang($angka - 1000);
        } elseif ($angka < 1000000) {
            return $this->terbilang(intval($angka / 1000)) . ' ribu' . $this->terbilang($angka % 1000);
        } elseif ($angka < 1000000000) {
            return $this->terbilang(intval($angka / 1000000)) . ' juta' . $this->terbilang($angka % 1000000);
        } elseif ($angka < 1000000000000) {
            return $this->terbilang(intval($angka / 1000000000)) . ' miliar' . $this->terbilang($angka % 1000000000);
        } elseif ($angka < 1000000000000000) {
            return $this->terbilang(intval($angka / 1000000000000)) . ' triliun' . $this->terbilang($angka % 1000000000000);
        } else {
            return ' angka terlalu besar';
        }
    }

    public function terbilang_rupiah($angka)
    {
        if ($angka == 0) {
            return 'Nol rupiah';
        }

        $hasil = trim($this->terbilang($angka));
        return ucfirst($hasil) . ' rupiah';
    }


    public function format_angka_rupiah($angka)
    {
        if ($angka === null || $angka === '') {
            return '0';
        }

        $angka = preg_replace('/[^\d]/', '', (string)$angka);
        return number_format((float)$angka, 0, ',', '.');
    }

    public function search_ppk()
    {
        $q = $this->input->get('q', true);
        $data = $this->Docxgenerator_model->search_ppk($q);

        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($data));
    }

    public function delete()
    {
        $id = (int) $this->input->post('id');

        if (!$id) {
            show_404();
        }

        $doc = $this->Docxgenerator_model->get_by_id($id);
        if (!$doc) {
            show_404();
        }

    // hapus file fisik
        $filePath = FCPATH . 'storage/docx/' . $doc->file_doc . '.docx';
        if (file_exists($filePath)) {
            unlink($filePath);
        }

    // hapus DB
        $this->Docxgenerator_model->delete($id);

        $this->session->set_flashdata('success', 'Dokumen berhasil dihapus');
        redirect('docxgenerator');
    }

    public function pengajuan($id_doc)
    {
        // ambil user login
        $nip = $this->session->userdata('nip');

        // ambil dokumen berdasarkan file_doc
        $dokumen = $this->Docxgenerator_model->getByFile($id_doc);

        if (!$dokumen) {
            show_error('Dokumen tidak ditemukan');
        }

        // validasi status
        if ($dokumen->status !== 'draft') {
            show_error('Dokumen tidak dapat diajukan');
        }

        // tentukan penerima (misal supervisor)
        $penerima = $this->Docxgenerator_model->getPenerimaByDokumen($dokumen->id);

        $this->db->trans_begin();

        // update status dokumen
        $this->Docxgenerator_model->updateStatus($dokumen->id, 'ajuan_baru');

        // insert log
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
            redirect('docxgenerator');
        } else {
            $this->db->trans_commit();
            $this->session->set_flashdata('success', 'Dokumen berhasil diajukan');
            redirect('docxgenerator'); // sesuaikan halaman tujuan
        }
    }

    public function view_docx($filename)
    {
        $filename = basename($filename) . '.docx';

        $token = $this->input->get('token');

    // validasi token
        if (!$this->isValidToken($filename, $token)) {
            show_error('Unauthorized', 401);
        }

        $path = FCPATH . 'storage/docx/' . $filename;

        if (!file_exists($path)) {
            show_404();
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Disposition: inline; filename="'.$filename.'"');
        header('Content-Length: ' . filesize($path));

        readfile($path);
        exit;
    }

    private function isValidToken($filename, $token)
    {
        if (!$token) return false;

        $secret = 'KUNCI_RAHASIA_APP'; // simpan di config
        $expected = hash_hmac('sha256', $filename, $secret);

        return hash_equals($expected, $token);
    }

    public function get_log($id)
    {
        $data = $this->Docxgenerator_model->get_logs_dokumen($id);

        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($data));
    }

    public function tindak_lanjut()
    {
        $id_dokumen = (int) $this->input->post('id_dokumen');
        $aksi       = $this->input->post('aksi');
        $pesan      = $this->input->post('pesan');

        $nip_login = $this->session->userdata('nip');

        if (!$id_dokumen || !$aksi) {
            echo json_encode(['status'=>false, 'message'=>'Data tidak valid']);
            return;
        }

        $dokumen = $this->Docxgenerator_model->get_by_id($id_dokumen);

        if (!$dokumen) {
            echo json_encode(['status'=>false, 'message'=>'Dokumen tidak ditemukan']);
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

            echo json_encode([
                'status' => false,
                'message' => 'Gagal tindak lanjut'
            ]);
        } else {
            $this->db->trans_commit();

            echo json_encode([
                'status' => true,
                'message' => 'Berhasil'
            ]);
        }
    }

}
