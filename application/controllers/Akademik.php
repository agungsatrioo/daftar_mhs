<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Akademik extends MY_Controller {
    public function __construct() {
        parent::__construct();
        
        $this->load->model(["M_mhs"=>"mhs"]);
        $this->load->library('table');
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
        
        $table->set_heading(["NIM", "Nama", "Jenis Kelamin", "Tanggal Lahir", "Jurusan"]);
        
        $data = $this->mhs->test();
    
        
        $row = [];
        
        foreach($data as $key=>$val) {
            $row[][] = [$val->nim, $val->nama, ($val->jk == "P" ? "Pria" : "Wanita") ,$val->tanggal_lahir, $val->nama_jurusan];
        }
        
        $data['daftar'] = $table->generate($row);
        $this->load->view('mahasiswa/v_daftar_mhs', $data);
    }
}