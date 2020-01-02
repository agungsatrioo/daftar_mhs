<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DariApi extends MY_Controller {

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

        $url = base_url("apimhs");
        $json = file_get_contents($url);
        $json = json_decode($json);

        $row = [];

        foreach($json as $key=>$val) {
            $button  = ["<a href='".base_url("dariapi/form/{$val->nim}")."' class='btn btn-success m-2'>Sunting</a>","<a href='".base_url("dariapi/delete/{$val->nim}")."' class='btn btn-danger m-2 btn-delete'>Hapus</a>"];
            $row[][] = [$val->nim, $val->nama, ($val->jk == "P" ? "Pria" : "Wanita") ,$val->tanggal_lahir, $val->nama_jurusan,
                       "{$button[0]} {$button[1]}"
                       ];
        }

        $data['daftar'] = $table->generate($row);
        $data['prefix'] = "dariapi";

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
            $data['open_form'] = form_open(base_url("dariapi/update/{$kontak->nim}"));

        } else {
            $data['title'] = "Tambah Mahasiswa";
            $data['jurusan'] = "<option disabled selected>-Pilih jurusan-</option>";

            foreach($jurusan as $item) {
                $data['jurusan'] .= "<option value=".$item->kode_jurusan.">{$item->nama_jurusan}</option>";
            }

            $data['open_form'] = form_open(base_url("dariapi/add"));
        }

        $data['form_close'] = form_close();

        $this->load->view('mahasiswa/v_form_mahasiswa', $data);
    }

    public function update($id) {
        /*$post = $this->input->post();
        $ret = $this->alterable() ? $this->db->update("t_mahasiswa", $post, ["nim" => $id]) : true;

        if($ret) $this->session->set_flashdata('success', "Data mahasiswa telah disunting.");
        else $this->session->set_flashdata('error', "Data mahasiswa gagal disunting.");

        redirect(base_url());
        */
        $post = $this->input->post();

        $ch = curl_init(base_url("apimhs"));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json')
        );

        $result = curl_exec($ch);
        $_ret = json_decode($result);
        $ret = $_ret->status;

        if($ret=="success") $this->session->set_flashdata('success', "Data mahasiswa telah disunting.");
        else $this->session->set_flashdata('error', "Data mahasiswa gagal disunting.");

        redirect(base_url("dariapi"));
    }

    public function delete($id) {
        /*$post = ["nim"=>$id];

        $ch = curl_init(base_url("apimhs"));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json')
        );

        $result = curl_exec($ch);
        $_ret = json_decode($result);

        echo $result;

        $ret = $_ret->status;

        if($ret=="success") $this->session->set_flashdata('success', "Data mahasiswa telah hapus.");
        else $this->session->set_flashdata('error', "Data mahasiswa gagal dihapus.");*/

        //redirect(base_url("dariapi"));

        $ret = $this->alterable() ? $this->db->delete("t_mahasiswa", ["nim" => $id]) : true;

        if($ret) $this->session->set_flashdata('success', "Data mahasiswa telah dihapus.");
        else $this->session->set_flashdata('error', "Data mahasiswa gagal dihapus.");

        redirect(base_url());
    }

    public function add() {
        $post = $this->input->post();

        $ch = curl_init(base_url("apimhs"));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json')
        );

        $result = curl_exec($ch);
        $_ret = json_decode($result);

        $ret = $_ret->status;

        if($ret=="success") $this->session->set_flashdata('success', "Data mahasiswa telah ditambahkan.");
        else $this->session->set_flashdata('error', "Data mahasiswa gagal ditambahkan.");

        //redirect(base_url("dariapi"));
    }


}
