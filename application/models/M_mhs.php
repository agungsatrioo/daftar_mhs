<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_mhs extends CI_Model {
    public function test() {
            $query = $this->m_query->select(
                                [
                                    "table" => 't_mahasiswa',
                                    "fields" => "*",
                                    "joins" => [
                                        't_jurusan' => [
                                            "on" => ["t_jurusan.kode_jurusan"=>"t_mahasiswa.kode_jurusan"]
                                        ]
                                    ],
                                ]
                            );
        
        return $query;
    }
    
    public function get_mhs($nim = 0) {
        $builder = [
                        "table" => 't_mahasiswa',
                        "fields" => "*",
                        "joins" => [
                            't_jurusan' => [
                                "on" => ["t_jurusan.kode_jurusan"=>"t_mahasiswa.kode_jurusan"]
                            ]
                        ],
                    ];
        
        if($nim > 0) $builder["conditions"] = ['nim' => $nim];
        
        $query = $this->m_query->select($builder);
        
        return $query;
    }
}