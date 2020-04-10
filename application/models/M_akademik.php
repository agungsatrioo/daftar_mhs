<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_akademik extends CI_Model {

    public function _mutu($n) {
        if($n > 78 && $n <=100) {
            return "A";
        } elseif($n > 67 && $n >= 78) {
            return "B";
        } elseif($n > 56 && $n >= 67) {
            return "C";
        } elseif($n > 41 && $n >= 56) {
            return "D";
        } elseif($n > 0 && $n >= 41) {
            return "E";
        } else {
            return "null";
        }
    }

    public function warna($n) {
        if($n > 78 && $n <=100) {
            return "#4CAF50";
        } elseif($n > 67 && $n >= 78) {
            return "#8BC34A";
        } elseif($n > 56 && $n >= 67) {
            return "#FF9800";
        } elseif($n > 41 && $n >= 56) {
            return "#FF5722";
        } elseif($n > 0 && $n >= 41) {
            return "#F44336";
        } else {
            return "#000000";
        }
    }

    public function explain_error($code) {
        switch($code) {
            case 1062: return "Data yang Anda masukkan sudah ada sebelumnya.";
            case 1053: return "Server sedang dimatikan.";
            default: return "Kesalahan yang tidak diketahui. Silakan hubungi administrator.";
        }
    }

    public function get_kompre($id_dosen = 0, $nim = 0) {
        setlocale(LC_ALL, 'id_id');

        $arr = ["s_sidang.id_status","mhs.nim","nama_mhs", "tgl_jadwal_sidang as sidang_date_fmtd", "tgl_jadwal_sidang as sidang_date", "ruangan.kode_ruang", "nama_kelompok_sidang", "nilai"];

        $builder = [
                        "table" => "t_u_kompre",
                        "fields" => $arr,
                        "order" => "sidang_date",
                        "joins" => [
                            "t_status_sidang s_sidang" => [
                                "on" => ["s_sidang.id_status" => "t_u_kompre.id_status_sidang"]
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
                                "on" => ["status.id_dosen" => "dsn.id_dosen"]
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

        if($id_dosen > 0) {
            $builder["conditions"] = ["dsn.id_dosen" => $id_dosen];
        } elseif($nim > 0) {
            $builder["conditions"] = ["mhs.nim" => $nim];
        }

        $query = $this->m_query->select($builder);


        foreach($query as $it) {
            $it->keterangan_sidang = $it->nilai != null ?  "Sidang sudah dinilai" : "Belum sidang";
            if($id_dosen > 0) {
                $it->keterangan_sidang = $it->nilai != null ?  "Sidang sudah dinilai" : "Belum sidang";
            } elseif($nim > 0) {
                //unset($it->id_status);
            }

            $str = strftime("%d %B %Y", strtotime($it->sidang_date_fmtd));
            $it->sidang_date_fmtd = $str;
        }


        return $query;
    }

    public function get_up_munaqosah($table, $id_dosen = 0, $nim = 0) {
        setlocale(LC_ALL, 'id_id');
        $cond = [];

        $arr = ["s_sidang.id_status","mhs.nim","nama_mhs", "judul_$table", "tgl_jadwal_sidang as sidang_date_fmtd", "tgl_jadwal_sidang as sidang_date", "ruangan.kode_ruang", "nama_kelompok_sidang", "nilai"];


        $builder = [
                        "table" => "t_u_$table",
                        "fields" => $arr,
                        "order" => "sidang_date",
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
                                "on" => ["status.id_dosen" => "dsn.id_dosen"]
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

        if($id_dosen > 0) {
            $builder["conditions"] = ["dsn.id_dosen" => $id_dosen];
        } elseif($nim > 0) {
            $builder["conditions"] = ["mhs.nim" => $nim];
        }

        $query = $this->m_query->select($builder);

        foreach($query as $it) {
            $it->keterangan_sidang = $it->nilai != null ?  "Sidang sudah dinilai" : "Belum sidang";

            if($id_dosen > 0) {
                $it->keterangan_sidang = $it->nilai != null ?  "Sidang sudah dinilai" : "Belum sidang";
            } elseif($nim > 0) {
                //unset($it->id_status);
            }

            $str = strftime("%d %B %Y", strtotime($it->sidang_date_fmtd));
            $it->sidang_date_fmtd = $str;
        }


        return $query;
    }

    public function get_revisi($conditions) {
        $builder = [
            "table"     => "t_revisi",
            "fields"    => "id_revisi, t_mahasiswa.nim, t_dosen.id_dosen, t_mahasiswa.nama_mhs, CONCAT(t_dosen.nama_dosen, ', ', IFNULL(t_dosen.gelar_depan, '')) as nama_dosen, t_jenis_status.nama_status, t_revisi.id_status, detail_revisi, tgl_revisi_input, tgl_revisi_deadline, status as status_revisi",
            "conditions"=> $conditions,
            "joins"     => [
                                "t_status" => [
                                    "on" => ["t_revisi.id_status" => "t_status.id_status"]
                                ],
                                "t_dosen" => [
                                    "on" => ["t_status.id_dosen" => "t_dosen.id_dosen"]
                                ],
                                "t_mahasiswa" => [
                                    "on" => ["t_status.nim" => "t_mahasiswa.nim"]
                                ],
                                "t_jenis_status" => [
                                    "on" => ["t_status.id_jenis_status" => "t_jenis_status.id_jenis_status"]
                                ],

                            ]
        ];

        $query = $this->m_query->select($builder);

        foreach($query as $item) {
            $item->status_revisi = $item->status_revisi > 0 ? true : false;
        }

        return $query;
    }

    public function cek_id_status($id) {
        $builder = [
            "table"     => "t_status",
            "fields"    => "id_status",
            "conditions"=> ["id_status" => $id],

        ];

        $query = $this->m_query->select($builder);

        if(count($query) > 0) return true;
        else return false;
    }

    public function cek_nilai($id) {
        $builder = [
            "table"     => "t_status",
            "fields"    => "t_status.id_status, t_status.id_dosen, CONCAT(t_dosen.nama_dosen, '', IFNULL(t_dosen.gelar_depan, '')) as nama_dosen, nama_status, IFNULL(nilai, 'Belum ada') as nilai, IFNULL(mutu, 'Belum ada') as mutu",
            "joins"     => [
                "t_jenis_status" => [
                    "on" => ["t_jenis_status.id_jenis_status"=>"t_status.id_jenis_status"]
                ],
                "t_dosen" => [
                    "on" => ["t_dosen.id_dosen"=>"t_status.id_dosen"]
                ],
                "t_nilai" => [
                    "on" => ["t_nilai.id_status"=>"t_status.id_status"],
                    "type" => "left"
                ],
            ],
            "conditions"=>  ["t_status.id_status" => $id]
        ];

        $query = $this->m_query->select($builder);

        foreach($query as $item) {
            $item->color = $this->warna($item->nilai);

            $item->revisi = $this->get_revisi(["t_status.id_status" => $item->id_status]);
        }

        return $query;
    }

    public function get_status_dosen($nim, $jenis_status) {
        $builder = [
            "table"     => "t_status",
            "fields"    => "t_status.id_status, t_status.id_dosen, CONCAT(t_dosen.nama_dosen, '', IFNULL(t_dosen.gelar_depan, '')) as nama_dosen, nama_status, IFNULL(nilai, 'Belum ada') as nilai, IFNULL(mutu, 'Belum ada') as mutu",
            "joins"     => [
                "t_jenis_status" => [
                    "on" => ["t_jenis_status.id_jenis_status"=>"t_status.id_jenis_status"]
                ],
                "t_dosen" => [
                    "on" => ["t_dosen.id_dosen"=>"t_status.id_dosen"]
                ],
                "t_nilai" => [
                    "on" => ["t_nilai.id_status"=>"t_status.id_status"],
                    "type" => "left"
                ],
            ],
            "conditions"=> ["t_status.nim" => $nim, "nama_status like" => $jenis_status]
        ];

        $query = $this->m_query->select($builder);

        foreach($query as $item) {
            $item->color = $this->warna($item->nilai);
            $item->revisi = $this->get_revisi(["t_status.id_status" => $item->id_status]);
        }

        return $query;
    }


    public function func_input_nilai($status, $nilai) {
        if($this->cek_id_status($status)) {
            $a = $this->m_query->insert(
                "t_nilai",
                [
                    "id_status" => $status,
                    "nilai"     => $nilai,
                    "mutu"      => $this->_mutu($nilai)
                ],
                TRUE
            );

            if(key_exists("id", $a)) {
                return "ok";
            } else {
                return $a['code'];
            }
        } else return "400";
    }

    public function func_edit_nilai($status, $nilai) {
        if($this->cek_id_status($status)) {
            $a = $this->m_query->update(
                "t_nilai",
                [
                    "id_status" => $status,
                ],
                [
                    "nilai"     => $nilai,
                    "mutu"      => $this->_mutu($nilai)
                ],
                false
            );

            if(!is_array($a)) {
                return "ok";
            } else {
                return $a['code'];
            }
        } else return "400";
    }

    public function func_tambah_revisi($id_status, $detail_revisi, $deadline = "NULL", $status = false) {
        if($this->cek_id_status($id_status)) {
            $a = $this->m_query->insert(
                "t_revisi",
                [
                    "id_status"             => $id_status,
                    "detail_revisi"         => $detail_revisi,
                    "tgl_revisi_deadline"   => $deadline,
                    "status"                => $status ? 1 : 0
                ],
                true
            );

            if(key_exists("id", $a)) {
                return "ok";
            } else {
                return $a['code'];
            }
        } else return "400";
    }

    public function func_edit_revisi($id_revisi, $id_status, $detail_revisi, $deadline = "NULL", $status = false) {
        if($this->cek_id_status($id_status)) {
            $a = $this->m_query->update(
                "t_revisi",
                [
                    "id_revisi" => $id_revisi,
                ],
                [
                    "detail_revisi"         => $detail_revisi,
                    "tgl_revisi_edit"       => date('m/d/Y h:i:s a', time()),
                    "tgl_revisi_deadline"   => $deadline,
                    "status"                => $status ? 1 : 0
                ],
                false
            );

            if(!is_array($a)) {
                return "ok";
            } else {
                return $a['code'];
            }
        } else return "400";
    }

    public function func_delete_revisi($id_revisi, $id_status) {
        if($this->cek_id_status($id_status)) {
            if($id_revisi == null) return "401";

            $a = $this->m_query->delete(
                "t_revisi",
                [
                    "id_revisi" => $id_revisi,
                ],
                false
            );

            if(!is_array($a)) {
                return "ok";
            } else {
                return $a['code'];
            }
        } else return "400";
    }

    public function func_mark_revisi($id_revisi, $id_status, $status = false) {
        if($this->cek_id_status($id_status)) {
            $a = $this->m_query->update(
                "t_revisi",
                [
                    "id_revisi" => $id_revisi,
                ],
                [
                    "status"    => $status ? 1 : 0
                ],
                false
            );

            if(!is_array($a)) {
                return "ok";
            } else {
                return $a['code'];
            }
        } else return "400";
    }
}
