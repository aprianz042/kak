<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        // BLOK AKSES JIKA BELUM LOGIN
        if ($this->session->userdata('logged_in') !== TRUE) {
            redirect('auth/login');
        }
    }

    public function index()
    {
        $this->load->view('home');
    }
}
