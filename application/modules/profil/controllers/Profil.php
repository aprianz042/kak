<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profil extends Authenticated_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('profil/Profil_model');
        $this->load->library('form_validation');
        $this->load->helper(['url', 'security']);
    }

    public function index()
    {
        $id_p = $this->session->userdata('user_id');
        $data['nama']    = $this->session->userdata('nama');
        $data['title']   = 'Profil';
        $data['profil'] = $this->Profil_model->get_by_id($id_p);
        $data['content'] = $this->load->view('profil_view', $data, TRUE);
        $this->load->view('layouts/main', $data);
    }

    public function update_profil()
    {
        $id = $this->session->userdata('user_id');

        $data = [
            'nama'  => $this->input->post('nama', true),
            'email' => $this->input->post('email', true),
        ];

        $this->Profil_model->update($id, $data);

        $this->session->set_flashdata('success', 'Profil berhasil diperbarui');
        redirect('profil');
    }

    public function update_password()
    {
        $id = $this->session->userdata('user_id');

        $current = $this->input->post('current_password');
        $new     = $this->input->post('new_password');
        $confirm = $this->input->post('confirm_password');

        $user = $this->Profil_model->get_by_id($id);

    // 1. cek password lama
        if (!$user || !password_verify($current, $user->pass)) {
            $this->session->set_flashdata('error', 'Password lama salah');
            redirect('profil#ubah-password');
            return;
        }

    // 2. cek konfirmasi
        if ($new !== $confirm) {
            $this->session->set_flashdata('error', 'Konfirmasi password tidak cocok');
            redirect('profil#ubah-password');
            return;
        }

    // 3. validasi minimal
        if (strlen($new) < 6) {
            $this->session->set_flashdata('error', 'Password minimal 6 karakter');
            redirect('profil#ubah-password');
            return;
        }

    // 4. update password (pakai field 'pass')
        $data = [
            'pass' => password_hash($new, PASSWORD_DEFAULT)
        ];

        $this->Profil_model->update($id, $data);

        $this->session->set_flashdata('success', 'Password berhasil diubah');
        redirect('profil#ubah-password');
    }


    /*public function upload_ttd()
    {
        $id = $this->session->userdata('user_id');

        if (!empty($_FILES['ttd']['name'])) {

            $config['upload_path']   = './storage/ttd/';
            $config['allowed_types'] = 'jpg|jpeg|png';
            $config['max_size']      = 2048;
            $config['encrypt_name']  = true;

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('ttd')) {

                $file = $this->upload->data('file_name');

            // hapus file lama
                $old = $this->Profil_model->get_by_id($id);
                if (!empty($old->ttd) && file_exists('./storage/ttd/'.$old->ttd)) {
                    unlink('./storage/ttd/'.$old->ttd);
                }

                $this->Profil_model->update($id, ['ttd' => $file]);

                $this->session->set_flashdata('success', 'TTD berhasil diupload');
            } else {
                $this->session->set_flashdata('error', $this->upload->display_errors());
            }
        }

        redirect('profil#tanda-tangan');
    }*/

    public function upload_ttd()
    {
        $id = $this->session->userdata('user_id');

        $base64 = $this->input->post('ttd_base64');

        if (!empty($base64)) {

        // decode base64
            $image = str_replace('data:image/png;base64,', '', $base64);
            $image = str_replace(' ', '+', $image);
            $image = base64_decode($image);

        // nama file
            $file = uniqid() . '.png';
            $path = './storage/ttd/' . $file;

            file_put_contents($path, $image);

        // hapus lama
            $old = $this->Profil_model->get_by_id($id);
            if (!empty($old->ttd) && file_exists('./storage/ttd/'.$old->ttd)) {
                unlink('./storage/ttd/'.$old->ttd);
            }

            $this->Profil_model->update($id, ['ttd' => $file]);

            $this->session->set_flashdata('success', 'TTD berhasil diupload (cropped)');
        } else {
            $this->session->set_flashdata('error', 'Gagal crop tanda tangan');
        }

        redirect('profil#tanda-tangan');
    }


    public function hapus_ttd()
    {
        $id = $this->session->userdata('user_id');

        $user = $this->Profil_model->get_by_id($id);

        if (!empty($user->ttd) && file_exists('./storage/ttd/'.$user->ttd)) {
            unlink('./storage/ttd/'.$user->ttd);
        }

        $this->Profil_model->update($id, ['ttd' => null]);

        $this->session->set_flashdata('success', 'TTD dihapus');
        redirect('profil#tanda-tangan');
    }



}
