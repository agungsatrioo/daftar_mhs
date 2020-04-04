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
        $this->load->model(["M_dosen"=>"dosen"]);
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
        $hdr_raw = $this->input->request_headers();
        $headers = array_change_key_case($hdr_raw, CASE_LOWER);

        // Use try-catch
        // JWT library throws exception if the token is not valid
        try {
            // Extract the token
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
        $id = $this->get('id_dosen');

        $kontak = $this->dosen->get_dosen($id);
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

        $kontak = $this->acd->get_up_munaqosah("proposal",$id, $mhs);

        foreach($kontak as $key=>$item) {
            $ada_nilai = true;

            if(isset($mhs)) {
                $penguji        = $this->acd->get_status_dosen($item->nim, "Penguji Sidang Proposal %");
                $item->penguji  = $penguji;

                foreach($item->penguji as $k1=>$v1) {
                    if(!is_numeric($v1->nilai)) {
                        $item->nilai = ["nilai"=>$v1->nilai,"mutu"=>$v1->mutu,"color"=>$v1->color];
                        $ada_nilai   = false;
                        break;
                    }
                }

                if($ada_nilai) {
                    $nilai = (.5*$kontak[0]->penguji[0]->nilai) + (.5*$kontak[0]->penguji[1]->nilai);
                    $item->nilai = ["nilai"=>floor($nilai), "mutu"=>$this->acd->_mutu($nilai), "color"=>$this->acd->warna($nilai)];
                }

                break;
            } else {
                $item->nilai = $this->acd->cek_nilai($item->id_status)[0];
            }
        }

        $this->response(isset($mhs) ? $kontak[0] : $kontak, 200);
    }

    function munaqosah_get() {
        $id = $this->get('dosen');
        $mhs = $this->get('mahasiswa');

        $kontak = $this->acd->get_up_munaqosah("munaqosah", $id, $mhs);

        foreach($kontak as $key=>$item) {
            $ada_nilai = true;

            if(isset($mhs)) {
                $penguji        = $this->acd->get_status_dosen($item->nim, "Penguji Sidang Munaqosah %");
                $pembimbing     = $this->acd->get_status_dosen($item->nim, "Pembimbing Munaqosah %");
                $dosenku        = $this->acd->get_status_dosen($item->nim, "% Munaqosah %");

                $item->penguji  = $penguji;
                $item->pembimbing  = $pembimbing;

                foreach($dosenku as $k1=>$v1) {
                    if(!is_numeric($v1->nilai)) {
                        $item->nilai = ["nilai"=>$v1->nilai,"mutu"=>$v1->mutu,"color"=>$v1->color];
                        $ada_nilai   = false;
                        break;
                    }
                }

                if($ada_nilai) {
                    $nilai = (.3*$kontak[0]->penguji[0]->nilai) + (.3*$kontak[0]->penguji[1]->nilai) +  (.2*$kontak[0]->pembimbing[0]->nilai) + (.2*$kontak[0]->pembimbing[1]->nilai);

                    $item->nilai = ["nilai"=>floor($nilai), "mutu"=>$this->acd->_mutu($nilai), "color"=>$this->acd->warna($nilai)];
                }

                break;
            } else {
                $item->nilai = $this->acd->cek_nilai($item->id_status)[0];
            }
        }

        $this->response(isset($mhs) ? $kontak[0] : $kontak, 200);
    }

    function kompre_get() {
        $id = $this->get('dosen');
        $mhs = $this->get('mahasiswa');
        $presentase_kompre = .333333333; //must be precise!

        $kontak = $this->acd->get_kompre($id, $mhs);

        foreach($kontak as $key=>$item) {
            $ada_nilai = true;

            if(isset($mhs)) {
                $penguji        = $this->acd->get_status_dosen($item->nim, "Penguji Sidang Komprehensif %");
                $item->penguji  = $penguji;

                foreach($item->penguji as $k1=>$v1) {
                    if(!is_numeric($v1->nilai)) {
                        $item->nilai = ["nilai"=>$v1->nilai,"mutu"=>$v1->mutu,"color"=>$v1->color];
                        $ada_nilai   = false;
                        break;
                    }
                }

                if($ada_nilai) {
                    $nilai = ($presentase_kompre*$kontak[0]->penguji[0]->nilai) + ($presentase_kompre*$kontak[0]->penguji[1]->nilai) +($presentase_kompre*$kontak[0]->penguji[2]->nilai);

                    if($nilai > 100) $nilai = 100;

                    $item->nilai = ["nilai"=>floor($nilai), "mutu"=>$this->acd->_mutu($nilai), "color"=>$this->acd->warna($nilai)];
                }

                break;
            } else {
                $item->nilai = $this->acd->cek_nilai($item->id_status)[0];
            }
        }

        $this->response(isset($mhs) ? $kontak[0] : $kontak, 200);
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

    public function revisi_get() {
        $status = $this->get('id_status');
        $mhs = $this->get('mahasiswa');

        $result = [];

        if(isset($status)) {
            $result =  $this->acd->get_revisi(["t_status.id_status" => status]);
        } elseif(isset($mhs)) {
            $result = $this->acd->get_revisi(["t_mahasiswa.nim" => $mhs]);
        }

        $this->response($result, 200);

    }

    public function input_revisi_post() {
        //INSERT INTO `t_revisi` (`id_revisi`, `id_status`, `detail_revisi`, `tgl_revisi_input`, `tgl_revisi_deadline`, `status`) VALUES (NULL, '392', 'Testing', CURRENT_TIMESTAMP, NULL, '0');
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

    public function input_nilai_post() {
        $id     = $this->post("id_status");
        $nilai  = $this->post("nilai");

        if(!empty($this->_verify())) {
            if(!is_numeric($nilai)) {
                $this->response(["error" => "The value you entered ($nilai) is not a number."], 400);
            } else {
                if($nilai > 0 && $nilai <= 100) {
                    $result = $this->acd->func_input_nilai($id, $nilai);

                    switch($result) {
                        case "ok":
                            $this->response(["info" => "ok"], 200);
                            break;
                        default:
                            $this->response(["code" => $result,
                                            "error" => $this->acd->explain_error($result)], 400);
                    }
                } else {
                    $this->response(["error" => "Please enter 0-100. Value you entered is: $nilai"], 400);
                }
            }
        }
    }

    public function input_nilai_put() {
        $id     = $this->put("id_status");
        $nilai  = $this->put("nilai");

        if(!empty($this->_verify())) {
            if(!is_numeric($nilai)) {
                $this->response(["error" => "The value you entered ($nilai) is not a number."], 400);
            } else {
                if($nilai > 0 && $nilai <= 100) {
                    $result = $this->acd->func_edit_nilai($id, $nilai);

                    switch($result) {
                        case "ok":
                            $this->response(["info" => "ok"], 200);
                            break;
                        default:
                            $this->response(["code" => $result,
                                            "error" => $this->acd->explain_error($result)], 400);
                    }
                } else {
                    $this->response(["error" => "Please enter 0-100. Value you entered is: $nilai"], 400);
                }
            }
        }
    }
}
