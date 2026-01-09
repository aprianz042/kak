<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
    }

    private function render($view)
    {
        $data['content'] = $view;
        $this->load->view('layout/dashboard_layout', $data);
    }

    public function index()
    {
        $this->render('dashboard/home');
    }

    public function docx()
    {
        $this->render('dashboard/docx');
    }

    public function users()
    {
        $this->render('dashboard/users');
    }

    public function products()
    {
        $this->render('dashboard/products');
    }

    public function orders()
    {
        $this->render('dashboard/orders');
    }

    public function settings()
    {
        $this->render('dashboard/settings');
    }

    public function users_create()
    {
        $this->render('dashboard/users_create');
    }

    public function users_store()
    {
        $nip  = $this->input->post('nip');
        $nama = $this->input->post('nama');
        $pass = $this->input->post('pass');
        $role = $this->input->post('role');

        // cek nip sudah ada
        $cek = $this->db->get_where('pegawai', ['nip' => $nip])->row();
        if ($cek) {
            $this->session->set_flashdata('error', 'NIP sudah terdaftar');
            redirect('dashboard/users_create');
        }

        $this->db->insert('pegawai', [
            'nip'  => $nip,
            'nama' => $nama,
            'pass' => password_hash($pass, PASSWORD_DEFAULT),
            'role' => $role
        ]);

        $this->session->set_flashdata('success', 'Pegawai berhasil ditambahkan');
        redirect('dashboard/users_create');
    }

}
