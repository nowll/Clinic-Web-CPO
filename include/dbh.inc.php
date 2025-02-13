<?php

$hostname = "klinik-db";
$dbusername = "klinik";
$dbpassword = "Asfg#2356";
$dbname = "klinik";

$conn = mysqli_connect($hostname, $dbusername, $dbpassword, $dbname);

if(!$conn){
    die("Connection failed: " . mysqli_connect_error());
}
