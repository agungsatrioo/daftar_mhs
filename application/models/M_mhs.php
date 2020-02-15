<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_mhs extends CI_Model {
    public function get_jurusan() {
            $query = $this->m_query->select(
                                [
                                    "table" => 't_jurusan',
                                ]
                            );

        return $query;
    }

    public function get_mhs($nim = 0) {
        //DEPRECATED FUNCTION; original code is removed, so use mhsfunc() instead.
        return $this->mhsfunc($nim);
    }

    public function mhsfunc($nim = 0) {
    $arr = ["mhs.nim as user_identity","nama_mhs as user_name","mhs.jk","mhs.tempat_lahir", "mhs.tanggal_lahir", "tanggal_masuk", "nama_jurusan", "mhs.kode_jurusan", " CONCAT(dsn.nama_dosen,', ', dsn.gelar) as nama_dospem"];

        $builder = [
                        "table" => 't_status',
                        "fields" => $arr,
                        "conditions" => ["t_status.id_jenis_status"=>15],
                        "joins" => [
                            "t_mahasiswa mhs" => [
                                "on" => ["mhs.nim" => "t_status.nim"]
                            ],
                            "t_dosen dsn" => [
                                "on" => ["dsn.nik" => "t_status.nik"]
                            ],
                            't_jurusan' => [
                                "on" => ["t_jurusan.kode_jurusan"=>"mhs.kode_jurusan",                              "t_jurusan.kode_jurusan"=>"dsn.kode_jurusan"]
                            ],
                            "t_jenis_status jenis" => [
                                "on" => ["jenis.id_jenis_status" => "t_status.id_jenis_status"],
                                "type" => "right"
                            ]
                        ]
                    ];

        if($nim > 0) $builder["conditions"] += ['mhs.nim' => $nim];

        $query = $this->m_query->select($builder);

        return $query;
    }

    public function get_dosen($nik = 0) {
        $fields = ["nik as user_identity, CONCAT(t_dosen.nama_dosen,', ', t_dosen.gelar) as user_name, tempat_lahir, tanggal_lahir, alamat_rumah, nomor_rumah, nomor_telepon, email, nama_jabatan"];

        $builder = [
                        "table" => 't_dosen',
                        "fields" => $fields,
                        "order" => "kode_jabatan",
                        "joins" => [
                            't_jurusan' => [
                                "on" => ["t_jurusan.kode_jurusan"=>"t_dosen.kode_jurusan"]
                            ],
                            't_jabatan' => [
                                "on" => ["t_jabatan.id_jabatan"=>"t_dosen.kode_jabatan"]
                            ]
                        ],
                    ];
        
        if($nik > 0) $builder["conditions"] = ['nik' => $nik];
        
        $query = $this->m_query->select($builder);
        
        return $query;
    }
}
