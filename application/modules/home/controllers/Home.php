<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends Authenticated_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('home/Home_model');
        $this->load->library('form_validation');
        $this->load->helper(['url', 'security']);
    }


    public function index()
    {
        $role = $this->session->userdata('role');

        $data['nama'] = $this->session->userdata('nama');
        $data['title'] = 'Home';
        $data['role'] = $role;
        $data['anggaran']  = $this->Home_model->get_realisasi();

        $data['total_draft']    = $this->Home_model->count_draft();
        $data['total_disetujui'] = $this->Home_model->count_disetujui();
        $data['total_ditolak']  = $this->Home_model->count_ditolak();
        $data['total_lainnya']  = $this->Home_model->count_lainnya();
        $data['statistik_ppk'] = $this->Home_model->count_by_ppk();
        $data['content'] = $this->load->view('home_view', $data, TRUE);            

        // load layout induk
        $this->load->view('layouts/main', $data);
    }
}
