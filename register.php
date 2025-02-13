<?php
session_start();
$error = "";
$creden = @$_SESSION['nik'];
$_SESSION['email'] = "";
$_SESSION['passwd'] = "";

if(isset($_POST["regis"])){
    $error = "";
    include 'include/dbh.inc.php';

    $nik = mysqli_real_escape_string($conn,$_POST["nik"]);
    $passwd = mysqli_real_escape_string($conn,$_POST["passwd"]);
    $nama = mysqli_real_escape_string($conn,$_POST["nama"]);
    $lahir = mysqli_real_escape_string($conn,$_POST["lahir"]);
    $perusahaan = mysqli_real_escape_string($conn,$_POST["perusahaan"]);
    $dept = mysqli_real_escape_string($conn,$_POST["dept"]);

    $select = "SELECT * FROM credentials WHERE email = '$nik' ";
    
    $result = mysqli_query($conn, $select);

    if(empty(($_POST["passwd"]))){
        $error = "Password harus diisi";
    }elseif(empty(trim($_POST["nama"])) || empty(trim($_POST["lahir"])) || empty(trim($_POST["perusahaan"]))
        || empty(trim($_POST["nik"])) || empty(trim($_POST["dept"]))){
        $error = "Semua data harus diisi";
    }else{
        if(mysqli_num_rows($result) > 0){
            $error = "Email sudah terdaftar";
        }else{
            $sql = "INSERT INTO `credentials` (`email`,`passwd`,`nama`,`lahir`,`perusahaan`,`nik`,`dept`) 
            VALUES ('$nik','$passwd','$nama','$lahir','$perusahaan','$nik','$dept')";

            if(mysqli_query($conn, $sql) == true){
                $_SESSION['email'] = $nik;
                $_SESSION['passwd'] = $passwd;
                header('location:daftar.php');
            }else{
                $error = "Error: " . $conn->error;
            }
        }
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
    <title>Register</title>
</head>
<body>
    <div class="container my-5 px-5">
        <div class="row justify-content-center">
            <div class="col-lg-2 col-md-3 col-4">
                <img src="image/CP-logo.jpg" class="img-fluid text-center" alt="Logo CP">
            </div>
            <h3 class="text-center mt-3">Klinik Head Office Ancol</h3>
            <h3 class="text-center">PT. Charoen Pokphand Indonesia</h3>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-12 card mt-3">
                <div class="card-body">
                    <h3 class="text-center my-3">Register</h3>
                    <form action="register.php" method="post">
                        <?php
                        if($error != ""){
                            echo '<div class="d-flex m-4 align-items-center justify-content-between alert alert-danger alert-dismissable fade show" role="alert">'.$error.'<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>'.'</div>';
                        }
                        ?>
                        <div class="row px-sm-4 px-0 mb-3">
                            <div class="col-lg-12 col-md-12 col-12">
                                <label for="nik" class="form-label">NIK</label>
                                <input type="text" class="form-control" id="nik" name="nik" value="<?php echo $creden; ?>" placeholder="Input NIK" required>
                                <div class="form-text">*Menggunakan NIK KTP atau Karyawan</div>
                            </div>
                        </div>
                        <div class="row px-sm-4 px-0 mb-3">
                            <div class="col-lg-12 col-md-12 col-12">
                                <label for="passwd" class="form-label">Password</label>
                                <input type="password" class="form-control" id="passwd" name="passwd" placeholder="Input Password" required>
                            </div>
                        </div>
                        <div class="row px-sm-4 px-0 mb-3">
                            <div class="col-lg-12 col-md-12 col-12">
                                <label for="nama" class="form-label link-sm">Nama Lengkap</label>
                                <input type="text" class="form-control link-sm" id="nama" name="nama" placeholder="Input Nama Lengkap" required>
                            </div>
                        </div>
                        <div class="row px-sm-4 px-0 mb-3">
                            <div class="col-lg-12 col-md-12 col-12">
                                <label for="perusahaan" class="form-label link-sm">Perusahaan</label>
                                <input type="text" class="form-control link-sm" id="perusahaan" name="perusahaan" placeholder="Input Nama Perusahaan" required>
                            </div>
                        </div>
                        <div class="row px-sm-4 px-0 mb-3">
                            <div class="col-lg-6 col-md-6 col-12">
                                <label for="dept" class="form-label link-sm">Departemen</label>
                                <input type="text" class="form-control link-sm" id="dept" name="dept" placeholder="Input Nama Departemen" required>
                            </div>
                            <div class="col-lg-6 col-md-6 col-12 mb-lg-0 mb-md-0 mb-3">
                                <label for="lahir" class="form-label link-sm">Tanggal Lahir</label>
                                <input type="date" class="form-control link-sm" id="lahir" name="lahir" required>
                            </div>
                        </div>
                        <div class="d-grid col-lg-12 px-4 mt-5 mb-2">
                            <button class="btn btn-primary" name="regis" type="submit">
                                Register
                            </button>
                            <p class="text-center mt-3 fw-semibold">Sudah punya akun? 
                                <a href="standard_login.php" class="link-underline link-underline-opacity-0">login disini</a>
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