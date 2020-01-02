<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Daftar Mahasiswa</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">
    </head>
    
    <body>
        <div class="wrapper">
            <div class="container-fluid">
                <h1 class="text-center">Daftar Mahasiswa</h1>

                <?= @$alert ?>

                <a href="<?= base_url("{$prefix}/form")?>" class="btn btn-primary m-2">Tambah</a>
                <?= @$daftar ?>
            </div>
        </div>
        
        <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
        
        <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.4.0/bootbox.min.js"></script>

        
        <script>
        $(document).ready(function() {
            $('#mahasiswa').DataTable();
            $('.btn-delete').click(function(e) {
               e.preventDefault();
                var empid = $(this).attr('href');
                bootbox.dialog({
                    message: "Anda yakin untuk menghapus ini?",
                    title: "Konfirmasi",
                    buttons: {
                        success: {
                            label: "Tidak",
                            className: "btn-success",
                            callback: function() {
                                $('.bootbox').modal('hide');
                            }
                        },
                        danger: {
                            label: "Ya",
                            className: "btn-danger",
                                callback: function() {
                                   window.location = empid;
                            }
                        }
                    }
                });
            });
        });
        </script>
    </body>
</html>
