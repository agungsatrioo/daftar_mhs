<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Inputapi extends REST_Controller {
    function __construct($config = 'rest') {
        parent::__construct($config);
        $this->load->model(["M_mhs"=>"mhs"]);
        $this->load->model(["M_user"=>"user"]);
    }

    private function alterable() {
        // BACA YA!
        // Jadiin true kalo mau ada fungsi edit/delete.
        return false;
    }

    function index_get() {
        $id = $this->get('nim');

        $kontak = $this->mhs->get_mhs($id);
        $this->response($kontak, 200);
    }

    function index_post() {
        $type = $this->post("type");

        if($type=="login") {
            $details = $this->user->login_api($this);
            $this->response($details, $details["code"]);

            //$this->response("you want to login?", 200);
        }
    }
}
