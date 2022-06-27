<?php
require('koneksi.php');
require('function.php');
// ------------------------------------------------------------------//
header('content-type: application/json');
$data = json_decode(file_get_contents('php://input'), true);
$sender =  preg_replace("/\D/", "", $data['id']);
$u = mysqli_query($koneksi, "SELECT * FROM device WHERE nomor = '$sender'");
$user = $u->fetch_assoc()['pemilik'];


foreach ($data['data'] as $d) {
    if (array_key_exists('name', $d)) {
        $nama = $d['name'];
        if (strpos($d['jid'], '@g.us') == false) {
            $type = 'Personal';
            $number = preg_replace("/\D/", "", $d['jid']);
        } else {
            $type = 'Group';
            $number = $d['jid'];
        }
        $cek = mysqli_query($koneksi, "SELECT * FROM all_contacts WHERE sender = '$sender' AND number = '$number'");
        if ($cek->num_rows > 0) {
            toastr_set("error", "Kontak dari nomor wa sender tersebut sudah ada di tabase");
        } else {

            $insert = mysqli_query($koneksi, "INSERT INTO all_contacts VALUES(null,'$sender','$number','$nama','$type')");
            toastr_set("success", "Berhasil Ambil Kontak");
        }
    }
}
