<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends Authenticated_Controller {

    public function index()
    {
        $data['nama'] = $this->session->userdata('nama');
        $this->load->view('home_view', $data);
    }
}
