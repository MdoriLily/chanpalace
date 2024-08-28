<?php
session_start();
include("connection/database.php");
if (!isset($_SESSION['login_user'])) {
    header("location: index.php");
    exit;
}
$check_login_query = $mysqli->query("select * from user where user_id='{$_SESSION['login_user']}'");
if ($check_login_query->num_rows == 0) {
    header("location: logout.php");
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
    <div class="h-100vh">
        <div class="container pt-3" style="margin-top:90px">
            <div class="row">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title text-center">เปลี่ยนรหัสผ่าน</h3>
                        <form method="post" action="update_password.php">
                            <div class="mb-3">
                                <label for="current_password" class="form-label">รหัสปัจจุบัน</label>
                                <input type="password" class="form-control" name="current_password" id="current_password" maxlength="30">
                            </div>
                            <div class="mb-3">
                                <label for="new_password" class="form-label">รหัสผ่านใหม่</label>
                                <input type="password" class="form-control" name="new_password" id="new_password" maxlength="30">
                            </div>
                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">ยืนยันรหัสผ่าน</label>
                                <input type="password" class="form-control" name="confirm_password" id="confirm_password" maxlength="30">
                            </div>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">เปลี่ยนรหัสผ่าน</button>
                                <a href="index.php" class="btn btn-secondary">ย้อนกลับ</a>
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