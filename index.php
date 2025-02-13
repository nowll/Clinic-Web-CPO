<?php
error_reporting(E_ALL);
ini_set('display_errors',1);
session_start();
include 'include/dbh.inc.php';
// define variable
$error = "";
$warning = "";
$user = "";
$email = "";
$passwd = "";
$nama = "";
$lahir = "";
$perusahaan = "";
$nik = "";
$dept = "";
$_SESSION['access'] = "";
$_SESSION['entry'] = array();
$_SESSION['email'] = "";
$_SESSION['passwd'] = "";
// connect ke ldap server
$ldap_server = "10.1.3.238";
$ldap_port = 389;
$ldap_dn = "uid=mailadmin,ou=superuser,o=CP Indonesia";
$ldap_pass = "mailadm1701";
$ldap_con = ldap_connect($ldap_server, $ldap_port);

//cek kalo login button di click
if(isset($_POST['login'])){
    //store email, password yg di post ke variable local
    $email = $_POST['email'];
    $passwd = $_POST['passwd'];

    //select semua data dari table 'credentials' dimana email & password di database == email & password local
    $select = "SELECT * FROM `credentials` WHERE email = '$email' && passwd = '$passwd'";
    //execute query select
    $result = mysqli_query($conn, $select); 
    //fetch array dari hasil query buat ambil access
    $row = mysqli_fetch_assoc($result); 
   
    @$_SESSION['access'] = $row['access']; //store value access dari database ke session variable

    // kalo user login pake domain email, display warning message
    if(str_contains($email, '@')){
        $warning = "Login cukup menggunakan username, tidak perlu domain email";
    }elseif($email != @$row['email'] || $passwd != @$row['passwd']){
        $error = "Username atau Password Salah";
    }
    //cek jumlah row, lebih dari 0 atau ga (cek apakah data ada di database)
    elseif(mysqli_num_rows($result) > 0){
        //kalo access == admin, redirect ke page history admin
        if($_SESSION['access'] == "admin"){
            header('location: history.php');
        //kalo access == dokter, redirect ke page history dokter
        }elseif($_SESSION['access'] == "dokter"){
            header('location: history_dokter.php');
        }
    }
    //searching data user di ldap 
    if(ldap_bind($ldap_con, $ldap_dn, $ldap_pass)){
        $filter = "(uid=".$email.")";
        $result = ldap_search($ldap_con, "o=CP Indonesia", $filter);
        $entries = ldap_get_entries($ldap_con, $result);
        // store data dari ldap ke variable local
        @$dn_ldap = $entries[0]["dn"];
        @$user = mysqli_real_escape_string($conn, $entries[0]["uid"][0]);
        @$nama = mysqli_real_escape_string($conn, $entries[0]["displayname"][0]);
        @$perusahaan = mysqli_real_escape_string($conn,$entries[0]["o"][0]);
        @$nik = mysqli_real_escape_string($conn, $entries[0]["employeenumber"][0]);
        @$dept = mysqli_real_escape_string($conn, $entries[0]["departmentnumber"][0]);
        // bind ldap pake dn & password user
        if(@ldap_bind($ldap_con, $dn_ldap, $passwd)){
            // query buat inseret data user ke table credential
            $insert = "INSERT INTO `credentials` 
                    (`email`,`nama`,`perusahaan`,`nik`,`dept`) VALUES 
                    ('$user','$nama','$perusahaan','$nik','$dept') ";
            // store username, password & data2 user ke session variable
            $_SESSION['email'] = $entries[0]["uid"][0];
            $_SESSION['passwd'] = $passwd;
            $_SESSION['entry'] = $entries;
            // select & fetch semua data dari table credentials dimana username == username user yg login
            $selectCred = "SELECT * FROM `credentials` WHERE email = '$email' ";
            $queryCred = mysqli_query($conn,$selectCred);
            $checkCred = mysqli_fetch_assoc($queryCred);
            // kalo row email ada data, redirect ke page data diri
            if(!empty($checkCred['email'])){
                header('location: data_diri.php');
            }else{
                // kalo row email kosong, insert data
                mysqli_query($conn,$insert);
            }
        }elseif($entries['count'] == 0){
            $error = "Harap login menggunakan username dan password CP";
        }
    }else{
        // error message kalo username/password salah
        $error = "Username atau Password Salah";
    }
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="icon" type="image/jpg" href="image/CP-icon.jpg">
    <title>Login</title>
    <style>
        .bg{
            background-image: url(image/Kantor-CP-bg.jpg);
            /* height: 90vh; */
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }
    </style>
</head>
<body>
    <div class="container my-3 my-md-4 p-5">
        <div class="row justify-content-center">
            <div class="col-lg-2 col-md-3 col-4">
                <img src="image/CP-logo.jpg" class="img-fluid text-center" alt="Logo CP">
            </div>
            <h3 class="text-center mt-3">Klinik Head Office Ancol</h3>
            <h3 class="text-center">PT. Charoen Pokphand Indonesia, Tbk</h3>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-12 card mt-3">
                <div class="row">
                    <div class="d-grid col-6 p-0">
                        <a href="#" class="btn btn-dark rounded-bottom-0 rounded-end-0 fs-5">Username CPI</a>
                    </div>
                    <div class="d-grid col-6 p-0">
                        <a href="standard_login.php" class="btn btn-outline-dark rounded-bottom-0 rounded-start-0 fs-5">Guest</a>
                    </div>
                </div>
                <div class="card-body" >
                    <h3 class="text-center mt-3 mb-4">Username Login</h3>
                    <form action="index.php" method="post">
                        <?php
                        //kalo error ga kosong, display error message
                        if($error != ""){
                            echo '<div class="d-flex m-4 align-items-center justify-content-between alert alert-danger alert-dismissable fade show" role="alert">'.$error.'<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>'.'</div>';
                        }
                        // kalo warning ga kosong, display warning message
                        elseif($warning != ""){
                            echo '<div class="d-flex m-4 align-items-center justify-content-between alert alert-warning alert-dismissable fade show" role="alert">'.$warning.'<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>'.'</div>';
                        }
                        ?>
                        <!-- field input username -->
                        <div class="col-lg-12 px-4 mt-3 mb-4">
                            <label for="email" class="form-label fs-5">Username</label>
                            <input type="text" class="form-control" id="email" name="email" placeholder="Input username">
                            <div class="form-text fw-bold text-primary" style="font-size: 1.05rem;">*Harap login menggunakan username dari email</div>
                        </div>
                        <!-- field input password -->
                        <div class="col-lg-12 px-4 mb-4">
                            <label for="passwd" class="form-label fs-5">Password</label>
                            <input type="password" class="form-control" id="passwd" name="passwd" placeholder="Input password">
                        </div>
                        <div class="d-grid col-lg-12 px-4 mt-5 mb-3">
                            <button class="btn btn-primary fs-5" name="login" type="submit">Login</button>
                            <p class="text-center mt-3 mb-0 fw-bold" style="font-size: 1.05rem;">Untuk bantuan hubungi 
                                <span class="text-primary">0813-8246-3434</span> (Admin)
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>    
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
