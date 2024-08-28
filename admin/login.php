<?php
session_start();
include("../connection/database.php");
if (isset($_SESSION['login_user'])) {
    header("location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>โรงแรมจันทร์พาเลส</title>
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/bootstrap-theme.css" rel="stylesheet">
    <link href="../css/app.css" rel="stylesheet">
    <link href="../css/all.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
    <script src="../js/jquery.min.js"></script>
    <script src="../js/popper.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
</head>
<body>
    <div class="h-100vh" style="background-image:url('../img/bg-index.jpg');background-size:cover;background-position:center">
        <div class="container">
            <div class="row d-flex align-items-center justify-content-center h-100vh">
                <div class="card" style="margin-top:90px;max-width:400px">
                    <div class="card-body">
                        <h3 class="card-title text-center">ส่วนผู้ดูแลระบบ</h3>
                        <form method="post" action="check_login.php">
                            <div class="mb-3">
                                <label for="username" class="form-label"><i class="fa-solid fa-user"></i> ชื่อผู้ใช้</label>
                                <input type="text" class="form-control" name="username" id="username" maxlength="30">
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label"><i class="fa-solid fa-key"></i> รหัสผ่าน</label>
                                <input type="password" class="form-control" name="password" id="password" maxlength="30">
                            </div>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">เข้าสู่ระบบ</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>