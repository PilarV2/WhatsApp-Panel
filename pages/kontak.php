<?php
include_once("../helper/koneksi.php");
include_once("../helper/function.php");


$login = cekSession();
if ($login == 0) {
    redirect("login.php");
}

if (isset($_POST['ambilkontak'])) {
    $u = $_SESSION['username'];
    $sender = $_POST['device'];

    $b = mysqli_query($koneksi, "SELECT * FROM all_contacts WHERE sender = '$sender'");
    while ($data = $b->fetch_assoc()) {
        $number = $data['number'];
        $nama = $data['name'];
        $type = $data['type'];
        $cek = mysqli_query($koneksi, "SELECT * FROM contacts WHERE sender = '$sender' AND number = '$number'");
        if ($cek->num_rows > 0) {
            toastr_set("error", "Kontak dari nomor wa sender tersebut sudah ada di tabase");
        } else {

            $insert = mysqli_query($koneksi, "INSERT INTO contacts VALUES('','$sender','$number','$nama','$type','$u')");
            toastr_set("success", "Berhasil Ambil Kontak");
        }
    }
}






if (get("act") == "hapus") {
    $u = $_SESSION['username'];
    $id = get("id");

    $q = mysqli_query($koneksi, "DELETE  FROM contacts WHERE id='$id' AND make_by = '$u'");
    toastr_set("success", "Sukses hapus kontak");
    redirect("kontak.php");
}

if (get("act") == "delete_all") {
    $u = $_SESSION['username'];
    $q = mysqli_query($koneksi, "DELETE FROM contacts WHERE make_by = '$u'");
    toastr_set("success", "Sukses hapus semua kontak");
    redirect("kontak.php");
}
require_once('../templates/header.php');
?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- DataTales Example -->

    <br>
    <div class="card shadow mb-4">

        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary" style="display: contents">Data Nomor</h6>
            <a class="btn btn-danger float-right" href="kontak.php?act=delete_all" style="margin:5px">Hapus Semua</a>
            <a class="btn btn-info float-right" href="<?= $base_url; ?>lib/kontakgroup.php" style="margin:5px">Export Kontak Group (excel)</a>
            <a class="btn btn-info float-right" href="<?= $base_url; ?>lib/kontakpersonal.php" style="margin:5px">Export Kontak Personal (excel)</a>
            <button type="submit" class="btn btn-info float-right" data-toggle="modal" style="margin:5px" data-target="#ambilkontak">
                Ambil Kontak
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Sender</th>
                            <th>Nama</th>
                            <th>Nomor</th>
                            <th>Type</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $u = $_SESSION['username'];
                        $q = mysqli_query($koneksi, "SELECT * FROM contacts WHERE make_by='$u'");

                        while ($row = mysqli_fetch_assoc($q)) {
                            echo '<tr>';
                            echo '<td>' . $row['sender'] . '</td>';
                            echo '<td>' . $row['name'] . '</td>';
                            echo '<td>' . $row['number'] . '</td>';
                            echo '<td>' . $row['type'] . '</td>';
                            echo '<td><a class="btn btn-danger" href="kontak.php?act=hapus&id=' . $row['id'] . '">Hapus</a></td>';
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
                <h5 class="modal-title" id="exampleModalLabel">Tambah Nomor</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="POST">
                    <label> Nama </label>
                    <input type="text" name="nama" required class="form-control">
                    <br>
                    <label> Nomor Telepon </label>
                    <input type="text" name="nomor" required class="form-control" placeholder="08xxxxxxxx">
                    <br>
                    <label>Pesan </label>
                    <input type="text" name="pesan" required class="form-control" placeholder="pesan">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modal ambil kontak -->
<div class="modal fade" id="ambilkontak" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Nomor</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="POST">
                    <label> Kontak dari nomor ? </label>
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
                    <p>pastikan nomor tersebut sudah scan dan connected</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" name="ambilkontak" class="btn btn-primary">Ambil</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="import" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Import Nomor</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="../lib/import_excel.php" method="POST" enctype="multipart/form-data">
                    <label> File (.xlsx) </label>
                    <input type="file" name="file" required class="form-control">
                    <br>
                    <label> Mulai dari Baris ke </label>
                    <input type="text" name="a" required class="form-control" value="2">
                    <br>
                    <label> Kolom Nama ke </label>
                    <input type="text" name="b" required class="form-control" value="1">
                    <br>
                    <label> Kolom Nomor ke </label>
                    <input type="text" name="c" required class="form-control" value="2">
                    <br>
                    <label> Kolom pesan ke </label>
                    <input type="text" name="d" required class="form-control" value="3">
                    <br>
                    <p> Download file contoh <a href="<?= $base_url; ?>excel/contoh.xlsx" target="_blank">disini</a> </p>
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
    <?php

    toastr_show();

    ?>
</script>
</body>

</html>