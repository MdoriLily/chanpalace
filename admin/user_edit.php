<?php
session_start();
include("../connection/database.php");
if (!isset($_SESSION['login_user'])) {
    header("location: login.php");
    exit;
}
$check_login_query = $mysqli->query("select * from user where user_id='{$_SESSION['login_user']}'");
if ($check_login_query->num_rows == 0) {
    header("location: logout.php");
    exit;
}
$login_result = $check_login_query->fetch_assoc();
if ($login_result['level'] != "admin") {
    header("location: ../index.php");
    exit;
}
if (!isset($_GET['id'])) {
    header("location: user.php");
    exit;
}
$id = $_GET['id'];
$user_query = $mysqli->query("select * from user where user_id='{$id}'");
if ($user_query->num_rows == 0) {
    header("location: user.php");
    exit;
}
$user_result = $user_query->fetch_array();
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
    <?php
    include("header.php");
    $mysqli->close();
    ?>
    <div class="container pt-3" style="margin-top:90px">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h4>แก้ไขบัญชีผู้ใช้งาน</h4>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-md-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php">หน้าแรก</a></li>
                            <li class="breadcrumb-item"><a href="user.php">บัญชีผู้ใช้งาน</a></li>
                            <li class="breadcrumb-item active" aria-current="page">แก้ไขบัญชีผู้ใช้งาน</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <div class="card mt-3">
            <div class="card-body">
                <form method="post" action="user_edit_db.php">
                    <input type="hidden" name="id" value="<?php echo $user_result['user_id'];?>">
                    <div class="mb-3">
                        <label for="name" class="form-label">ชื่อ นามสกุล</label>
                        <input type="text" class="form-control" name="name" id="name" maxlength="100" value="<?php echo $user_result['name'];?>">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">อีเมล</label>
                        <input type="email" class="form-control" name="email" id="email" maxlength="100" value="<?php echo $user_result['email'];?>">
                    </div>
                    <div class="mb-3">
                        <label for="phone_no" class="form-label">เบอร์โทรศัพท์</label>
                        <input type="text" class="form-control" name="phone_no" id="phone_no" maxlength="10" value="<?php echo $user_result['phone_no'];?>">
                    </div>
                    <div class="mb-3">
                        <label for="level" class="form-label">ระดับผู้ใช้งาน</label>
                        <select class="form-control" name="level" id="level">
                            <option value="customer" <?php if ($user_result['level'] == "customer") { echo "selected"; } ?>>ลูกค้า</option>
                            <option value="admin" <?php if ($user_result['level'] == "admin") { echo "selected"; } ?>>ผู้ดูแลระบบ</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="username" class="form-label">ชื่อผู้ใช้</label>
                        <input type="text" class="form-control" name="username" id="username" maxlength="30" value="<?php echo $user_result['username'];?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">เปลี่ยนรหัสผ่าน</label>
                        <input type="password" class="form-control" name="password" id="password" maxlength="30">
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">แก้ไขบัญชีผู้ใช้งาน</button>
                        <a href="user.php" class="btn btn-secondary">ย้อนกลับ</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>