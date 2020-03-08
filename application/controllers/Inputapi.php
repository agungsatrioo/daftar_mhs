<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

use Restserver\Libraries\REST_Controller;

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

class Inputapi extends REST_Controller {
    function __construct($config = 'rest') {
        parent::__construct($config);
        $this->load->model(["M_mhs"=>"mhs"]);
        $this->load->model(["M_user"=>"user"]);
        $this->load->model(["M_akademik"=>"acd"]);
        $this->load->helper(['jwt', 'authorization']);
    }

    private function alterable() {
        // BACA YA!
        // Jadiin true kalo mau ada fungsi edit/delete.
        return false;
    }

    private function _verify() {
        // Get all the headers
        $headers = $this->input->request_headers();

        print_r($headers);
        // Use try-catch
        // JWT library throws exception if the token is not valid
        try {
            // Extract the token
            $token = $headers['Authorization'];
            // Validate the token
            // Successfull validation will return the decoded user data else returns false
            $data = AUTHORIZATION::validateToken($token);
            if ($data === false) {
                $status = parent::HTTP_UNAUTHORIZED;
                $response = ['status' => "failed", "code"=>$status, 'msg' => 'Unauthorized Access!'];
                $this->response($response, $status);
                exit();
            } else {
                return $data;
            }
        } catch (Exception $e) {
            // Token is invalid
            // Send the unathorized access message
            $status = 501;
            $response = ['status' => "failed","code"=>$status, 'msg' => 'Access invalid.'];
            $this->response($response, $status);
        }
    }

    function index_get() {
        $id = $this->get('nim');

        $kontak = $this->mhs->get_mhs($id);
        $this->response($kontak, 200);
    }

    function mahasiswa_get() {
        $id = $this->get('nim');

        $kontak = $this->mhs->get_mhs($id);
        $this->response($kontak, 200);
    }

    function dosen_get() {
        $id = $this->get('nik');

        $kontak = $this->mhs->get_dosen($id);
        $this->response($kontak, 200);
    }

    function auth_post() {
        if(!empty($this->_verify())) {
            $details = $this->user->login_api($this);
            $this->response($details, 200);
        }
    }

    function up_get() {
        $id = $this->get('dosen');
        $mhs = $this->get('mahasiswa');

        $kontak = $this->acd->upemqsh("proposal",$id, $mhs);

        foreach($kontak as $key=>$item) {
            $status = $this->acd->get_status_dosen($item->nim, "Penguji Sidang Proposal %");
            $item->penguji = $status;
        }

        $this->response($kontak, 200);
    }

    function munaqosah_get() {
        $id = $this->get('dosen');

        $kontak = $this->acd->upemqsh("munaqosah",$id);

        $this->response($kontak, 200);
    }

    function nilai_up_get() {
        $mhs = $this->get('nim');
        $ada_nilai = true;

        $kontak = $this->acd->lihat_nilai("proposal", $mhs);

        foreach($kontak as $key=>$item) {
            $status = $this->acd->get_status_dosen($item->nim, "Penguji Sidang Proposal %");

            $item->penguji = $status;
        }

        if($kontak[0]->penguji[0]->nilai == null || $kontak[0]->penguji[1]->nilai == null) {
            $ada_nilai = false;
        }

        $kontak[0]->nilai = $ada_nilai ? (.5*$kontak[0]->penguji[0]->nilai) + (.5*$kontak[0]->penguji[1]->nilai) : null;

        $kontak[0]->mutu        =  $ada_nilai ? $this->acd->_mutu($kontak[0]->nilai) : null;
        $kontak[0]->color       = $ada_nilai ? $this->acd->warna($kontak[0]->nilai) : null;

        $this->response($kontak[0], 200);
    }

    function cek_nilai_get() {
        $id = $this->get('status');

        $kontak = $this->acd->cek_nilai($id);

        foreach($kontak as $key=>$item) {
            $item->color = $this->acd->warna($item->nilai);
        }

        if(empty($kontak)) $kontak = [["nilai" => "Belum ada", "mutu" => "Belum ada", "color" => "#000000"]];

        $this->response($kontak, 200);

    }

    public function hello_get() {
        $tokenData = 'SiflabAppInput';

        // Create a token
        $token = AUTHORIZATION::generateToken($tokenData);
        // Set HTTP status code
        $status = parent::HTTP_OK;
        // Prepare the response
        $response = ['status' => $status, 'token' => $token];
        // REST_Controller provide this method to send responses
        $this->response($response, $status);
    }

    public function status_get() {
        $id = $this->get("id");

        $f = $this->acd->cek_id_status($id);

        $this->response(["result" => $f], 200);
    }

    //

    public function input_nilai_post() {
        $id     = $this->post("id_status");
        $nilai  = $this->post("nilai");

        if(!empty($this->_verify())) {
            if(!is_numeric($nilai)) {
                $this->response(["error" => "The value you entered ($nilai) is not a number."], 400);
            } else {
                if($nilai > 0 && $nilai <= 100) {
                    if($this->acd->func_input_nilai($id, $nilai)) {
                        $this->response(["info" => "ok"], 200);
                    } else {
                        $this->response(["error" => "Error when inputting!"], 400);
                    }
                } else {
                    $this->response(["error" => "Please enter 0-100. Value you entered is: $nilai"], 400);
                }
            }
        }
    }

}
