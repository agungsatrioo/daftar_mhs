<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Akademik extends MY_Controller {

    public function __construct() {
        parent::__construct();
        
        $this->load->model(["M_mhs"=>"mhs"]);
        $this->load->library('table');
    }
    
    private function alterable() {
        return false;
    }

    public function index() {
        $table = $this->table;
        
        $template = array(
        'table_open'            => '<table id="mahasiswa" class="table table-striped">',

        'thead_open'            => '<thead>',
        'thead_close'           => '</thead>',

        'heading_row_start'     => '<tr>',
        'heading_row_end'       => '</tr>',
        'heading_cell_start'    => '<th>',
        'heading_cell_end'      => '</th>',

        'tbody_open'            => '<tbody>',
        'tbody_close'           => '</tbody>',

        'row_start'             => '<tr>',
        'row_end'               => '</tr>',
        'cell_start'            => '<td>',
        'cell_end'              => '</td>',

        'row_alt_start'         => '<tr>',
        'row_alt_end'           => '</tr>',
        'cell_alt_start'        => '<td>',
        'cell_alt_end'          => '</td>',

        'table_close'           => '</table>'
        );
        
        $table->set_template($template);
        
        $table->set_heading(["NIM", "Nama", "Jenis Kelamin", "Tanggal Lahir", "Jurusan", "Aksi"]);
        
        $data = $this->mhs->test();
    
        
        $row = [];
        
        foreach($data as $key=>$val) {
            $button  = ["<a href='".base_url("akademik/form/{$val->nim}")."' class='btn btn-success m-2'>Sunting</a>","<a href='".base_url("akademik/delete/{$val->nim}")."' class='btn btn-danger m-2 btn-delete'>Hapus</a>"];
            $row[][] = [$val->nim, $val->nama, ($val->jk == "P" ? "Pria" : "Wanita") ,$val->tanggal_lahir, $val->nama_jurusan,
                       "{$button[0]} {$button[1]}"
                       ];
        }
        
        $data['daftar'] = $table->generate($row);
        $data['prefix'] = "akademik";

        $error          = $this->session->flashdata('error');
        $success        = $this->session->flashdata('success');

        if($success != null) {
            $data['alert'] = "<div class='alert alert-success'>".$this->session->flashdata('success')."</div>";
        }elseif($error != null) {
            $data['alert'] = "<div class='alert alert-danger'>".$this->session->flashdata('error')."</div>";
        }

        $this->load->view('mahasiswa/v_daftar_mhs', $data);
    }

    public function form($id="") {
        $data = [];
        $jurusan = $this->mhs->get_jurusan();
        $data['jurusan'] = "<option disabled>-Pilih jurusan-</option>";

        if(!empty($id)) {
            $kontak         = $this->mhs->get_mhs($id)[0];

            $data['title']  = "Sunting Mahasiswa";
            $data['nim']    = $kontak->nim;
            $data['nama']    = $kontak->nama;
            $data['jk']    = $kontak->jk;
            $data['tempat_lahir']    = $kontak->tempat_lahir;
            $data['tanggal_lahir']    = $kontak->tanggal_lahir;
            $data['tanggal_masuk']    = $kontak->tanggal_masuk;

            foreach($jurusan as $item) {
                $data['jurusan'] .= "<option value=".$item->kode_jurusan." ".($kontak->kode_jurusan == $item->kode_jurusan ? "selected" : "").">{$item->nama_jurusan}</option>";
            }
            $data['open_form'] = form_open(base_url("akademik/update/{$kontak->nim}"));

        } else {
            $data['title'] = "Tambah Mahasiswa";
            $data['jurusan'] = "<option disabled selected>-Pilih jurusan-</option>";

            foreach($jurusan as $item) {
                $data['jurusan'] .= "<option value=".$item->kode_jurusan.">{$item->nama_jurusan}</option>";
            }

            $data['open_form'] = form_open(base_url("akademik/add"));
        }

        $data['form_close'] = form_close();

        $this->load->view('mahasiswa/v_form_mahasiswa', $data);
    }

    public function update($id) {
        $post = $this->input->post();
        $ret = $this->alterable() ? $this->db->update("t_mahasiswa", $post, ["nim" => $id]) : true;

        if($ret) $this->session->set_flashdata('success', "Data mahasiswa telah disunting.");
        else $this->session->set_flashdata('error', "Data mahasiswa gagal disunting.");

        redirect(base_url());
    }

    public function delete($id) {
        $ret = $this->alterable() ? $this->db->delete("t_mahasiswa", ["nim" => $id]) : true;

        if($ret) $this->session->set_flashdata('success', "Data mahasiswa telah dihapus.");
        else $this->session->set_flashdata('error', "Data mahasiswa gagal dihapus.");

        redirect(base_url());
    }

    public function add() {
        redirect(base_url());

        $post = $this->input->post();
        $ret = $this->alterable() ? $this->db->insert("t_mahasiswa", $post) : true;

        if($ret) $this->session->set_flashdata('success', "Data mahasiswa telah ditambahkan.");
        else $this->session->set_flashdata('error', "Data mahasiswa gagal ditambahkan.");
    }

    public function querydmp() {
        echo "INSERT INTO t_status_sidang VALUES<BR>";

                /*
        select id_jenis_status, min(id_status), max(id_status)
from t_status
GROUP by id_jenis_status

        1 	1 	    128 //Pembimbing akademik

        3 	129 	256 //mq1p
        4 	257 	384 //mq2p

        6 	385 	512 //up1
        7 	513 	640 //up2

        8 	641 	768 //p1
        9 	769 	896 //p2
        10 	897 	1024 //m3

        11 	1025 	1152 //mq1u
        12 	1153 	1280 //mq2u

        */

        $a = 897;
        $b = 1024;

        $j = 50;

        for($i=$a; $i<=$b; $i++) {
            if($i%3==0) $j++;
            echo "($i, $j),<br>";
        }
    }

        public function upe() {
        echo "INSERT INTO t_u_kompre VALUES<BR>";

                /*
        select id_jenis_status, min(id_status), max(id_status)
from t_status
GROUP by id_jenis_status

        1 	1 	    128 //Pembimbing akademik

        3 	129 	256 //mq1p
        4 	257 	384 //mq2p

        6 	385 	512 //up1
        7 	513 	640 //up2

        8 	641 	768 //kmpre1
        9 	769 	896 //kompre2
        10 	897 	1024 //kompre3

        11 	1025 	1152 //mq1u
        12 	1153 	1280 //mq2u

        */

            $a = 641;
            $b = 768;

            $oa = [[641,768],[769,896],[897,1024]];
            $arr = [];

            for($i=$a; $i<=$b; $i++) {
                $m = rand(1, 12);
                $d = rand(1, 28);
                $arr[] = "2020-$m-$d";
            }

            foreach($oa as $bb) {
                $o = 0;
                for($i=$bb[0]; $i<=$bb[1]; $i++) {
                    echo "(NULL, $i, '{$arr[$o]}'),<br>";
                    $o++;
                }
            }
    }

    public function mya() {
        /*
        1 	1 	    128 //Pembimbing akademik

        3 	129 	256 //mq1p 1
        4 	257 	384 //mq2p

        6 	385 	512 //up1 10
        7 	513 	640 //up2 1

        8 	641 	768 //kmpre1 20
        9 	769 	896 //kompre2
        10 	897 	1024 //kompre3

        11 	1025 	1152 //mq1u 1
        12 	1153 	1280 //mq2u
        */

        $ganti = 3;
        $mula = 0;
        $awal = 1;
        echo "INSERT INTO t_status_sidang VALUES<BR>";

        for($i=1153; $i<=1280; $i++) {
            if($mula % $ganti == 0) {
                $mula = 0;
                $awal++;
            }
            echo "($i, $awal),<br>";
            $mula++;
        }
    }

    public function t_st_sidang() {
        echo "INSERT INTO t_u_munaqosah VALUES<BR>";
    }


    public function myz() {
        echo "INSERT INTO t_u_munaqosah VALUES<BR>";

        $j=0;

        for($i=129; $i<=256; $i++) {
            $j++;
            echo "(NULL, $i, 'Munaqosah $j'),<br>";
        }
         $j=0;

        for($i=257; $i<=384; $i++) {
            $j++;
            echo "(NULL, $i, 'Munaqosah $j'),<br>";
        }
         $j=0;

        for($i=1025; $i<=1152; $i++) {
            $j++;
            echo "(NULL, $i, 'Munaqosah $j'),<br>";
        }
         $j=0;

        for($i=1153; $i<=1280; $i++) {
            $j++;
            echo "(NULL, $i, 'Munaqosah $j'),<br>";
        }
    }

    public function myc() {
        echo "INSERT INTO t_u_proposal VALUES<BR>";
        $j=0;

        for($i=385; $i<=512; $i++) {
            $j++;
            echo "(NULL, $i, 'Proposal $j'),<br>";
        }

        $j =0;

        for($i=513; $i<=640; $i++) {
            $j++;
            echo "(NULL, $i, 'Proposal $j'),<br>";
        }
    }

    public function myq() {

        /*
        select id_jenis_status, min(id_status), max(id_status)
from t_status
GROUP by id_jenis_status

        1 	1 	    128 //Pembimbing akademik

        3 	129 	256 //mq1p
        4 	257 	384 //mq2p

        6 	385 	512 //up1
        7 	513 	640 //up2

        8 	641 	768 //p1
        9 	769 	896 //p2
        10 	897 	1024 //m3

        11 	1025 	1152 //mq1u
        12 	1153 	1280 //mq2u

        */

        echo "INSERT INTO t_status VALUES<BR>";
        $j = 0;
        $utype = [1, 3, 4, 6, 7, 8, 9, 10, 11, 12];
        $q = 0;

        $limit = 3;

        foreach ($utype as $type) {
            $mhs = $this->mhs->get_mhs(0);
            $dsn = $this->mhs->get_dosen(0,55201);

            foreach($mhs as $it) {
                if($type == 6 || $utype ==7) {
                    if($j%$limit==0) {
                        $r = array_rand($dsn);
                        $q = $dsn[$r];
                    }
                } else {
                    if($j%$limit==0) {
                        $r = array_rand($dsn);
                        $q = $dsn[$r];
                    }
                }

                $j++;
                echo "(NULL, $type, {$it->user_identity}, {$q->user_identity}),<br>";
                unset($dsn[$r]);
            }
        }


    }

    public function sidang() {
        echo "INSERT INTO t_sidang VALUES<BR>";

        $ruangan = [2,3,4];
        $jadwal = 0;
        $kelompok = 0;
        $d = -1;

        for($i=0; $i<=100; $i++) {
            $d++;
            if($jadwal > 10) $jadwal = 0;
            if($kelompok > 14) $kelompok = 0;
            if($d > 2) $d = 0;
            $jadwal++;
            $kelompok++;

            echo "(NULL, {$jadwal}, {$kelompok}, {$ruangan[$d]}),<br>";
        }

    }

    public function mhsku() {
        $mhs = $this->mhs->mhsfunc();

        echo "<pre>";
        print_r($mhs);

        /*
        foreach($mhs as $it) {
            echo "Mahasiswa: {$it->nim}, <br>Dospem: <hr>";
        }*/
    }

    public function bimbingan() {

    }

    public function generate_login() {
                $builder = [
            "table"     => "t_dosen",
            "fields"    => "id_dosen",
        ];

        $query = $this->m_query->select($builder);

        foreach($query as $item) {
            echo "({$item->id_dosen}, '".password_hash("dosen", PASSWORD_DEFAULT) . "',0,NULL,0,5,0), <BR>";
        }
    }

}
