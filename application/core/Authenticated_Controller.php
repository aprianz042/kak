<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Authenticated_Controller extends MY_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->load->library('session');

        if (!$this->session->userdata('logged_in')) {
            redirect(base_url('auth/login'));
            exit;
        }
    }
}
