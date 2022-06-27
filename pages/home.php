<?php
include_once("../helper/koneksi.php");
include_once("../helper/function.php");


$login = cekSession();
if ($login == 0) {
    redirect("../auth/login.php");
}


if (get("act") == "hapus") {
    $nomor = get("nomor");
    $file = '../whatsapp-session-' . $nomor . '.json';
    $cekfile = file_exists($file);

    if ($cekfile == true) {
        toastr_set("error", "Harap Logout koneksi sebelum menghapus");
    } else {
        $q = mysqli_query($koneksi, "DELETE FROM device WHERE nomor='$nomor'");
        toastr_set("success", "Sukses hapus user");
    }
}

if (post("nomorwhatsapp")) {
    $nomor = post("nomorwhatsapp");
    $cek = mysqli_query($koneksi, "SELECT * FROM device WHERE nomor = '$nomor' ");
    if (substr($nomor, 0, 2) != '62') {
        toastr_set("error", "Nomor harus menggunakan kode negara ");
    } else if (mysqli_num_rows($cek) > 0) {
        toastr_set("error", "Nomor sudah ada di database");
    } else {
        $username = $_SESSION['username'];
        $q = mysqli_query($koneksi, "INSERT INTO device VALUES (null,'$username','$nomor','')");
        toastr_set("success", "Nomor berhasil ditambahkan");
    }
}

if (post("idnomor")) {
    $id = post("idnomor");
    $url = post("urlwebhook");
    $update = mysqli_query($koneksi, "UPDATE device SET link_webhook = '$url' WHERE id = '$id'");
    toastr_set("success", "hook berhasil di set");
}
require_once('../templates/header.php');
?>


<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
    </div>

    <!-- Content Row -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <button class="btn btn-primary mb-4" data-toggle="modal" data-target="#tambahNomorModal">Tambah Nomor</button>
                    <div class="table table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Nomor</th>
                                    <th>Webhook Url</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $username = $_SESSION['username'];
                                $q = mysqli_query($koneksi, "SELECT * FROM device WHERE pemilik = '$username'");
                                while ($row = mysqli_fetch_assoc($q)) { ?>
                                    <tr>
                                        <td><?= $row['nomor']; ?></td>

                                        <td><?= $row['link_webhook']; ?></td>
                                        </td>
                                        <td>
                                            <button class="btn btn-primary scanbutton" onclick="scanqr('<?= $row['nomor']; ?>')">Scan</button>
                                            <a class="btn btn-danger" href="home.php?act=hapus&nomor=<?= $row['nomor']; ?>">Delete</a>
                                            <button class="btn btn-success" onclick="sethook('<?= $row['id']; ?>')">Set Webhook</button>

                                        </td>
                                    </tr>
                                <?php } ?>


                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="row">
        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Nomor Tersimpan</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= countDB("nomor") ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-phone fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Whatsapp Terkirim</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= countDB("pesan", "status", "TERKIRIM") ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-comments fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Presentase Terkirim
                            </div>
                            <div class="row no-gutters align-items-center">
                                <div class="col-auto">
                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">
                                        <?= round(countPresentase()) ?>%</div>
                                </div>
                                <div class="col">
                                    <div class="progress progress-sm mr-2">
                                        <div class="progress-bar bg-info" role="progressbar" style="width: <?= round(countPresentase()) ?>%" aria-valuenow="<?= round(countPresentase()) ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-double fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Requests Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Menunggu Jadwal Pengiriman</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= countDB("pesan", "status", "MENUNGGU JADWAL") ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-pause fa-2x text-gray-300"></i>
                        </div>
                    </div>
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
<!-- Tambah Nomor Modal -->
<!-- Modal -->

<div class="modal fade" id="tambahNomorModal" tabindex="-1" role="dialog" aria-labelledby="tambahNomorModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Nomor </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="POST">
                    <label> Nomor Whatsapp </label>
                    <input type="number" name="nomorwhatsapp" value="62" required class="form-control">
                    <br>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" name="tambahnomor" class="btn btn-primary">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- set hook modal -->
<div class="modal fade" id="setHookModal" tabindex="-1" role="dialog" aria-labelledby="tambahNomorModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Set Webhook</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body bodysetwebhook">
                <form action="" method="POST">
                    <input type="hidden" name="idnomor" class="idnomor" readonly required class="form-control">
                    <br>
                    <label>URL Webhook</label>
                    <input type="text" name="urlwebhook" required class="form-control urlwebhook">
                    <br>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" name="tambahnomor" class="btn btn-primary">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- scan Modal-->
<div class="modal fade" id="scanModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Scan QR</h5>
            </div>
            <div class="card shadow m-3 areascanqr">


            </div>
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
<script src="<?= $base_url; ?>vendor/chart.js/Chart.min.js"></script>

<!-- Page level custom scripts -->
<script src="<?= $base_url; ?>js/demo/chart-area-demo.js"></script>
<script src="<?= $base_url; ?>js/demo/chart-pie-demo.js"></script>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/3.1.0/socket.io.js" integrity="sha512-+l9L4lMTFNy3dEglQpprf7jQBhQsQ3/WvOnjaN/+/L4i0jOstgScV0q2TjfvRF4V+ZePMDuZYIQtg5T4MKr+MQ==" crossorigin="anonymous"></script> -->
<script src="../node_modules/socket.io/client-dist/socket.io.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous">
</script>
<script>
    <?php
    toastr_show();
    ?>
    // ini socket untuk di hosting
    // var socket = io();

    // ini socket untuk di localhost
    var socket = io('http://localhost:3000', {
        transports: ['websocket',
            'polling',
            'flashsocket'
        ]
    });

    function scanqr(nomor) {
        $('.areascanqr').html(`
<div class="card-body">
    <div id="cardimg-${nomor}" class="text-center ">

    </div>
    <p id="info-${nomor}" class="info-${nomor}"></p>
    <div class="div arealogout"></div>
    <button class="btn btn-danger scanbutton" onclick="logoutqr(${nomor})">Logout</button>
</div>

`)
        $(`#cardimg-${nomor}`).html(`<img src="../loading.gif" class="card-img-top center" alt="cardimg" id="qrcode"
    style="height:250px; width:250px;"><br><p>menghubungkan ....</p>`);

        $('#scanModal').modal('show');
        socket.emit('create-session', {
            id: nomor
        });
    }
    // sethook
    function sethook(id) {
        $('.idnomor').val(id);
        var hook = $('.urlwebhook').val();
        $('#setHookModal').modal('show');
    }
    /// function ini untuk logouot
    function logoutqr(nomor) {
        socket.emit('logout', {
            id: nomor
        });
    }



    socket.on('message', function(msg) {
        $('.log').html(`<li>` + msg.text + `</li>`);
    })
    socket.on('qr', function(src) {
        console.log(src)
        $(`#cardimg-${src.id}`).html(`<img src="` + src.src + `" class="card-img-top" alt="cardimg" id="qrcode"
    style="height:250px; width:250px;">`);
        var count = 0;
        var interval = setInterval(function() {
            count++
            $(`.info-${src.id}`).html(`<p>Waktu scan anda adalah 10 detik - <span class="text-danger">${count}</span></p>`);
            if (count == 10) {
                $(`#cardimg-${src.id}`).html(`<h2 class="text-center text-warning mt-4">Silahkan refresh untuk scan ulang<h2>`);

                clearInterval(interval)
            }
        }, 1000);
    });
    // socket.on('authenticated', function(src) {
    //     $(`#info-${src.id}`).attr('class', 'changed');
    //     $('.changed').html('')
    //     $(`#cardimg-${src.id}`).html(`<h2 class="text-center text-success mt-4">` + src.text + `<h2>`);

    // });
    // ketika terhubung
    socket.on('authenticated', function(src) {
        const nomors = src.data.jid;
        //  const nomor = src.id
        const nomor = nomors.replace(/\D/g, '');
        $(`#cardimg-${src.id}`).html(` <img src="` + src.data.imgUrl + `" class="card-img-top" alt="foto profil" id="qrcode" style="height:250px; width:250px;"><br><br>
            <ul>
            <li> Nama : ${src.data.name}</li>
            <li> Nomor Wa : ${src.data.jid}</li>
            <li> Phone : ${src.data.phone.device_model}</li>
            <li> WA Versi : ${src.data.phone.wa_version}</li>
            </ul>
            
            `);
        //  $('#cardimg').html(`<h2 class="text-center text-success mt-4">Whatsapp Connected.<br>` + src + `<h2>`);

    });
    socket.on('isdelete', function(src) {
        //  $(`.info-${src.id}`).html(`<p><span class="text-danger">disconnect</span></p>`);
        $(`#cardimg-${src.id}`).html(src.text);
    });
    socket.on('close', function(src) {
        console.log(src);
        $(`#cardimg-${src.id}`).html(`<h2 class="text-center text-danger mt-4">` + src.text + `<h2>`);
    })
</script>

</body>

</html>