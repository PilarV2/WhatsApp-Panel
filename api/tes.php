<?php

$data = [
    'api_key' => 'api key mu',
    'sender'  => 'nomor sender (Pastikan sudah scan)',
    'number'  => 'nomor tujuan kirim pesan',
    'message' => 'caption ( isi jika kirim gambar)',
    'filetype' => 'jpg/pdf',
    'url' => 'Link gambar/pdf'
];

$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => "https://domainmu/api/send-media.php",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => json_encode($data))
);

$response = curl_exec($curl);

curl_close($curl);
echo $response;



