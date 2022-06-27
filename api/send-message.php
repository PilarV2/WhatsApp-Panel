<?php

include_once("../helper/koneksi.php");
include_once("../helper/function.php");


// Takes raw data from the request
$data = json_decode(file_get_contents('php://input'), true);
$sender = $data['sender'];
$nomor = $data['number'];
$pesan = $data['message'];
$key = $data['api_key'];
header('Content-Type: application/json');



if(!isset($nomor) && !isset($pesan) && !isset($sender) && !isset($key)){
    $ret['status'] = false;
    $ret['msg'] = "Parameter salah!";
    echo json_encode($ret, true);
    exit;
}

$cek = mysqli_query($koneksi,"SELECT * FROM account WHERE api_key = '$key'");
if($cek->num_rows < 1){
    $ret['status'] = false;
    $ret['msg'] = "Api Key salah/tidak ditemukan!";
    echo json_encode($ret, true);
    exit;
}
$username = $cek->fetch_assoc()['username'];
$cek2 = mysqli_query($koneksi,"SELECT * FROM device WHERE nomor = '$sender' AND pemilik = '$username'");
if($cek2->num_rows < 1){
    $ret['status'] = false;
    $ret['msg'] = "Api Key salah/tidak ditemukan!";
    echo json_encode($ret, true);
    exit;
}
$res = sendMSG($nomor, $pesan,$sender);
if($res['status'] == "true"){
    $ret['status'] = true;
    $ret['msg'] = "Pesan berhasil dikirim";
    echo json_encode($ret, true);
    exit;
}else{
    $ret['status'] = false;
    $ret['msg'] = $res['message'];
    echo json_encode($ret, true);
    exit;
}
