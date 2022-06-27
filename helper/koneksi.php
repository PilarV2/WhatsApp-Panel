<?php

$host = "containers-us-west-66.railway.app";
$username = "root";
$password = "joxL3Sv8a5wN2RjoRGen";
$db = "railway";
error_reporting(0);
$koneksi = mysqli_connect($host, $username, $password, $db) or die("GAGAL");

$base_url = "https://web-whatsapp-bot.herokuapp.com/wabot/";
date_default_timezone_set('Asia/Jakarta');
