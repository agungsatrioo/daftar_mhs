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

    public function get_mhs_table_mhs($nim = 0, $kode_jur = 0) {
        $arr = ["mhs.nim as user_identity","nama_mhs as user_name","mhs.jk","mhs.tempat_lahir", "mhs.tanggal_lahir", "tanggal_masuk", "nama_jur", "mhs.kode_jurusan"];

        $builder = [
                        "table" => 't_mahasiswa mhs',
                        "fields" => $arr,
                        "joins" => [
                            't_jurusan' => [
                                "on" => ["t_jurusan.kode_jur"=>"mhs.kode_jurusan",]
                            ],
                        ]
                    ];

        $builder["conditions"] = [];

        if($nim > 0) $builder["conditions"] += ['mhs.nim' => $nim];
        if($kode_jur > 0) $builder["conditions"] += ['mhs.kode_jurusan' => $kode_jur];

        $query = $this->m_query->select($builder);

        return $query;
    }

    public function get_mhs($nim = 0, $kode_jur = 0) {
    $arr = ["mhs.nim as user_identity","nama_mhs as user_name","mhs.jk","mhs.tempat_lahir", "mhs.tanggal_lahir", "tanggal_masuk", "nama_jur", "mhs.kode_jurusan", "CONCAT(dsn.nama_dosen, '', IFNULL(dsn.gelar_depan, '')) as pembimbing_akademik"];

        $builder = [
                        "table" => 't_status',
                        "fields" => $arr,
                        "order" => "mhs.nim",
                        "conditions" => ["t_status.id_jenis_status"=>1],
                        "joins" => [
                            "t_mahasiswa mhs" => [
                                "on" => ["mhs.nim" => "t_status.nim"]
                            ],
                            "t_dosen dsn" => [
                                "on" => ["dsn.id_dosen" => "t_status.id_dosen"]
                            ],
                            't_jurusan' => [
                                "on" => ["t_jurusan.kode_jur"=>"mhs.kode_jurusan",                              "t_jurusan.kode_jur"=>"dsn.kode_jur"]
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
}
