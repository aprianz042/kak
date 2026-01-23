<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pegawai extends Authenticated_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('pegawai/Pegawai_model');
        $this->load->library('form_validation');
        $this->load->helper(['url', 'security']);
    }

    public function index()
    {
        $data['nama']    = $this->session->userdata('nama');
        $data['title']   = 'Pegawai';
        $data['pegawai'] = $this->Pegawai_model->get_all();
        $data['content'] = $this->load->view('pegawai_view', $data, TRUE);
        $this->load->view('layouts/main', $data);
    }

    // =========================
    // SIMPAN DATA
    // =========================
    public function simpan()
    {
        $this->form_validation->set_rules('nip', 'NIP', 'required|trim');
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email');
        $this->form_validation->set_rules('nama', 'Nama', 'required|trim');
        $this->form_validation->set_rules('jabatan', 'Jabatan', 'required|trim');
        $this->form_validation->set_rules('role', 'Role', 'required|trim');
        $this->form_validation->set_rules('pass', 'Password', 'required|min_length[6]');

        if ($this->form_validation->run() === FALSE) {
            echo json_encode([
                'status'  => false,
                'message' => strip_tags(validation_errors())
            ]);
            return;
        }

        if ($this->Pegawai_model->cek_nip($this->input->post('nip'))) {
            echo json_encode([
                'status'  => false,
                'message' => 'NIP sudah terdaftar'
            ]);
            return;
        }

        if ($this->Pegawai_model->cek_email($this->input->post('email'))) {
            echo json_encode([
                'status'  => false,
                'message' => 'Email sudah terdaftar'
            ]);
            return;
        }

        $data = [
            'nip'        => $this->input->post('nip', true),
            'email'      => $this->input->post('email', true),
            'nama'       => $this->input->post('nama', true),
            'jabatan'    => $this->input->post('jabatan', true),
            'role'       => $this->input->post('role', true),
            'pass'       => password_hash($this->input->post('pass'), PASSWORD_BCRYPT),
            'created_at' => date('Y-m-d H:i:s')
        ];

        $insert = $this->Pegawai_model->insert($data);

        echo json_encode([
            'status'  => $insert,
            'message' => $insert ? 'Data pegawai berhasil disimpan' : 'Gagal menyimpan data pegawai'
        ]);
    }

    public function get($id)
    {
        echo json_encode($this->Pegawai_model->get_by_id($id));
    }

    // =========================
    // UPDATE DATA
    // =========================
    public function update()
    {
        $id = $this->input->post('id');

        if ($this->Pegawai_model->cek_email_edit($this->input->post('email'), $id)) {
            echo json_encode([
                'status'  => false,
                'message' => 'Email sudah digunakan pegawai lain'
            ]);
            return;
        }

        $data = [
            'nip'     => $this->input->post('nip', true),
            'email'   => $this->input->post('email', true),
            'nama'    => $this->input->post('nama', true),
            'jabatan' => $this->input->post('jabatan', true),
            'role'    => $this->input->post('role', true),
        ];

        if (!empty($this->input->post('pass'))) {
            $data['pass'] = password_hash($this->input->post('pass'), PASSWORD_BCRYPT);
        }

        $update = $this->Pegawai_model->update($id, $data);

        echo json_encode([
            'status'  => $update,
            'message' => $update ? 'Data berhasil diupdate' : 'Gagal update data'
        ]);
    }

    public function hapus($id)
    {
        $hapus = $this->Pegawai_model->delete($id);

        echo json_encode([
            'status'  => $hapus,
            'message' => $hapus ? 'Data berhasil dihapus' : 'Gagal menghapus data'
        ]);
    }
}
