<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {
    
    const LEVEL_MAHASISWA = 4;
    const LEVEL_PIMIPINAN = 3;
    const LEVEL_ADMIN_2 = 2;
    const LEVEL_ADMIN_1 = 1;
    
    function __construct() {
        parent::__construct();
        $this->ci_minifier->init(0);
    }

    protected function makeview($page, array $data) {
        $data['contents'] = $this->load->view($page, $data, true);
        $data['container_type'] = "container";
        $data['use_bg'] = "bg-gradient-primary";
        $this->load->view('base/v_base', $data);
    }

    protected function make_dashboard($title, $page, array $data) {
        $data['main_view'] = "";
        $data['container_type'] = "wrapper";
        $data['contents'] = $this->load->view('base/v_content_wrapper', $data, true);
        $this->load->view('base/v_base', $data);
    }
    
    public function make_404() {
        $data['title'] = "Halaman tidak ditemukan";
        $this->makeview('common/v_error_404', $data);
    }

    protected function makelinkrel() {
        
    }
}
