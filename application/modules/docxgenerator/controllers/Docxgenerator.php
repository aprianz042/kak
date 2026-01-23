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
        $this->load->model('DOcxgenerator_model');
    }


    public function index()
    {
        $data['title'] = 'DOCX Generator';

        $data['content'] = $this->load->view('docxgenerator_view', [], TRUE);
        $this->load->view('layouts/main', $data);
    }

    public function generate()
    {
        $unit_organisasi = $this->input->post('unit_organisasi', true);
        $program = $this->input->post('program', true);
        $kegiatan = $this->input->post('kegiatan', true);
        $kro = $this->input->post('kro', true);
        $ro = $this->input->post('ro', true);
        $komponen = $this->input->post('komponen', true);
        $kode_anggaran = $this->input->post('kode_anggaran', true);
        $akun_anggaran = $this->input->post('akun_anggaran', true);

        $regency_id = $this->input->post('regency_id', true);

        $wil = $this->DOcxgenerator_model->get_regency_with_province($regency_id);
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
        $ppk = $this->input->post('ppk', true);
        $nip_ppk = $this->input->post('nip_ppk', true);
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
        $template->setValue('nama_ppk', $ppk);
        $template->setValue('nip_ppk', $nip_ppk);
        $template->setValue('nama_kepala', $kepala);
        $template->setValue('nip_kepala', $nip_kepala);


        // ===== SIMPAN DOCX =====
        $outputDirDocx = FCPATH . 'storage/docx/';
        if (!is_dir($outputDirDocx)) {
            mkdir($outputDirDocx, 0777, true);
        }

        $baseName = 'dokumen_' . time();
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
        $rows = $this->DOcxgenerator_model->search_regencies($q, 20);

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


}
