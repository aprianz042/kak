<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;

// Include DomPDF autoloader
require_once APPPATH . 'third_party/dompdf/autoload.inc.php';

class Docxgenerator extends Authenticated_Controller {

    public function index()
    {
        $data['title'] = 'DOCX Generator';

        $data['content'] = $this->load->view('docxgenerator_view', [], TRUE);
        $this->load->view('layouts/main', $data);
    }

    public function generate()
    {
        $nama       = $this->input->post('nama', true);
        $nip        = $this->input->post('nip', true);
        $instansi   = $this->input->post('instansi', true);
        $deskripsi  = $this->input->post('deskripsi', true);
        $rawRincian = $this->input->post('rincian', true);

        // format tanggal Indonesia
        setlocale(LC_TIME, 'id_ID.UTF-8');
        $tanggal = strftime('%d %B %Y');

        $templatePath = APPPATH . 'templates/template.docx';
        if (!file_exists($templatePath)) {
            show_error('Template DOCX tidak ditemukan');
        }

        $template = new TemplateProcessor($templatePath);

        // ===== PROSES RINCIAN =====
        $rincianArr = [];

        if (!empty($rawRincian)) {
            $rincianArr = array_values(array_filter(
                array_map('trim', preg_split("/\r\n|\n|\r/", $rawRincian))
            ));
        }

        $count = count($rincianArr);

        if ($count === 1) 
        {
            $template->cloneBlock('RINCIAN_SATU', 1, true, true);
            foreach ($rincianArr as $i => $item) 
            {
                $template->setValue('item#' . ($i + 1), $item);
            }
            $template->cloneBlock('RINCIAN_BLOCK', 0, true, true);
        } 
        elseif ($count > 1) 
        {
            $template->cloneBlock('RINCIAN_SATU', 0, true, true);
            $template->cloneBlock('RINCIAN_BLOCK', $count, true, true);
            foreach ($rincianArr as $i => $item) 
            {
                $template->setValue('item#' . ($i + 1), $item);
            }
        } 
        else 
        {
            $template->cloneBlock('RINCIAN_SATU', 0, true, true);
            $template->cloneBlock('RINCIAN_BLOCK', 0, true, true);
        }

        $template->setValue('nama', $nama);
        $template->setValue('nip', $nip);
        $template->setValue('tanggal', $tanggal);
        $template->setValue('instansi', $instansi);
        $template->setValue('deskripsi', $deskripsi ?: '-');

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
}
