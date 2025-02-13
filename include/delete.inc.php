<?php
session_start();
$_SESSION["sukses"] = "";
$_SESSION["error"] = "";
require_once 'dbh.inc.php';
if(isset($_GET['id'])){
    $id = $_GET['id'];
    $sql = "DELETE FROM `daftar` WHERE id = '$id' ";
    $query = mysqli_query($conn,$sql);

    if($query === true){
        $_SESSION["sukses"] = "Data pasien berhasil di delete";
        header('location: ../history.php');
    }else{
        $_SESSION["error"] = "Data pasien gagal di delete";
        header('location: ../history.php');
    }
}
?>