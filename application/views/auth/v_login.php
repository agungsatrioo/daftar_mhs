<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<body class="bg-gradient-primary">
  <div class="container">
    <div class="row justify-content-center">
        <div class="col-xl-4 col-lg-4 col-md-3">

        <div class="card o-hidden border-0 shadow-lg my-5">
          <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
              <div class="col-lg-12">
                <div class="p-5">
                    <?php if(!empty($error)) { ?>
                        <div class="alert alert-danger"><?= $error ?></div>
                    <?php } ?>


                    <?php if(!empty($identity)) { ?>
                        <div class="alert alert-success"><?= $identity ?></div>
                    <?php } ?>
                  <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-4">SIFLAB</h1>
                  </div>
                  <?= @$form_open ?>
                    <div class="form-group">
                      <input type="text" name="inputNI" id="inputNI" class="form-control form-control-user"  placeholder="Identitas">
                    </div>
                    <div class="form-group">
                      <input type="password" name="inputPassword" id="inputPassword" class="form-control form-control-user" id="exampleInputPassword" placeholder="Password">
                    </div>
                    <button type="submit" class="btn btn-primary btn-user btn-block">
                      Login
                    </button>
                 <?= @$form_close ?>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
        <div class="col-xl-7 col-lg-8 col-md-6">
        <div class="card shadow o-hidden border-0 shadow-lg my-5">
                <div class="card-header py-3">
                  <h6 class="m-0 font-weight-bold text-primary">Tanggal Penting</h6>
                </div>
                <div class="card-body">
                    <ul>
                        <li>
                            Ujian Proposal
                            <div class="table-responsive">
                                <table class="table table-bordered"  width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Jadwal</th>
                                            <th>Tanggal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Pendaftaran</td>
                                            <td>25-30 September 2019</td>
                                        </tr>
                                        <tr>
                                            <td>Penyerahan Berkas</td>
                                            <td>25-30 September 2019</td>
                                        </tr>
                                        <tr>
                                            <td>Ujian</td>
                                            <td>25-30 September 2019</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </li>
                        <li>Ujian Komprehensif
                            <div class="table-responsive">
                                <table class="table table-bordered"  width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Jadwal</th>
                                            <th>Tanggal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                            <td>Pendaftaran</td>
                                            <td>25-30 September 2019</td>
                                        </tr>
                                        <tr>
                                            <td>Penyerahan Berkas</td>
                                            <td>25-30 September 2019</td>
                                        </tr>
                                        <tr>
                                            <td>Ujian</td>
                                            <td>25-30 September 2019</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </li>
                        <li>Ujian Munaqosah
                            <div class="table-responsive">
                                <table class="table table-bordered"  width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Jadwal</th>
                                            <th>Tanggal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                            <td>Pendaftaran</td>
                                            <td>25-30 September 2019</td>
                                        </tr>
                                        <tr>
                                            <td>Penyerahan Berkas</td>
                                            <td>25-30 September 2019</td>
                                        </tr>
                                        <tr>
                                            <td>Ujian</td>
                                            <td>25-30 September 2019</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </li>
                    </ul>
                </div>
              </div>
        </div>
    </div>
  </div>

<script src="<?= base_url('media/jquery/jquery.min.js') ?>" type="text/javascript"></script>
<script src="<?= base_url('media/sbadmin2/js/sb-admin-2.min.js') ?>" type="text/javascript"></script>
</body>
