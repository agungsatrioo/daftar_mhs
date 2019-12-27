<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends MY_Controller {

    function __construct() {
        parent::__construct();

        show_404();
        
        //if($this->M_user->user()!=null) redirect(base_url("home"));
    }

    public function _remap($method, $params = array()) {
        switch($method) {
            case "": case "index":
                redirect("login");
                break;
            case "login":
                $this->login_page();
                break;
            case "do_login":
                $this->do_login();
                break;
            default:
                parent::_remap($method, $params);
        }
    }
    
    private function login_page() {
        $attributes = array('class' => 'user', 'id' => 'myform');

        $data['title'] = "Login";
        $data['login_url'] = base_url('auth/do_login');
        $data['form_open'] = form_open(base_url('auth/do_login'), $attributes);
        $data['form_close'] = form_close();
        $data['error']      = $this->session->flashdata('error');

        $this->makeview('auth/v_login',$data);
    }
    
    private function do_login() {
        $log = $this->M_user->login($this->input);

        if($log==true) {
            $this->session->set_flashdata('identity','Berhasil login sebagai');
        } else {
            $this->session->set_flashdata('error','Identitas atau kata sandi tidak benar');
        }

        redirect(site_url('login'));
    }
}
