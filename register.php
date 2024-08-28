<?php
session_start();
include("connection/database.php");
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
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-theme.css" rel="stylesheet">
    <link href="css/app.css" rel="stylesheet">
    <link href="css/all.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <script src="js/jquery.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</head>
<body>
    <?php
    include("header.php");
    ?>
    <div class="h-100vh" style="background-image:url('img/bg-index.jpg');background-size:cover;background-position:center">
        <div class="container">
            <div class="row d-flex align-items-center justify-content-center h-100vh">
                <div class="card" style="margin-top:90px;max-width:400px">
                    <div class="card-body">
                        <h3 class="card-title text-center">สมัครสมาชิก</h3>
                        <form method="post" action="register_add.php">
                            <div class="mb-3">
                                <label for="name" class="form-label">ชื่อ นามสกุล</label>
                                <input type="text" class="form-control" name="name" id="name" maxlength="100">
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">อีเมล</label>
                                <input type="email" class="form-control" name="email" id="email" maxlength="100">
                            </div>
                            <div class="mb-3">
                                <label for="phone_no" class="form-label">เบอร์โทรศัพท์</label>
                                <input type="text" class="form-control" name="phone_no" id="phone_no" maxlength="10">
                            </div>
                            <div class="mb-3">
                                <label for="username" class="form-label">ชื่อผู้ใช้</label>
                                <input type="text" class="form-control" name="username" id="username" maxlength="30">
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">รหัสผ่าน</label>
                                <input type="password" class="form-control" name="password" id="password" maxlength="30">
                            </div>
                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">ยืนยันรหัสผ่าน</label>
                                <input type="password" class="form-control" name="confirm_password" id="confirm_password" maxlength="30">
                            </div>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">สมัครสมาชิก</button>
                                <a href="index.php" class="btn btn-danger"><i class="fa-solid fa-angle-left"></i> กลับหน้าแรก</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    include("footer.php");
    ?>
</body>
</html>