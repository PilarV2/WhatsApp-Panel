<?php

$data = [
    'api_key' => '5e09c971c0b2722d3fd511d25a411243d8769c4e',
    'sender'  => '6282330605698',
    'number'  => '6282231376068',
    'message' => 'Pesan nya'
];

$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => "https://web-whatsapp-bot.herokuapp.com/wabot/api/send-message.php",
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
