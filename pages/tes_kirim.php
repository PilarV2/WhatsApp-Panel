<?php
include_once("../helper/koneksi.php");
include_once("../helper/function.php");

$login = cekSession();
if ($login == 0) {
    redirect("login.php");
}

if (post("pesan")) {
    $nomor = post("nomor");
    $pesan = post("pesan");
    $sender = post("sender");
    $res = sendMSG($nomor, $pesan, $sender);
    if ($res['status'] == true) {
        toastr_set("success", "Pesan terkirim");
    } else {
        toastr_set("error", $res['response']);
    }
}

if (post("nomormedia")) {
    $nomor = post("nomormedia");
    $pesan = post("pesan");
    $sender = post("sender");
    $url = post("linkmedia");
    $a = explode('/', $url);
    $filename = $a[count($a) - 1];
    $a2 = explode('.', $filename);
    $namefile = $a2[count($a2) - 2];
    $filetype = $a2[count($a2) - 1];
    $res = sendMedia($nomor, $pesan, $sender, $filetype, $namefile, $url);
    if ($res['status'] == "true") {
        toastr_set("success", "Pesan terkirim");
    } else {
        toastr_set("error", $res['message']);
    }
}
require('../templates/header.php')
?>


<!-- Begin Page Content -->
<div class="container-fluid">
    <div class="row">
        <!-- DataTales Example -->
        <div class="card shadow col-md-5 mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Tes Kirim Pesan</h6>
            </div>
            <div class="card-body">
                <form action="" method="post">
                    <label for="">Sender</label>
                    <select class="form-control" name="sender" style="width: 100%">
                        <?php
                        $u = $_SESSION['username'];
                        $q = mysqli_query($koneksi, "SELECT * FROM device WHERE pemilik='$u'");
                        while ($row = mysqli_fetch_assoc($q)) {
                            echo '<option value="' . $row['nomor'] . '">' . $row['nomor'] . '</option>';
                        }
                        ?>
                    </select>
                    <label> Nomor Tujuan</label>
                    <input class="form-control" type="text" name="nomor" placeholder="08xxxxxxxx" required>
                    <br>
                    <label> Pesan </label>
                    <input class="form-control" type="text" name="pesan" required>
                    <br>
                    <button class="btn btn-success" type="submit">Kirim</button>
                </form>
            </div>
        </div>
        <div class="card shadow  col-md-5 ml-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Tes Kirim Media</h6>
                <p clas="small-text">Memungkinkan untuk mengirim jpg png dan pdf</p>
            </div>
            <div class="card-body">
                <form action="" method="post">
                    <label for="">Sender</label>
                    <select class="form-control" name="sender" style="width: 100%">
                        <?php
                        $u = $_SESSION['username'];
                        $q = mysqli_query($koneksi, "SELECT * FROM device WHERE pemilik='$u'");
                        while ($row = mysqli_fetch_assoc($q)) {
                            echo '<option value="' . $row['nomor'] . '">' . $row['nomor'] . '</option>';
                        }
                        ?>
                    </select>
                    <label> Nomor Tujuan</label>
                    <input class="form-control" type="text" name="nomormedia" placeholder="08xxxxxxxx" required>
                    <br>
                    <label> Pesan </label>
                    <input class="form-control" type="text" name="pesan">
                    <p class="small-text">Isi jika mengirim image</p>
                    <br>
                    <label> Link Media </label>
                    <input class="form-control" type="text" name="linkmedia" required>
                    <p class="small-text">jpg/png/jpeg/pdf</p>
                    <p class="small-text">support png,jpg dan pdf</p>
                    <br>
                    <!-- <label> Type File </label>
                    <input class="form-control" type="text" name="filetype" required>
                    <p class="small-text">jpg/png/jpeg/pdf</p>
                    <br> -->
                    <button class="btn btn-success" type="submit">Kirim</button>
                </form>
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
</body>

</html>