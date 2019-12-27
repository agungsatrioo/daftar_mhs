<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Apimhs extends REST_Controller {
    function __construct($config = 'rest') {
        parent::__construct($config);
        $this->load->model(["M_mhs"=>"mhs"]);
        
    }
    function index_get() {
        $id = $this->get('nim');
        if ($id == '') {
            $kontak = $this->mhs->get_mhs();
        } else {
            $kontak = $this->mhs->get_mhs($id);
        }
        $this->response($kontak, 200);
    }

    //Masukan function selanjutnya disini
}