<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpWord\TemplateProcessor;

class Docxgenerator extends Authenticated_Controller {

    public function index()
    {
        $data['title'] = 'DOCX Generator';

        // isi konten kanan
        $data['content'] = $this->load->view('docxgenerator_view', [], TRUE);

        // layout induk
        $this->load->view('layouts/main', $data);
    }

    public function generate()
    {
        $nama       = $this->input->post('nama');
        $nip        = $this->input->post('nip');
        $instansi   = $this->input->post('instansi');
        $deskripsi  = $this->input->post('deskripsi');

        setlocale(LC_TIME, 'id_ID.UTF-8');
        $tanggal = strftime('%d %B %Y');

        $templatePath = APPPATH . 'templates/template.docx';
        if (!file_exists($templatePath)) {
            show_error('Template DOCX tidak ditemukan');
        }

        $template = new TemplateProcessor($templatePath);

        $template->setValue('nama', $nama);
        $template->setValue('nip', $nip);
        $template->setValue('tanggal', $tanggal);
        $template->setValue('instansi', $instansi);
        $template->setValue('deskripsi', $deskripsi);

        $outputDir = FCPATH . 'storage/docx/';
        if (!is_dir($outputDir)) {
            mkdir($outputDir, 0777, true);
        }

        $fileName = 'dokumen_' . time() . '.docx';
        $filePath = $outputDir . $fileName;

        $template->saveAs($filePath);

        $this->session->set_flashdata('success', 'Dokumen berhasil dibuat');
        $this->session->set_flashdata('file', $fileName);

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
}
