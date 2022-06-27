<?php
include_once("../helper/koneksi.php");
include_once("../helper/function.php");
$login = cekSession();
if ($login == 0) {
    redirect("login.php");
}

if (post("callback")) {
    $callback = post("callback");
    mysqli_query($koneksi, "UPDATE pengaturan SET callback = '$callback' WHERE id='1'");
    toastr_set("success", "Sukses edit callback");
}

if (get("act") == "cn") {
    mysqli_query($koneksi, "UPDATE pengaturan SET callback = NULL WHERE id='1'");
    toastr_set("success", "Sukses menonaktifkan callback");
    redirect("rest_api.php");
}
$username = $_SESSION['username'];

require_once('../templates/header.php');
?>


<!-- DataTales Example -->
<div class="card shadow mb-4 ml-4 mr-4">
<div class="card-body">
<div class="code-block">
<div class="input url"><label for="OrderLink">Your Token</label><input disabled value="<?= getSingleValDB("account", "username", "$username", "api_key") ?>" placeholder="" name="url" type="url" class="form-control" required id="url"/></div>
<br><br>


<br>

<h3>PHP API CODE Send Text Message</h3>
<!-- HTML generated using highlightmycode --><div style="background: #ffffff; overflow:auto;width:auto;border:solid gray;border-width:.1em .1em .1em .8em;padding:.2em .6em;"><pre style="margin: 0; line-height: 125%"><span style="color: #507090">&lt;?php</span>

<span style="color: #906030">$data</span> <span style="color: #303030">=</span> [
    <span style="background-color: #fff0f0">&#39;api_key&#39;</span> <span style="color: #303030">=&gt;</span> <span style="background-color: #fff0f0">&#39;api key&#39;</span>,
    <span style="background-color: #fff0f0">&#39;sender&#39;</span>  <span style="color: #303030">=&gt;</span> <span style="background-color: #fff0f0">&#39;Nomor pengirim (pastikan sudah scan)&#39;</span>,
    <span style="background-color: #fff0f0">&#39;number&#39;</span>  <span style="color: #303030">=&gt;</span> <span style="background-color: #fff0f0">&#39;Nomor tujuan kirim pesan&#39;</span>,
    <span style="background-color: #fff0f0">&#39;message&#39;</span> <span style="color: #303030">=&gt;</span> <span style="background-color: #fff0f0">&#39;Pesan nya&#39;</span>
];

<span style="color: #906030">$curl</span> <span style="color: #303030">=</span> <span style="color: #007020">curl_init</span>();
curl_setopt_array(<span style="color: #906030">$curl</span>, <span style="color: #008000; font-weight: bold">array</span>(
  CURLOPT_URL <span style="color: #303030">=&gt;</span> <span style="background-color: #fff0f0">&quot;<?= $base_url;?>api/send-message.php&quot;</span>,
  CURLOPT_RETURNTRANSFER <span style="color: #303030">=&gt;</span> <span style="color: #008000; font-weight: bold">true</span>,
  CURLOPT_ENCODING <span style="color: #303030">=&gt;</span> <span style="background-color: #fff0f0">&quot;&quot;</span>,
  CURLOPT_MAXREDIRS <span style="color: #303030">=&gt;</span> <span style="color: #0000D0; font-weight: bold">10</span>,
  CURLOPT_TIMEOUT <span style="color: #303030">=&gt;</span> <span style="color: #0000D0; font-weight: bold">0</span>,
  CURLOPT_FOLLOWLOCATION <span style="color: #303030">=&gt;</span> <span style="color: #008000; font-weight: bold">true</span>,
  CURLOPT_HTTP_VERSION <span style="color: #303030">=&gt;</span> CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST <span style="color: #303030">=&gt;</span> <span style="background-color: #fff0f0">&quot;POST&quot;</span>,
  CURLOPT_POSTFIELDS <span style="color: #303030">=&gt;</span> json_encode(<span style="color: #906030">$data</span>))
);

<span style="color: #906030">$response</span> <span style="color: #303030">=</span> <span style="color: #007020">curl_exec</span>(<span style="color: #906030">$curl</span>);

<span style="color: #007020">curl_close</span>(<span style="color: #906030">$curl</span>);
<span style="color: #008000; font-weight: bold">echo</span> <span style="color: #906030">$response</span>;
</pre></div>


<br><br>


<h3>PHP API CODE Send Media Message</h3>
<!-- HTML generated using highlightmycode --><div style="background: #ffffff; overflow:auto;width:auto;border:solid gray;border-width:.1em .1em .1em .8em;padding:.2em .6em;"><pre style="margin: 0; line-height: 125%"><span style="color: #507090">&lt;?php</span>

<span style="color: #906030">$data</span> <span style="color: #303030">=</span> [
    <span style="background-color: #fff0f0">&#39;api_key&#39;</span> <span style="color: #303030">=&gt;</span> <span style="background-color: #fff0f0">&#39;api key mu&#39;</span>,
    <span style="background-color: #fff0f0">&#39;sender&#39;</span>  <span style="color: #303030">=&gt;</span> <span style="background-color: #fff0f0">&#39;nomor sender (Pastikan sudah scan)&#39;</span>,
    <span style="background-color: #fff0f0">&#39;number&#39;</span>  <span style="color: #303030">=&gt;</span> <span style="background-color: #fff0f0">&#39;nomor tujuan kirim pesan&#39;</span>,
    <span style="background-color: #fff0f0">&#39;message&#39;</span> <span style="color: #303030">=&gt;</span> <span style="background-color: #fff0f0">&#39;caption ( isi jika kirim gambar)&#39;</span>,
    <!-- <span style="background-color: #fff0f0">&#39;filetype&#39;</span> <span style="color: #303030">=&gt;</span> <span style="background-color: #fff0f0">&#39;jpg/pdf&#39;</span>, -->
    <span style="background-color: #fff0f0">&#39;url&#39;</span> <span style="color: #303030">=&gt;</span> <span style="background-color: #fff0f0">&#39;Link gambar/pdf&#39;</span>
];

<span style="color: #906030">$curl</span> <span style="color: #303030">=</span> <span style="color: #007020">curl_init</span>();
curl_setopt_array(<span style="color: #906030">$curl</span>, <span style="color: #008000; font-weight: bold">array</span>(
  CURLOPT_URL <span style="color: #303030">=&gt;</span> <span style="background-color: #fff0f0">&quot;<?= $base_url;?>api/send-media.php&quot;</span>,
  CURLOPT_RETURNTRANSFER <span style="color: #303030">=&gt;</span> <span style="color: #008000; font-weight: bold">true</span>,
  CURLOPT_ENCODING <span style="color: #303030">=&gt;</span> <span style="background-color: #fff0f0">&quot;&quot;</span>,
  CURLOPT_MAXREDIRS <span style="color: #303030">=&gt;</span> <span style="color: #0000D0; font-weight: bold">10</span>,
  CURLOPT_TIMEOUT <span style="color: #303030">=&gt;</span> <span style="color: #0000D0; font-weight: bold">0</span>,
  CURLOPT_FOLLOWLOCATION <span style="color: #303030">=&gt;</span> <span style="color: #008000; font-weight: bold">true</span>,
  CURLOPT_HTTP_VERSION <span style="color: #303030">=&gt;</span> CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST <span style="color: #303030">=&gt;</span> <span style="background-color: #fff0f0">&quot;POST&quot;</span>,
  CURLOPT_POSTFIELDS <span style="color: #303030">=&gt;</span> json_encode(<span style="color: #906030">$data</span>))
);

<span style="color: #906030">$response</span> <span style="color: #303030">=</span> <span style="color: #007020">curl_exec</span>(<span style="color: #906030">$curl</span>);

<span style="color: #007020">curl_close</span>(<span style="color: #906030">$curl</span>);
<span style="color: #008000; font-weight: bold">echo</span> <span style="color: #906030">$response</span>;
</pre></div>

<br><br>
<h3>PHP  CODE Webhook</h3>

<!-- HTML generated using highlightmycode --><div style="background: #ffffff; overflow:auto;width:auto;border:solid gray;border-width:.1em .1em .1em .8em;padding:.2em .6em;"><pre style="margin: 0; line-height: 125%"><span style="color: #507090">&lt;?php</span>

<span style="color: #808080">// ------------------------------------------------------------------//</span>
<span style="color: #007020">header</span>(<span style="background-color: #fff0f0">&#39;content-type: application/json&#39;</span>);
<span style="color: #906030">$data</span> <span style="color: #303030">=</span> json_decode(<span style="color: #007020">file_get_contents</span>(<span style="background-color: #fff0f0">&#39;php://input&#39;</span>), <span style="color: #008000; font-weight: bold">true</span>);
<span style="color: #007020">file_put_contents</span>(<span style="background-color: #fff0f0">&#39;whatsapp.txt&#39;</span>, <span style="background-color: #fff0f0">&#39;[&#39;</span> <span style="color: #303030">.</span> <span style="color: #007020">date</span>(<span style="background-color: #fff0f0">&#39;Y-m-d H:i:s&#39;</span>) <span style="color: #303030">.</span> <span style="background-color: #fff0f0">&quot;]</span><span style="color: #606060; font-weight: bold; background-color: #fff0f0">\n</span><span style="background-color: #fff0f0">&quot;</span> <span style="color: #303030">.</span> json_encode(<span style="color: #906030">$data</span>) <span style="color: #303030">.</span> <span style="background-color: #fff0f0">&quot;</span><span style="color: #606060; font-weight: bold; background-color: #fff0f0">\n\n</span><span style="background-color: #fff0f0">&quot;</span>, FILE_APPEND);
<span style="color: #906030">$message</span> <span style="color: #303030">=</span> <span style="color: #906030">$data</span>[<span style="background-color: #fff0f0">&#39;message&#39;</span>]; <span style="color: #808080">// ini menangkap pesan masuk</span>
<span style="color: #906030">$from</span> <span style="color: #303030">=</span> <span style="color: #906030">$data</span>[<span style="background-color: #fff0f0">&#39;from&#39;</span>]; <span style="color: #808080">// ini menangkap nomor pengirim pesan</span>


<span style="color: #008000; font-weight: bold">if</span> (<span style="color: #007020">strtolower</span>(<span style="color: #906030">$message</span>) <span style="color: #303030">==</span> <span style="background-color: #fff0f0">&#39;hai&#39;</span>) {
    <span style="color: #906030">$result</span> <span style="color: #303030">=</span> [
        <span style="background-color: #fff0f0">&#39;mode&#39;</span> <span style="color: #303030">=&gt;</span> <span style="background-color: #fff0f0">&#39;chat&#39;</span>, <span style="color: #808080">// mode chat = chat biasa</span>
        <span style="background-color: #fff0f0">&#39;pesan&#39;</span> <span style="color: #303030">=&gt;</span> <span style="background-color: #fff0f0">&#39;Hai juga&#39;</span>
    ];
} <span style="color: #008000; font-weight: bold">else</span> <span style="color: #008000; font-weight: bold">if</span> (<span style="color: #007020">strtolower</span>(<span style="color: #906030">$message</span>) <span style="color: #303030">==</span> <span style="background-color: #fff0f0">&#39;hallo&#39;</span>) {
    <span style="color: #906030">$result</span> <span style="color: #303030">=</span> [
        <span style="background-color: #fff0f0">&#39;mode&#39;</span> <span style="color: #303030">=&gt;</span> <span style="background-color: #fff0f0">&#39;reply&#39;</span>, <span style="color: #808080">// mode reply = reply pessan</span>
        <span style="background-color: #fff0f0">&#39;pesan&#39;</span> <span style="color: #303030">=&gt;</span> <span style="background-color: #fff0f0">&#39;Halo juga&#39;</span>
    ];
} <span style="color: #008000; font-weight: bold">else</span> <span style="color: #008000; font-weight: bold">if</span> (<span style="color: #007020">strtolower</span>(<span style="color: #906030">$message</span>) <span style="color: #303030">==</span> <span style="background-color: #fff0f0">&#39;gambar&#39;</span>) {
    <span style="color: #906030">$result</span> <span style="color: #303030">=</span> [
        <span style="background-color: #fff0f0">&#39;mode&#39;</span> <span style="color: #303030">=&gt;</span> <span style="background-color: #fff0f0">&#39;picture&#39;</span>, <span style="color: #808080">// type picture = kirim pesan gambar</span>
        <span style="background-color: #fff0f0">&#39;data&#39;</span> <span style="color: #303030">=&gt;</span> [
            <span style="background-color: #fff0f0">&#39;caption&#39;</span> <span style="color: #303030">=&gt;</span> <span style="background-color: #fff0f0">&#39;*webhook picture*&#39;</span>,
            <span style="background-color: #fff0f0">&#39;url&#39;</span> <span style="color: #303030">=&gt;</span> <span style="background-color: #fff0f0">&#39;https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRZ2Ox4zgP799q86H56GbPMNWAdQQrfIWD-Mw&amp;usqp=CAU&#39;</span>
        ]
    ];
}

<span style="color: #008000; font-weight: bold">print</span> json_encode(<span style="color: #906030">$result</span>);


<span style="color: #808080">// kami akan memberitahu jika update. :)</span>
</pre></div>


</div>
<br><br>

<h3>Respon</h3>
<div>
    Token Tidak Valid <br>
    {"status":false,"message":"Api key salah"}
    <br><br>
    
    Nomer Tidak Valid <br>
    {"status":false,"message":"Harap scan qr terlebih dahulu"}
    <br><br>
    
    Pesan Kosong <br>
    {"status":false,"message":"Parameter salah"}
    <br><br>
    
    Gagal Request <br>
    {"status":false,"message":"Error"}
    <br><br>
    
    Url Tidak Valid <br>
    {"status":false,"message":"Url Tidak Valid"}
    <br><br>
    
    Bukan File Gambar <br>
    {"status":false,"message":"Extensi Tidak Dikenal"}
    <br><br>
    
    Sukses Request <br>
    {"status":true,"message":"Pesan berhasil dikirim"}
    <br><br>
    
   
   
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
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
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
                <a class="btn btn-primary" href="<?= $base_url;?>auth/logout.php">Logout</a>
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"
    integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw=="
    crossorigin="anonymous"></script>
<script>
    <?php

    toastr_show();

    ?>
</script>
</body>

</html>