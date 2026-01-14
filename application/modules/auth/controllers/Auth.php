<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends MY_Controller {
    
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
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
        $nip      = $this->input->post('nip');
        $password = $this->input->post('pass');

        $user = $this->db->get_where('pegawai', [
            'nip' => $nip
        ])->row();

        if ($user && password_verify($password, $user->pass)) {

            $this->session->set_userdata([
                'logged_in' => TRUE,
                'user_id'   => $user->id,
                'nip'       => $user->nip,
                'nama'      => $user->nama,
                'role'      => $user->role
            ]);

            redirect('home');
        }

        $this->session->set_flashdata('error', 'NIP atau Password salah');
        redirect(base_url('auth/login'));
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect(base_url('auth/login'));
    }
}
