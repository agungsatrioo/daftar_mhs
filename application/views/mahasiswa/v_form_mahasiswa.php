<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!DOCTYPE html>
<html>
    <head>
        <title><?= @$title ?></title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">
    </head>

    <body>
        <div class="wrapper">
            <div class="container-fluid">
                <h1 class="text-center"><?= @$title ?></h1>
                 <?= $open_form ?>
                    <div class="form-group">
                        <label for="email">NIM</label>
                        <input type="text" class="form-control" placeholder="Masukkan NIM Anda" name="nim" value="<?= @$nim ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="pwd">Nama</label>
                        <input type="text" class="form-control" placeholder="Masukkan nama Anda" name="nama" value="<?= @$nama ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="pwd">Jenis Kelamin</label>
                        <select class="form-control" name="jk" required>
                            <option value="P" <?= @$jk == "P" ? "selected" : "" ?>>Pria</option>
                            <option value="W" <?= @$jk == "W" ? "selected" : "" ?>>Wanita</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="pwd">Tempat Lahir</label>
                        <input type="text" class="form-control" placeholder="Masukkan tempat lahir Anda" name="tempat_lahir" value="<?= @$tempat_lahir ?>" required>
                    </div>

                     <div class="form-group">
                        <label for="pwd">Tanggal Lahir</label>
                        <input type="date" class="form-control" placeholder="Masukkan tanggal lahir Anda" name="tanggal_lahir" value="<?= @$tanggal_lahir ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="pwd">Tanggal Masuk</label>
                        <input type="date" class="form-control" placeholder="Masukkan tanggal lahir Anda" name="tanggal_masuk" value="<?= @$tanggal_masuk ?>" required >
                    </div>

                    <div class="form-group">
                        <label for="pwd" >Jurusan</label>
                        <select class="form-control" name="kode_jurusan" required>
                            <?= $jurusan ?>
                        </select>
                    </div>

                     <input type="hidden" name="nik_dospem" value="1">

                  <button type="submit" class="btn btn-primary">Submit</button>
                <?= $form_close ?>"
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>

        <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>

        <script>
        </script>
    </body>
</html>
