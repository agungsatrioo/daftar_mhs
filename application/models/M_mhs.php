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
    
    public function get_jurusan() {
            $query = $this->m_query->select(
                                [
                                    "table" => 't_jurusan',
                                ]
                            );

        return $query;
    }

    public function get_mhs($nim = 0) {
        $arr = ["nim","nama","t_mahasiswa.jk","t_mahasiswa.tempat_lahir", "t_mahasiswa.tanggal_lahir", "tanggal_masuk", "nama_jurusan", "dospem.nama_dosen as nama_dospem", "t_mahasiswa.kode_jurusan"];
        $builder = [
                        "table" => 't_mahasiswa',
                        "fields" => $arr,
                        "joins" => [
                            't_jurusan' => [
                                "on" => ["t_jurusan.kode_jurusan"=>"t_mahasiswa.kode_jurusan"]
                            ],
                            't_dosen dospem' => [
                                "on" => ["dospem.nik"=>"t_mahasiswa.nik_dospem"]
                            ]
                        ],
                    ];
        
        if($nim > 0) $builder["conditions"] = ['nim' => $nim];
        
        $query = $this->m_query->select($builder);
        
        return $query;
    }
}
