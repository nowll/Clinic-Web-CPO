<?php
session_start();
include 'include/dbh.inc.php';
// define variable
$error = "";
$sukses = "";
$lahir = "";
$disable = "";
$currentUser = "";
$currentPass = "";

// kalo session variable email ada isi, store ke currentUser
if(isset($_SESSION['email'])){
    $currentUser = $_SESSION['email'];
}
// kalo session variable password ada isi, store ke currentPass
if(isset($_SESSION['passwd'])){
    $currentPass = $_SESSION['passwd'];
}
// kalo currentUSer & currentPass kosong, destroy session & redirect ke login page
if($currentUser == "" || $currentPass == ""){
    session_destroy();
    header('location: index.php');
}
// select & fetch semua data dari table credentials dimana row email == currentUser
$select = "SELECT * FROM `credentials` WHERE email = '$currentUser' ";
$query = mysqli_query($conn, $select);
$result = mysqli_fetch_assoc($query);
// kalo row lahir ada isi, redirect ke page daftar
if($result['lahir'] != ""){
    header('location: daftar.php');
}
// kalo klik confirm, assign value yg di post ke local variable
if(isset($_POST["confirm"])){
    $nama = $_POST['nama'];
    $lahir = $_POST['lahir'];
    $perusahaan = $_POST['perusahaan'];
    $nik = $_POST['nik'];
    $dept = $_POST['dept'];
    // kalo field lahir belum diisi, display error message
    if($_POST['lahir'] == ""){
        $error = "Lengkapi semua data"; 
    }else{
    // kalo field error sudah diisi siapin sql statement buat update data lahir
        $insert = "UPDATE `credentials` SET
                `lahir` = '$lahir'
                WHERE email = '$currentUser' ";
        // kalo query berhasil diexecute, redirect ke page daftar
        if(mysqli_query($conn, $insert) == true){
            header('location: daftar.php');
        }else{
            $error = "Error: " . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="icon" type="image/jpg" href="image/CP-icon.jpg">
    <title>Data Diri</title>
    <style>
        .noscroll{
            position: static;
            overflow-y: auto;
        }
        .bg{
            background-image: url(image/Kantor-CP-bg.jpg);
            height: 100vh;
            width: auto;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }
        #waktu option[disabled]{
            display: none;
        }
        @media(max-width: 1600px){
            .bg{
                background-image: url(image/Kantor-CP-bg.jpg);
                max-height: 120vh;
                width: auto;
                background-position: center;
                background-repeat: no-repeat;
                background-size: cover;
            }
        }
        @media(max-width: 1200px){
            .bg{
                background-image: url(image/Kantor-CP-bg.jpg);
                max-height: 140vh;
                width: auto;
                background-position: center;
                background-repeat: no-repeat;
                background-size: cover;
            }
        }
        @media(max-width: 768px){
            .link-sm{
                font-size: 0.85rem;
            }
            .bg{
                background-image: url(image/Kantor-CP-bg.jpg);
                max-height: 160vh;
                width: auto;
                background-position: center;
                background-repeat: no-repeat;
                background-size: cover;
            }
        }
        @media(max-width: 500px){
            .header-sm{
                font-size: 1.25rem;
            }
        }
    </style>
</head>
<body class="bg noscroll">
    <div class="container p-5">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-12 card">
                <div class="card-body">
                    <div class="d-flex row justify-content-md-center justify-content-end align-items-center ms-4 me-3">
                        <h3 class="text-center my-md-3 mt-3 mb-0 header-sm">Lengkapi Data Diri Anda</h3>
                    </div>
                    <form action="data_diri.php" method="post">
                        <?php
                        // kalo error ga kosong, display error messagee
                        if($error != ""){
                            echo '<div class="d-flex m-4 align-items-center justify-content-between alert alert-danger alert-dismissable fade show" role="alert">'.$error.'<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>'.'</div>';
                        }
                        ?>
                        <div class="row px-sm-4 px-0 mb-3">
                            <!-- input field buat nama -->
                            <div class="col-lg-12 col-md-12 col-12">
                                <label for="nama" class="form-label link-sm">Nama Lengkap</label>
                                <input type="text" class="form-control link-sm" id="nama" name="nama" value="<?php echo @$result['nama'];?>" disabled>
                            </div>
                        </div>
                        <div class="row px-sm-4 px-0 mb-3">
                            <!-- input field buat tgl lahir -->
                            <div class="col-lg-6 col-md-6 col-12 mb-lg-0 mb-md-0 mb-3">
                                <label for="lahir" class="form-label link-sm">Tanggal Lahir</label>
                                <input type="date" class="form-control link-sm" id="lahir" name="lahir">
                            </div>
                            <!-- input field buat perusahaan -->
                            <div class="col-lg-6 col-md-6 col-12">
                                <label for="perusahaan" class="form-label link-sm">Perusahaan</label>
                                <input type="text" class="form-control link-sm" id="perusahaan" name="perusahaan" value="<?php echo @$result['perusahaan'];?>" disabled>
                            </div>
                        </div>
                        <div class="row px-sm-4 px-0 mb-3">
                            <!-- input field buat nik -->
                            <div class="col-lg-6 col-md-6 col-12 mb-lg-0 mb-md-0 mb-3">
                                <label for="nik" class="form-label link-sm">NIK</label>
                                <input type="text" class="form-control link-sm" id="nik" name="nik" value="<?php echo @$result['nik'];?>" disabled>
                            </div>
                            <!-- input field buat departemen -->
                            <div class="col-lg-6 col-md-6 col-12">
                                <label for="dept" class="form-label link-sm">Departemen</label>
                                <input type="text" class="form-control link-sm" id="dept" name="dept" value="<?php echo @$result['dept'];?>" disabled>
                            </div>
                        </div>
                        <div class="row justify-content-end px-sm-4 px-0 mt-4 mb-3">
                            <div class="d-grid col-6">
                                <button class="btn btn-success link-sm" name="confirm" type="submit">Confirm</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>