<?php
include_once("../helper/koneksi.php");
include_once("../helper/function.php");
// <meta name="description" content="WaSender Pro">
$count = 0;
$now = strtotime(date("Y-m-d H:i:s"));
$chunk = 100;
$q = mysqli_query($koneksi, "SELECT * FROM pesan WHERE status='MENUNGGU JADWAL' ORDER BY id ASC LIMIT 50 ");

$i = 0;
while ($data = $q->fetch_assoc()) {
    $jadwal = strtotime($data['jadwal']);
    if ($jadwal < $now) {

        $sender = $data['sender'];
        $nomor = $data['nomor'];
        $pesan = $data['pesan'];
        $media = $data['media'];
        if ($data['media'] != null) {

            $a = explode('/', $media);
            $filename = $a[count($a) - 1];
            $a2 = explode('.', $filename);
            $namefile = $a2[count($a2) - 2];
            $filetype = $a2[count($a2) - 1];
            $send = sendMedia($nomor, $pesan, $sender, $filetype, $namefile, $media);
            $this_id = $data['id'];
            if ($send['status'] == "true") {
                $i++;
                $q3 = mysqli_query($koneksi, "UPDATE pesan SET status = 'TERKIRIM' WHERE id='$this_id'");
            } else {
                $q3 = mysqli_query($koneksi, "UPDATE pesan SET status = 'GAGAL' WHERE id='$this_id'");
                $s = false;
            }
        }
    }
}
echo 'succes kirim ke' . $i . 'Nomor';
