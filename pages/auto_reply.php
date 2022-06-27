<?php
include_once("../helper/koneksi.php");
include_once("../helper/function.php");


$login = cekSession();
if ($login == 0) {
    redirect("login.php");
}


if (get("act") == "hapus") {
    $id = get("id");

    $q = mysqli_query($koneksi, "DELETE FROM autoreply WHERE id='$id'");
    toastr_set("success", "Sukses menghapus autoreply");
    redirect("auto_reply.php");
}

if (post("nomor")) {
    $nomor = post("nomor");
    $keyword = post("keyword");
    $respon = post("response");
    if (!empty($_FILES['media']) && $_FILES['media']['error'] == UPLOAD_ERR_OK) {
        // Be sure we're dealing with an upload
        if (is_uploaded_file($_FILES['media']['tmp_name']) === false) {
            throw new \Exception('Error on upload: Invalid file definition');
        }


        if ($size > 1000000) {
            toastr_set("error", "Maximal 1mb");
            redirect("auto_reply.php");
            exit;
        }
        // Rename the uploaded file
        $uploadName = $_FILES['media']['name'];
        $ext = strtolower(substr($uploadName, strripos($uploadName, '.') + 1));

        $allow = ['png', 'jpeg', 'pdf', 'jpg'];
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
            toastr_set("error", "Format png, jpg, pdf only");
            redirect("auto_reply.php");
            exit;
        }

        move_uploaded_file($_FILES['media']['tmp_name'], 'uploads/' . $filename);
        // Insert it into our tracking along with the original name
        $media = $base_url . "pages/uploads/" . $filename;
    } else {
        $media = null;
    }

    $u = $_SESSION['username'];
    $cek = mysqli_query($koneksi, "SELECT * FROM autoreply WHERE nomor = '$nomor' AND keyword = '$keyword'");
    if (mysqli_num_rows($cek) > 0) {
        toastr_set("error", "Nomor dengan keyword tersebut sudah ada");
    } else {
        $q = mysqli_query($koneksi, "INSERT INTO autoreply VALUES ('','$keyword','$respon','$media','$nomor','$u')");
        toastr_set("success", "Sukses menambahkan autoreply");
    }
}
require_once('../templates/header.php');
?>


<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- DataTales Example -->
    <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#exampleModal">
        Tambah Autoreply
    </button>
    <br>
    <div class="card shadow mb-4">

        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Data Autoreply</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Nomor</th>
                            <th>Keyword</th>
                            <th>Response</th>
                            <th>Response Media</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $username = $_SESSION['username'];
                        $q = mysqli_query($koneksi, "SELECT * FROM autoreply WHERE make_by = '$username'");

                        while ($row = mysqli_fetch_assoc($q)) {
                            echo '<tr>';
                            echo '<td>' . $row['nomor'] . '</td>';
                            echo '<td>' . $row['keyword'] . '</td>';
                            echo '<td>' . $row['response'] . '</td>';
                            echo '<td>' . $row['media'] . '</td>';
                            echo '<td><a class="btn btn-danger" href="auto_reply.php?act=hapus&id=' . $row['id'] . '">Hapus</a></td>';
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
                <h5 class="modal-title" id="exampleModalLabel">Tambah Autoreply</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="POST" enctype="multipart/form-data">
                    <label for="">Nomor</label>
                    <select class="form-control" name="nomor" style="width: 100%">
                        <?php
                        $u = $_SESSION['username'];
                        $q = mysqli_query($koneksi, "SELECT * FROM device WHERE pemilik='$u'");
                        while ($row = mysqli_fetch_assoc($q)) {
                            echo '<option value="' . $row['nomor'] . '">' . $row['nomor'] . '</option>';
                        }
                        ?>
                    </select>
                    <label> Keyword </label>
                    <input type="text" name="keyword" required class="form-control">
                    <br>
                    <label> Media </label>
                    <input type="file" name="media" class="form-control">
                    <p class="text-small text-danger">*jpg/png/pdf maximal 1mb</p>
                    <label> Response </label>
                    <textarea name="response" class="form-control" required></textarea>
                    <br>

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
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.js-example-basic-multiple').select2({
            dropdownAutoWidth: true
        });
    });
</script>
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