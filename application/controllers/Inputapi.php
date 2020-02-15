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
        // Use try-catch
        // JWT library throws exception if the token is not valid
        try {
            // Extract the token
            array_change_key_case($headers, CASE_LOWER);

            $token = $headers['authorization'];
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
        $id = $this->get('nik');

        $kontak = $this->acd->upemqsh("proposal",$id);
        $this->response($kontak, 200);
    }

    function munaqosah_get() {
        $id = $this->get('nik');

        $kontak = $this->acd->upemqsh("munaqosah",$id);
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
}
