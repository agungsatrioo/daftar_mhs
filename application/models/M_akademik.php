<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_akademik extends CI_Model {

    public function upemqsh($table, $nik = 0) {
        setlocale(LC_ALL, 'id_id');

        $arr = ["s_sidang.id_status","mhs.nim","nama_mhs","dsn.nik","CONCAT(dsn.nama_dosen,', ', dsn.gelar) as nama_dosen", "judul_$table", "tgl_jadwal_sidang as sidang_date_fmtd", "tgl_jadwal_sidang as sidang_date", "nama_kelompok_sidang", "nilai"];

        /*
select t_status_sidang.id_status, t_mahasiswa.nim, nama_mhs, judul_proposal, id_ruang, tgl_jadwal_sidang, nama_kelompok_sidang, nilai from t_u_proposal
join t_status_sidang on t_status_sidang.id_status = t_u_proposal.id_status_sidang
join t_status on t_status_sidang.id_status = t_status.id_status
join t_sidang on t_status_sidang.id_sidang = t_sidang.id_sidang
join t_mahasiswa on t_status.nim = t_mahasiswa.nim
join t_dosen on t_status.nik = t_dosen.nik
join t_kelompok_sidang on t_sidang.id_kelompok_sidang = t_kelompok_sidang.id_kelompok_sidang
join t_ruangan on t_sidang.id_ruangan = t_ruangan.id_ruang
join t_jadwal_sidang on t_sidang.id_jadwal_sidang = t_jadwal_sidang.id_jadwal_sidang
left join t_nilai on t_status_sidang.id_status = t_nilai.id_status
*/

        $builder = [
                        "table" => "t_u_$table",
                        "conditions" => $nik>0 ? ["dsn.nik" => $nik] : [],
                        "fields" => $arr,
                        "joins" => [
                            "t_status_sidang s_sidang" => [
                                "on" => ["s_sidang.id_status" => "t_u_$table.id_status_sidang"]
                            ],
                            "t_status status" => [
                                "on" => ["s_sidang.id_status" => "status.id_status"]
                            ],
                            "t_sidang sidang" => [
                                "on" => ["s_sidang.id_sidang" => "sidang.id_sidang"]
                            ],
                            "t_mahasiswa mhs" => [
                                "on" => ["status.nim" => "mhs.nim"]
                            ],
                            "t_dosen dsn" => [
                                "on" => ["status.nik" => "dsn.nik"]
                            ],
                            "t_kelompok_sidang kelompok" => [
                                "on" => ["sidang.id_kelompok_sidang" => "kelompok.id_kelompok_sidang"]
                            ],
                            "t_ruangan ruangan" => [
                                "on" => ["sidang.id_ruangan" => "ruangan.id_ruang"]
                            ],
                            "t_jadwal_sidang jadwal" => [
                                "on" => ["sidang.id_jadwal_sidang" => "jadwal.id_jadwal_sidang"]
                            ],
                            "t_nilai nilai" => [
                                "on" => ["s_sidang.id_status" => "nilai.id_status"],
                                "type" => "left"
                            ],
                        ]
                    ];

        $query = $this->m_query->select($builder);

        foreach($query as $it) {
            $str = strftime("%d %B %Y", strtotime($it->sidang_date_fmtd));
            $it->sidang_date_fmtd = $str;
        }


        return $query;
    }
}
