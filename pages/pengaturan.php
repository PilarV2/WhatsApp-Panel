<?php
include_once("../helper/koneksi.php");
include_once("../helper/function.php");


$login = cekSession();
if ($login == 0) {
    redirect("login.php");
}


if (post("username")) {
    $u = post("username");
    $password = post('newpassword');

    if (strlen($password) < 5) {
        toastr_set("error", "Password Minimal 5 karakter");
    } else if (post("newpassword") != post("newpassword2")) {
        toastr_set("error", "Password tidak sesuai");
        //exit;
    } else {
        $p = sha1(post("newpassword"));
        $u = $_SESSION['username'];
        $q = mysqli_query($koneksi, "UPDATE account SET password = '$p' WHERE username = '$u' ");
        if ($q) {

            toastr_set("success", "Ganti password berhasil");
        }
    }
}

if (get("act") == "hapus") {
    $id = get("id");

    $q = mysqli_query($koneksi, "DELETE FROM account WHERE id='$id'");
    toastr_set("success", "Sukses hapus user");
}

if (post("chunk")) {
    $username = $_SESSION['username'];
    $chunk = post("chunk");
    if ($chunk > 100) {
        toastr_set("error", "Maximal pesan masal adalah 100 per menit");
    } else {
        mysqli_query($koneksi, "UPDATE account SET chunk = '$chunk' WHERE username='$username'");
        toastr_set("success", "Sukses edit pengaturan");
    }
}
if (post("apikey")) {
    $username = $_SESSION['username'];
    $api_key = sha1(date("Y-m-d H:i:s") . rand(100000, 999999));
    mysqli_query($koneksi, "UPDATE account SET api_key='$api_key' WHERE username='$username'");
    toastr_set("success", "Sukses generate api key baru");
}

require('../templates/header.php');
?>


<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- DataTales Example -->
    <div class="row">


        <div class="col-md-6">
            <div class="card shadow mb-4">

                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Pengaturan wa</h6>
                </div>

                <div class="card-body">
                    <?php
                    $username = $_SESSION['username'];

                    ?>
                    <hr>
                    <form action="" method="post">
                        <label> API KEY </label>
                        <input type="text" class="form-control" name="apikey" readonly value="<?= getSingleValDB("account", "username", "$username", "api_key") ?>">
                        <br>
                        <button class="btn btn-primary"> Ubah Api Key </button>
                        <br>
                        <br>
                    </form>
                    <form action="" method="post">
                        <label> Batas Pengiriman per menit </label>
                        <input type="text" class="form-control" name="chunk" value="<?= getSingleValDB("account", "username", "$username", "chunk") ?>">
                        <br>
                        <button class="btn btn-success"> Simpan </button>
                    </form>
                </div>
            </div>

        </div>
        <div class="col-md-6">
            <div class="card shadow mb-4">

                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Pengaturan account</h6>
                </div>

                <div class="card-body">
                    <?php
                    $username = $_SESSION['username'];

                    ?>
                    <hr>
                    <form action="" method="post">
                        <label> Username </label>
                        <input type="text" class="form-control" name="username" readonly value="<?= getSingleValDB("account", "username", "$username", "username") ?>">
                        <br>
                        <label> Password baru </label>
                        <input type="password" class="form-control" name="newpassword">
                        <br>
                        <label>Ulangi Password baru </label>
                        <input type="password" class="form-control" name="newpassword2">
                        <br>

                        <button class="btn btn-success"> Ubah password </button>

                    </form>
                </div>
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
                <h5 class="modal-title" id="exampleModalLabel">Tambah User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="POST">
                    <label> Username </label>
                    <input type="text" name="username" required class="form-control">
                    <br>
                    <label> Password </label>
                    <input type="password" name="password" required class="form-control">
                    <br>
                    <label for="exampleFormControlSelect1">Level</label>
                    <select class="form-control" id="exampleFormControlSelect1" name="level">
                        <option value="1">Admin</option>
                        <option value="2">CS</option>
                    </select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
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

</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/3.1.0/socket.io.js" integrity="sha512-+l9L4lMTFNy3dEglQpprf7jQBhQsQ3/WvOnjaN/+/L4i0jOstgScV0q2TjfvRF4V+ZePMDuZYIQtg5T4MKr+MQ==" crossorigin="anonymous"></script>
<script>
    <?php

    toastr_show();

    ?>
</script>
</body>

</html>