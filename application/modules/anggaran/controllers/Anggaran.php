<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Anggaran extends Authenticated_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('anggaran/Anggaran_model');
        $this->load->library('form_validation');
        $this->load->helper(['url', 'security']);
    }

    public function index()
    {
        $data['nama']    = $this->session->userdata('nama');
        $data['title']   = 'Anggaran';
        $data['anggaran'] = $this->Anggaran_model->get_all();
        $data['content'] = $this->load->view('anggaran_view', $data, TRUE);
        $this->load->view('layouts/main', $data);
    }

    // =============================
    // SIMPAN DATA BARU
    // =============================
    public function simpan()
    {
        $this->cek_role(['operator']);

        $this->load->library('form_validation');

        $this->form_validation->set_rules('kode_akun',     'Kode Akun',  'required|trim|is_unique[anggaran.kode_akun]');
        $this->form_validation->set_rules('nama_kegiatan', 'Kegiatan',   'required|trim');
        $this->form_validation->set_rules('pagu',          'Pagu',       'required|is_natural_no_zero');

        // set pesan custom
        $this->form_validation->set_message('is_unique', 'Kode Akun sudah digunakan, gunakan kode lain.');

        if ($this->form_validation->run() === FALSE) {
            $errors = $this->form_validation->error_array();
            echo json_encode([
                'status'  => false,
                'message' => implode(' ', $errors),
            ]);
            return;
        }

        $data = [
            'kode_akun'     => $this->input->post('kode_akun'),
            'nama_kegiatan' => $this->input->post('nama_kegiatan'),
            'pagu'          => $this->input->post('pagu'),
        ];

        $simpan = $this->Anggaran_model->insert_data($data);

        if ($simpan) {
            echo json_encode([
                'status'  => true,
                'message' => 'Data anggaran berhasil ditambahkan.',
            ]);
        } else {
            echo json_encode([
                'status'  => false,
                'message' => 'Gagal menyimpan data.',
            ]);
        }
    }

    // =============================
    // GET DATA BY ID (untuk modal edit)
    // =============================
    public function get($id)
    {
        $this->cek_role(['operator']);
        $data = $this->Anggaran_model->get_by_id($id);

        if (!$data) {
            echo json_encode([
                'status'  => false,
                'message' => 'Data tidak ditemukan.',
            ]);
            return;
        }

        echo json_encode($data);
    }

    // =============================
    // UPDATE DATA
    // =============================
    public function update()
    {
        $this->cek_role(['operator']);

        $id = $this->input->post('id');

        if (!$id) {
            echo json_encode([
                'status'  => false,
                'message' => 'ID tidak valid.',
            ]);
            return;
        }

        $this->load->library('form_validation');

        // is_unique dengan pengecualian: [anggaran.kode_akun.id.{$id}]
        // artinya: unik di tabel anggaran kolom kode_akun, KECUALI baris dengan id = $id
        $this->form_validation->set_rules('kode_akun',     'Kode Akun',  "required|trim|is_unique[anggaran.kode_akun.id.{$id}]");
        $this->form_validation->set_rules('nama_kegiatan', 'Kegiatan',   'required|trim');
        $this->form_validation->set_rules('pagu',          'Pagu',       'required|is_natural_no_zero');

        $this->form_validation->set_message('is_unique', 'Kode Akun sudah digunakan, gunakan kode lain.');

        if ($this->form_validation->run() === FALSE) {
            $errors = $this->form_validation->error_array();
            echo json_encode([
                'status'  => false,
                'message' => implode(' ', $errors),
            ]);
            return;
        }

        $data = [
            'kode_akun'     => $this->input->post('kode_akun'),
            'nama_kegiatan' => $this->input->post('nama_kegiatan'),
            'pagu'          => $this->input->post('pagu'),
        ];

        $update = $this->Anggaran_model->update_data($id, $data);

        if ($update) {
            echo json_encode([
                'status'  => true,
                'message' => 'Data anggaran berhasil diperbarui.',
            ]);
        } else {
            echo json_encode([
                'status'  => false,
                'message' => 'Gagal memperbarui data.',
            ]);
        }
    }

    // =============================
    // HAPUS DATA
    // =============================
    public function hapus($id)
    {
        $this->cek_role(['operator']);

        $data = $this->Anggaran_model->get_by_id($id);

        if (!$data) {
            echo json_encode([
                'status'  => false,
                'message' => 'Data tidak ditemukan.',
            ]);
            return;
        }

        $hapus = $this->Anggaran_model->delete_data($id);

        if ($hapus) {
            echo json_encode([
                'status'  => true,
                'message' => 'Data anggaran berhasil dihapus.',
            ]);
        } else {
            echo json_encode([
                'status'  => false,
                'message' => 'Gagal menghapus data.',
            ]);
        }
    }
}
