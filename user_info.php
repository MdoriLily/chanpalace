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
$login_result = $check_login_query->fetch_assoc();
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
    <div class="container pt-3" style="margin-top:90px">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title text-center">ข้อมูลผู้ใช้งาน</h3>
            </div>
            <div class="card-body">
                <form action="user_info_edit.php" method="post">
                    <div class="mb-2">
                        <div class="mb-3">
                            <label for="name" class="form-label">ชื่อ นามสกุล</label>
                            <input type="text" class="form-control" name="name" id="name" maxlength="100" value="<?php echo $login_result['name'];?>">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">อีเมล</label>
                            <input type="text" class="form-control" name="email" id="email" maxlength="100" value="<?php echo $login_result['email'];?>">
                        </div>
                        <div class="mb-3">
                            <label for="phone_no" class="form-label">เบอร์โทรศัพท์</label>
                            <input type="text" class="form-control" name="phone_no" id="phone_no" maxlength="10" value="<?php echo $login_result['phone_no'];?>">
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">บันทึก</button>
                            <a href="index.php" class="btn btn-secondary">ย้อนกลับ</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>