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
        return true;
    }

    //READ
    function index_get() {
        $id = $this->get('nim');

        $kontak = $this->mhs->get_mhs($id);
        $this->response($kontak, 200);
    }

    //DELETE
    function index_delete() {
        $nim = $this->delete("nim");

        $delete = $this->alterable() ? $this->db->delete("t_mahasiswa", ["nim" => $nim]) : true;

        if ($nim!=null && $delete) {
            $this->response(array('status' => 'success'), 201);
        } else {
            if($nim==null) $this->response(array('status' => 'fail', 502, "Tidak ada parameter 'nim'"));
            elseif(!$delete) {
                $this->response(array('status' => 'fail', 502, 'Query gagal.'));
            }
        }
    }

    //CREATE
    function index_post() {
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

        $ret = $this->alterable() ? $this->db->insert("t_mahasiswa", $post) : true;

        if ($ret) {
            $this->response(array('status' => 'success'), 201);
        } else {
            $this->response(array('status' => 'fail', 502, 'Query gagal.', "cause"=> $ret));
        }
    }

    //UPDATE
    function index_put() {
        $id = $this->put('nim');

        $post = [
            "nim"           => $this->put('nim'),
            "nama"          => $this->put('nama'),
            "jk"            => $this->put('jk'),
            "tempat_lahir"  => $this->put('tempat_lahir'),
            "tanggal_lahir" => $this->put('tanggal_lahir'),
            "tanggal_masuk" => $this->put('tanggal_masuk'),
            "kode_jurusan"  => $this->put('kode_jurusan'),
        ];

        $ret = $this->alterable() ? $this->db->update("t_mahasiswa", $post, ["nim" => $id]) : true;

        if ($ret) {
            $this->response(array('status' => 'success'), 201);
        } else {

            $this->response(array('status' => 'fail', 502, 'Query gagal.', "cause"=> $ret));
        }
    }
}
