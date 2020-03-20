<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_dosen extends CI_Model {
    public function get_dosen($nik = 0, $kode_jur = 0) {
        $fields = ["id_dosen as user_identity,  CONCAT(t_dosen.nama_dosen, '', IFNULL(t_dosen.gelar_depan, '')) as user_name, nip, nidn, IFNULL(nik, '') as nik"];

        $builder = [
                        "table" => 't_dosen',
                        "fields" => $fields,
                        "order" => "t_dosen.id_jabatan",
                        "joins" => [
                            't_jurusan' => [
                                "on" => ["t_jurusan.kode_jur"=>"t_dosen.kode_jur"]
                            ],
                            't_jabatan' => [
                                "on" => ["t_jabatan.id_jabatan"=>"t_dosen.id_jabatan"]
                            ]
                        ],
                    ];

        $builder["conditions"] = [];

        if($nik > 0) $builder["conditions"] += ['id_dosen' => $nik];
        if($kode_jur > 0) $builder["conditions"] += ['t_dosen.kode_jur' => $kode_jur];

        $query = $this->m_query->select($builder);


        return $query;
    }
}
