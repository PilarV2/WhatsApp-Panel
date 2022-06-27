<?php
include_once("../helper/koneksi.php");
include_once("../helper/function.php");
date_default_timezone_set('Asia/Jakarta');

$login = cekSession();
if ($login == 0) {
    redirect("login.php");
}

if (post("pesan")) {
    $username = $_SESSION['username'];
    $pesan = post("pesan");
    $sender = post("device");
    // var_dump($sender); die;
    $jadwal = date("Y-m-d H:i:s", strtotime(post("tgl") . " " . post("jam")));
    if (!empty($_FILES['media']) && $_FILES['media']['error'] == UPLOAD_ERR_OK) {
        // Be sure we're dealing with an upload
        if (is_uploaded_file($_FILES['media']['tmp_name']) === false) {
            throw new \Exception('Error on upload: Invalid file definition');
        }

        // Rename the uploaded file
        $uploadName = $_FILES['media']['name'];
        $ext = strtolower(substr($uploadName, strripos($uploadName, '.') + 1));
        $size = $_FILES['media']['size'];
        if ($size > 1000000) {
            toastr_set("error", "Maximal 1 mb");
            redirect("kirim.php");
            exit;
        }
        $allow = ['pdf', 'jpg'];
        if (in_array($ext, $allow)) {
            if ($ext == "pdf") {
                $filename = round(microtime(true)) . mt_rand() . '.pdf';
            }

            if ($ext == "jpg") {
                $filename = round(microtime(true)) . mt_rand() . '.jpg';
            }
        } else {
            toastr_set("error", "Format  jpg, pdf only");
            redirect("kirim.php");
            exit;
        }

        move_uploaded_file($_FILES['media']['tmp_name'], 'uploads/' . $filename);
        // Insert it into our tracking along with the original name
        $media = $base_url . "pages/uploads/" . $filename;
    } else {
        $media = null;
    }



    if (isset($_POST['target'])) {
        foreach ($_POST['target'] as $data) {
            $n = $data;
            $ceknomor = mysqli_query($koneksi, "SELECT * FROM nomor WHERE nomor = '$n' AND make_by = '$username'");
            $data2 = $ceknomor->fetch_assoc();
            $pesannya = strtr($pesan, array(
                '{nama}' => $data2['nama'],
            ));
            $pesannya2 = utf8_encode($pesannya);
            if ($media == null) {
                $q = mysqli_query($koneksi, "INSERT INTO pesan(`sender`,`nomor`, `pesan`, `jadwal`, `make_by`)
              VALUES('$sender','$n', '$pesannya2', '$jadwal','$username')");
            } else {
                $q = mysqli_query($koneksi, "INSERT INTO pesan(`sender`,`nomor`, `pesan`, `media`, `jadwal`, `make_by`)
              VALUES('$sender','$n', '$pesannya2', '$media', '$jadwal', '$username')");
            }
        }
        // var_dump($n); die;

    } else {
        $username = $_SESSION['username'];
        $ceknomor = mysqli_query($koneksi, "SELECT * FROM nomor WHERE make_by = '$username'");
        while ($data = $ceknomor->fetch_assoc()) {
            $pesannya = strtr($pesan, array(
                '{nama}' => $data['nama'],
            ));
            $pesannya2 = utf8_encode($pesannya);
            $n = $data['nomor'];
            if ($media == null) {
                $q = mysqli_query($koneksi, "INSERT INTO pesan(`sender`,`nomor`, `pesan`, `jadwal`, `make_by`)
            VALUES('$sender','$n', '$pesannya2', '$jadwal','$username')");
            } else {
                $q = mysqli_query($koneksi, "INSERT INTO pesan(`sender`,`nomor`, `pesan`, `media`, `jadwal`, `make_by`)
            VALUES('$sender','$n', '$pesannya2', '$media', '$jadwal', '$username')");
            }
            // var_dump($q);
        }
    }



    toastr_set("success", "Sukses kirim pesan terjadwal1");
}

if (post("pesan2")) {
    $sender = post("device");
    $username = $_SESSION['username'];
    //$pesan = post("pesan");
    $jadwal = date("Y-m-d H:i:s", strtotime(post("tgl") . " " . post("jam")));
    if (!empty($_FILES['media']) && $_FILES['media']['error'] == UPLOAD_ERR_OK) {
        // Be sure we're dealing with an upload
        if (is_uploaded_file($_FILES['media']['tmp_name']) === false) {
            throw new \Exception('Error on upload: Invalid file definition');
        }

        // Rename the uploaded file
        $uploadName = $_FILES['media']['name'];
        $ext = strtolower(substr($uploadName, strripos($uploadName, '.') + 1));
        $size = $_FILES['media']['size'];
        if ($size > 1000000) {
            toastr_set("error", "maximal 1 mb");
            redirect("kirim.php");
            exit;
        }
        $allow = ['pdf', 'jpg'];
        if (in_array($ext, $allow)) {
            if ($ext == "png") {
                $filename = round(microtime(true)) . mt_rand() . '.png';
            }

            if ($ext == "pdf") {
                $filename = round(microtime(true)) . mt_rand() . '.pdf';
            }

            if ($ext == "jpg") {
                $filename = round(microtime(true)) . mt_rand() . '.jpg';
            }

            if ($ext == "jpeg") {
                $filename = round(microtime(true)) . mt_rand() . '.jpeg';
            }
        } else {
            toastr_set("error", "Format jpg, pdf only");
            redirect("kirim.php");
            exit;
        }

        move_uploaded_file($_FILES['media']['tmp_name'], 'uploads/' . $filename);
        // Insert it into our tracking along with the original name
        $media = $base_url . "pages/uploads/" . $filename;
    } else {
        $media = null;
    }



    if (isset($_POST['target'])) {
        foreach ($_POST['target'] as $data) {
            $n = $data;
            $ceknomor = mysqli_query($koneksi, "SELECT * FROM nomor WHERE nomor = '$n' AND make_by = '$username'");
            $data2 = $ceknomor->fetch_assoc();
            $pesannya = strtr($data2['pesan'], array(
                '{nama}' => $data2['nama'],
            ));

            $pesannya2 = utf8_encode($pesannya);
            if ($media == null) {
                $q = mysqli_query($koneksi, "INSERT INTO pesan(`sender`,`nomor`, `pesan`, `jadwal`, `make_by`)
              VALUES('$sender','$n', '$pesannya2', '$jadwal','$username')");
            } else {
                $q = mysqli_query($koneksi, "INSERT INTO pesan(`sender`,`nomor`, `pesan`, `media`, `jadwal`, `make_by`)
              VALUES('$sender','$n', '$pesannya2', '$media', '$jadwal', '$username')");
            }
        }
    } else {
        $username = $_SESSION['username'];
        $ceknomor = mysqli_query($koneksi, "SELECT * FROM nomor WHERE make_by = '$username'");
        while ($data = $ceknomor->fetch_assoc()) {
            $pesannya = strtr($data['pesan'], array(
                '{nama}' => $data['nama'],
            ));
            $pesannya2 = utf8_encode($pesannya);
            $n = $data['nomor'];
            if ($media == null) {
                $q = mysqli_query($koneksi, "INSERT INTO pesan(`sender`,`nomor`, `pesan`, `jadwal`, `make_by`)
            VALUES('$sender','$n', '$pesannya2', '$jadwal','$username')");
            } else {
                $q = mysqli_query($koneksi, "INSERT INTO pesan(`sender`,`nomor`, `pesan`, `media`, `jadwal`, `make_by`)
            VALUES('$sender','$n', '$pesannya2', '$media', '$jadwal', '$username')");
            }
            // var_dump($q);
        }
    }

    toastr_set("success", "Sukses kirim pesan terjadwal");
}

if (get("act") == "ku") {
    $id_blast = get("id");
    $q = mysqli_query($koneksi, "UPDATE `pesan` SET `status`='MENUNGGU JADWAL' WHERE `id_blast`='$id_blast' AND `status`='GAGAL'");
    toastr_set("success", "Sukses mengirim ulang blast");
    redirect("kirim.php");
}

if (get("act") == "hd") {
    $q = mysqli_query($koneksi, "DELETE FROM pesan WHERE `status`='TERKIRIM'");
    toastr_set("success", "Sukses menghapus pesan");
    redirect("kirim.php");
}

require_once('../templates/header.php');
?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- DataTales Example -->
    <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#exampleModal">
        Kirim Pesan
    </button>
    <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#kirimpesan2">
        Kirim Pesan ( Pesan sesuai data nomor )
    </button>
    <br>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary" style="display:contents">Data Pesan</h6>
            <a class="btn btn-danger float-right" href="kirim.php?act=hd">Hapus data (terkirim)</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Sender</th>
                            <th>Nomor</th>
                            <th>Pesan</th>
                            <th>Media</th>
                            <th>Jadwal</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                        $username = $_SESSION['username'];
                        $q = mysqli_query($koneksi, "SELECT * FROM pesan WHERE make_by='$username' ORDER BY id DESC");

                        while ($row = mysqli_fetch_assoc($q)) {
                            echo '<tr>';
                            echo '<td>' . $row['sender'] . '</td>';
                            echo '<td>' . $row['nomor'] . '</td>';
                            echo '<td>' . utf8_decode($row['pesan'])  . '</td>';
                            echo '<td>' . $row['media'] . '</td>';
                            echo '<td>' . $row['jadwal'] . '</td>';
                            if ($row['status'] == "TERKIRIM") {
                                echo '<td><span class="badge badge-success status-container-' . $row['id'] . '">Terkirim</span></td>';
                            } else if ($row['status'] == "GAGAL") {
                                echo '<td><span class="badge badge-danger status-container-' . $row['id'] . '">Gagal Terkirim</span></td>';
                            } else {
                                echo '<td><span class="badge badge-warning status-container-' . $row['id'] . '">Menunggu Jadwal / Pending</span></td>';
                            }

                            if ($row['status'] == "GAGAL") {
                                echo '<td class="button-container-' . $row['id'] . '"><a style="margin:5px" class="btn btn-success" href="kirim.php?act=ku&id=' . $row['id_blast'] . '">Kirim Ulang</a><a style="margin:5px" class="btn btn-danger" href="hapus_pesan.php?id=' . $row['id'] . '">Hapus</a></td>';
                            } else {
                                echo '<td class="button-container-' . $row['id'] . '"><a class="btn btn-danger" href="hapus_pesan.php?id=' . $row['id'] . '">Hapus</a></td>';
                            }
                            echo '</tr>';
                        }

                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->

<!-- Footer -->
<footer class="sticky-footer bg-white">
    <div class="container my-auto">
        <div class="copyright text-center my-auto">
            <span>Copyright &copy; <a href="https://web.facebook.com/mycodingxd">HambaAllah</a></span>
        </div>
    </div>
</footer>
<!-- End of Footer -->

</div>
<!-- End of Content Wrapper -->

</div>
<!-- End of Page Wrapper -->

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                <a class="btn btn-primary" href="<?= $base_url; ?>auth/logout.php">Logout</a>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Kirim Pesan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="POST" enctype="multipart/form-data">
                    <label>sender</label>
                    <br>
                    <select class="form-control js-example-basic-multiple" name="device" style="width: 100%">
                        <?php

                        $u = $_SESSION['username'];
                        $q = mysqli_query($koneksi, "SELECT * FROM device WHERE pemilik='$u'");

                        while ($row = mysqli_fetch_assoc($q)) {
                            echo '<option value="' . $row['nomor'] . '">' . $row['nama'] . ' (' . $row['nomor'] . ')</option>';
                        }
                        ?>
                    </select>
                    <br>
                    <label> Pesan * </label>
                    <textarea name="pesan" required class="form-control"></textarea>
                    <br>
                    <label> Media </label>
                    <input type="file" name="media" class="form-control">
                    <p class="text-small text-danger">Maximal 1mb </p>
                    <br>
                    <label> Tanggal Pengiriman * </label>
                    <input type="date" name="tgl" required class="form-control">
                    <br>
                    <label> Waktu Pengiriman * </label>
                    <input type="time" name="jam" required class="form-control">
                    <br>
                    <label>Target</label>
                    <br>
                    <select class="form-control js-example-basic-multiple" name="target[]" multiple="multiple" style="width: 100%">
                        <?php

                        $u = $_SESSION['username'];
                        $q = mysqli_query($koneksi, "SELECT * FROM nomor WHERE make_by='$u'");

                        while ($row = mysqli_fetch_assoc($q)) {
                            echo '<option value="' . $row['nomor'] . '">' . $row['nama'] . ' (' . $row['nomor'] . ')</option>';
                        }
                        ?>
                    </select>
                    <br>
                    <p>*Kosongkan bila ingin mengirim ke semua kontak</p>
                    <br>
                    <br>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" name="pesan1" class="btn btn-primary">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="kirimpesan2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Kirim Pesan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="POST" enctype="multipart/form-data">
                    <label>sender</label>
                    <br>
                    <select class="form-control js-example-basic-multiple" name="device" style="width: 100%">
                        <?php

                        $u = $_SESSION['username'];
                        $q = mysqli_query($koneksi, "SELECT * FROM device WHERE pemilik='$u'");

                        while ($row = mysqli_fetch_assoc($q)) {
                            echo '<option value="' . $row['nomor'] . '">' . $row['nama'] . ' (' . $row['nomor'] . ')</option>';
                        }
                        ?>
                    </select>
                    <br>
                    <input type="hidden" name="pesan2" value="yo">
                    <label> Media </label>
                    <input type="file" name="media" class="form-control">
                    <p class="text-small text-danger">Maximal 1mb </p>
                    <br>
                    <label> Tanggal Pengiriman * </label>
                    <input type="date" name="tgl" required class="form-control">
                    <br>
                    <label> Waktu Pengiriman * </label>
                    <input type="time" name="jam" required class="form-control">
                    <br>
                    <label>Target</label>
                    <br>
                    <select class="form-control js-example-basic-multiple" name="target[]" multiple="multiple" style="width: 100%">
                        <?php

                        $u = $_SESSION['username'];
                        $q = mysqli_query($koneksi, "SELECT * FROM nomor WHERE make_by='$u'");

                        while ($row = mysqli_fetch_assoc($q)) {
                            echo '<option value="' . $row['nomor'] . '">' . $row['nama'] . ' (' . $row['nomor'] . ')</option>';
                        }
                        ?>
                    </select>
                    <br>
                    <p>*Kosongkan bila ingin mengirim ke semua kontak</p>
                    <br>
                    <br>
                    <div class="form-check">
                        <input type="checkbox" name="tiap_bulan" class="form-check-input" id="exampleCheck1">
                        <label class="form-check-label" for="exampleCheck1">Kirim tiap bulan</label>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" name="kirimpesan2" class="btn btn-info">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap core JavaScript-->
<script src="<?= $base_url; ?>vendor/jquery/jquery.min.js"></script>
<script src="<?= $base_url; ?>vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="<?= $base_url; ?>vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="<?= $base_url; ?>js/sb-admin-2.min.js"></script>

<!-- Page level plugins -->
<script src="<?= $base_url; ?>vendor/datatables/jquery.dataTables.min.js"></script>
<script src="<?= $base_url; ?>vendor/datatables/dataTables.bootstrap4.min.js"></script>

<!-- Page level custom scripts -->
<script src="<?= $base_url; ?>js/demo/datatables-demo.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous"></script>
<script>
    <?php

    toastr_show();

    ?>
</script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.js-example-basic-multiple').select2({
            dropdownAutoWidth: true
        });
    });
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js" integrity="sha512-qTXRIMyZIFb8iQcfjXWCO8+M5Tbc38Qi5WzdPOYZHIlZpzBHG3L3by84BBBOiRGiEb7KKtAOAs5qYdUiZiQNNQ==" crossorigin="anonymous"></script>
<script>
    setInterval(sync, 4000);

    function sync() {
        let sync = localStorage.getItem('sync');
        if (sync == null) {
            sync = moment().format("YYYY-MM-DD HH:mm:ss");
            localStorage.setItem('sync', sync);
        }

        $.get("longpooling.php?lastsync=" + sync, function(data) {
            r = JSON.parse(data);

            jQuery.each(r, function(i, val) {
                let id = val.id;
                let id_blast = val.id_blast;
                if (val.status == "GAGAL") {
                    $(".status-container-" + id).empty();
                    $(".status-container-" + id).html('Gagal Terkirim');
                    $(".status-container-" + id).addClass('badge-danger').removeClass('badge-warning');

                    $(".button-container-" + id).html('<a style="margin:5px" class="btn btn-success" href="kirim.php?act=ku&id=' + id_blast + '">Kirim Ulang</a><a style="margin:5px" class="btn btn-danger" href="hapus_pesan.php?id=' + id + '">Hapus</a>');
                }

                if (val.status == "TERKIRIM") {
                    $(".status-container-" + id).empty();
                    $(".status-container-" + id).html('Terkirim');
                    $(".status-container-" + id).addClass('badge-success').removeClass('badge-warning');

                    $(".button-container-" + id).html('<a style="margin:5px" class="btn btn-danger" href="hapus_pesan.php?id=' + id + '">Hapus</a>');
                }
                console.log(id);
            });

            localStorage.setItem('sync', moment().format("YYYY-MM-DD HH:mm:ss"));

        });
    }
</script>
</body>

</html>