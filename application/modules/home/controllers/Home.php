<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends Authenticated_Controller {

    public function index()
    {
        $data['nama'] = $this->session->userdata('nama');
        $data['title'] = 'Home';

        // render view home sebagai STRING
        $data['content'] = $this->load->view('home_view', $data, TRUE);

        // load layout induk
        $this->load->view('layouts/main', $data);
    }
}
