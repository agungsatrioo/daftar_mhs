<?php defined('BASEPATH') OR exit('No direct script access allowed');

//Adapted from Ion Auth

class M_user extends CI_Model {

	public function __construct() {
        parent::__construct();
		$this->check_compatibility();

		$this->load->library(['session']);
		$this->load->helper(['cookie', 'url', 'security']);
	}

    protected function check_compatibility() {
		// PHP password_* function sanity check
		if (!function_exists('password_hash') || !function_exists('password_verify'))
		{
			show_error("PHP function password_hash or password_verify not found. " .
				"Are you using CI 2 and PHP < 5.5? " .
				"Please upgrade to CI 3, or PHP >= 5.5 " .
				"or use password_compat (https://github.com/ircmaxell/password_compat).");
		}

		// Sanity check for CI2
		if (substr(CI_VERSION, 0, 1) === '2')
		{
			show_error("This module requires CodeIgniter 3. Update to CI 3 or downgrade to Ion Auth 2.");
		}

		// Compatibility check for CSPRNG
		// See functions used in Ion_auth_model::_random_token()
		if (!function_exists('random_bytes') && !function_exists('mcrypt_create_iv') && !function_exists('openssl_random_pseudo_bytes'))
		{
			show_error("No CSPRNG functions to generate random enough token. " .
				"Please update to PHP 7 or use random_compat (https://github.com/paragonie/random_compat).");
		}
	}

    function change_key( $array, $old_key, $new_key ) {
        if( ! array_key_exists( $old_key, $array ) )
            return $array;

        $keys = array_keys( $array );
        $keys[ array_search( $old_key, $keys ) ] = $new_key;

        return array_combine( $keys, $array );
    }

    public function user() {
        if($this->session->has_userdata("siflab_unique_code")&&$this->session->has_userdata("siflab_user_level")) {
            return $this->session;
        } else {
            return null;
        }
    }

    public function get_user_name() {
        if($this->user()!=null) return $this->session->siflab_mhs_name;
        else return null;
    }

    public function get_last_login() {
        if($this->user()!=null) return $this->session->siflab_last_login;
        else return null;
    }

    public function get_jurusan() {
        if($this->user()!=null && $this->session->has_userdata("siflab_mhs_nim")) return $this->session->siflab_kode_jurusan;
        else return null;
    }

    protected function get_usergroup($level) {
        return $this->m_query->select(
                        array(
                            'table' => 't_grup_pengguna',
                            'conditions' => array(
                                'id' => $level,
                            )
                        )
                    )[0];
    }

    public function logout() {
        //$this->session->unset_userdata(array("siflab_user_level","siflab_mhs_nim","siflab_mhs_name","siflab_unique_code","siflab_last_login","siflab_kode_jurusan"));
        $this->session->sess_destroy();
    }

    public function login_api($input) {
        $identity   = $input->post('identity');
        $password   = $input->post('password');
        $uniqueid   = $this->security->get_csrf_hash();

        $user_db  = $this->m_query->select(
                        array(
                            'table' => 't_pengguna',
                            'conditions' => array(
                                'identity' => $identity,
                            )
                        )
                    );

        if($user_db == null) return ["status"=>"failed","code"=>401,"msg"=>"Pengguna tidak ditemukan."];

        $u_details = $user_db[0];

        if(password_verify($password,$u_details->password)) {
            $u_level    = $this->get_usergroup($u_details->level);
            $jenis      = $u_level->description;

            $newdata = array(
                        "user_level" => $u_level->id,
                        "unique_code" => $uniqueid,
                        "last_login" => date('Y-m-d H:m:s', time())
                    );
            $user_data = array();

            switch($u_level->id) {
                case 5:
                    $this->load->model(["M_mhs"=>"mhs"]);
                    $query = $this->mhs->get_dosen($identity);
                    $user_data = get_object_vars($query[0]);

                    break;
                case 4:
                    $this->load->model(["M_mhs"=>"mhs"]);
                    $query = $this->mhs->get_mhs($identity);
                    $user_data = get_object_vars($query[0]);

                    break;
                default:
                    return false;
            }
            //array_merge($newdata,$user_data)
            return ["status"=>"ok","code"=>200,"data"=>array_merge($newdata,$user_data)];
        } else {
            return ["status"=>"failed","code"=>401,"msg"=>"Kata sandi salah."];
        }
    }


}
