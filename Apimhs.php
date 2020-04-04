<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Apimhs extends REST_Controller {

    function array_key_first(array $arr) {
        foreach($arr as $key => $unused) {
            return $key;
        }
        return NULL;
    }


    function __construct($config = 'rest') {
        parent::__construct($config);
        $this->load->model(["M_mhs"=>"mhs"]);
    }

    private function alterable() {
        // BACA YA!
        // Jadiin true kalo mau ada fungsi edit/delete.
        return false;
    }

    //READ
    function index_get() {
        $id = $this->get('nim');

        $kontak = $this->mhs->get_mhs($id);
        $this->response($kontak, 200);
    }

    //DELETE
    function index_delete() {
        $db_debug = $this->db->db_debug; //save setting
        $this->db->db_debug = FALSE; //disable debugging for queries

        $nim = $this->delete("nim");

        if($this->alterable()) {
            $query = $this->db->delete("t_mahasiswa", ["nim" => $nim]);

            if(!$query) {
                $error = $this->db->error();
                $this->response(array('status' => 'fail', 502, 'Query gagal.', "cause"=> $error));
            } else {
                $this->response(array('status' => 'success'), 201);
            }
        } else {
            $this->response(array('status' => 'success'), 201);
        }

        $this->db->db_debug = $db_debug; //restore setting
    }

    //CREATE
    function index_post() {
        $db_debug = $this->db->db_debug; //save setting
        $this->db->db_debug = FALSE; //disable debugging for queries

        $post = [
            "nim"           => $this->post('nim'),
            "nama"          => $this->post('nama'),
            "jk"            => $this->post('jk'),
            "tempat_lahir"  => $this->post('tempat_lahir'),
            "tanggal_lahir" => $this->post('tanggal_lahir'),
            "tanggal_masuk" => $this->post('tanggal_masuk'),
            "kode_jurusan"  => $this->post('kode_jurusan'),
            "nik_dospem"  => 2,
        ];

        if($this->alterable()) {
            $query = $this->db->insert("t_mahasiswa", $post);

            if(!$query) {
                $error = $this->db->error();
                $this->response(array('status' => 'fail', 502, 'Query gagal.', "cause"=> $error));
            } else {
                $this->response(array('status' => 'success'), 201);
            }
        } else {
            $this->response(array('status' => 'success'), 201);
        }

        $this->db->db_debug = $db_debug; //restore setting
    }

    //UPDATE
    function index_put() {
        $id = $this->put('nim');
        $db_debug = $this->db->db_debug; //save setting
        $this->db->db_debug = FALSE; //disable debugging for queries

        $post = [
            "nim"           => $this->put('nim'),
            "nama"          => $this->put('nama'),
            "jk"            => $this->put('jk'),
            "tempat_lahir"  => $this->put('tempat_lahir'),
            "tanggal_lahir" => $this->put('tanggal_lahir'),
            "tanggal_masuk" => $this->put('tanggal_masuk'),
            "kode_jurusan"  => $this->put('kode_jurusan'),
        ];

        if($this->alterable()) {
            $query = $this->db->update("t_mahasiswa", $post, ["nim" => $id]);

            if(!$query) {
                $error = $this->db->error();
                $this->response(array('status' => 'fail', 502, 'Query gagal.', "cause"=> $error));
            } else {
                $this->response(array('status' => 'success'), 201);
            }
        } else {
            $this->response(array('status' => 'success'), 201);
        }

        $this->db->db_debug = $db_debug; //restore setting
    }
}
