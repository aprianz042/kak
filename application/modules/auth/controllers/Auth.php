<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->model('Auth_model');
    }

    public function login()
    {
        if ($this->session->userdata('logged_in')) {
            redirect('home');
        }

        $this->load->view('auth_view');
    }

    public function do_login()
    {
        $nip      = $this->input->post('nip', true);
        $password = $this->input->post('pass');

        $user = $this->Auth_model->get_user_by_nip($nip);

        if ($user && password_verify($password, $user->pass)) {

            $this->session->set_userdata([
                'logged_in' => true,
                'user_id'   => $user->id,
                'nip'       => $user->nip,
                'email'     => $user->email,
                'nama'      => $user->nama,
                'role'      => $user->role
            ]);

            redirect('home');
            return;
        }

        $this->session->set_flashdata('error', 'NIP atau Password salah');
        redirect('auth/login');
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect('auth/login');
    }

}
