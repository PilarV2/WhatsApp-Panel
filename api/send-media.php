<?php

include_once("../helper/koneksi.php");
include_once("../helper/function.php");


// Takes raw data from the request
$data = json_decode(file_get_contents('php://input'), true);
$sender = $data['sender'];
$nomor = $data['number'];
$caption = $data['message'];
//$filetype = $data['filetype'];
$key = $data['api_key'];
$url = $data['url'];
header('Content-Type: application/json');


if (!isset($nomor) ||  !isset($sender) || !isset($key)  || !isset($url)) {
    $ret['status'] = false;
    $ret['msg'] = "Parameter salah!";
    echo json_encode($ret, true);
    exit;
}

$a = explode('/', $url);
$filename = $a[count($a) - 1];
$a2 = explode('.', $filename);
$namefile = $a2[count($a2) - 2];
$ext = $a2[count($a2) - 1];

if ($ext != 'jpg' && $ext != 'pdf') {
    $ret['status'] = false;
    $ret['msg'] = "Hanya support jpg dan pdf";
    echo json_encode($ret, true);
    exit;
}

$cek = mysqli_query($koneksi, "SELECT * FROM account WHERE api_key = '$key'");
if ($cek->num_rows < 1) {
    $ret['status'] = false;
    $ret['msg'] = "Api Key salah/tidak ditemukan!2";
    echo json_encode($ret, true);
    exit;
}
$username = $cek->fetch_assoc()['username'];
$cek2 = mysqli_query($koneksi, "SELECT * FROM device WHERE nomor = '$sender' AND pemilik = '$username'");
if ($cek2->num_rows < 1) {
    $ret['status'] = false;
    $ret['msg'] = "Api Key salah/tidak ditemukan!1";
    echo json_encode($ret, true);
    exit;
}
$res = sendMedia($nomor, $caption, $sender, $ext, $namefile, $url);
if ($res['status'] == "true") {
    $ret['status'] = true;
    $ret['msg'] = "Pesan berhasil dikirim";
    echo json_encode($ret, true);
    exit;
} else {
    $ret['status'] = false;
    $ret['msg'] = $res['message'];
    echo json_encode($ret, true);
    exit;
}
