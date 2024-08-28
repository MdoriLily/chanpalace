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
    ?>
    <div class="container pt-3" style="margin-top:90px">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h4>เพิ่มประชาสัมพันธ์</h4>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-md-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php">หน้าแรก</a></li>
                            <li class="breadcrumb-item"><a href="news.php">ประชาสัมพันธ์</a></li>
                            <li class="breadcrumb-item active" aria-current="page">เพิ่มประชาสัมพันธ์</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <div class="card mt-3">
            <div class="card-body">
                <form method="post" action="news_add_db.php" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="title" class="form-label">ชื่อเรื่อง</label>
                        <input type="text" class="form-control" name="title" id="title" maxlength="200">
                    </div>
                    <div class="mb-3">
                        <label for="content" class="form-label">เนื้อหา</label>
                        <textarea class="form-control" name="content" id="content"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="picture" class="form-label">รูปภาพ</label>
                        <input class="form-control" type="file" name="picture" id="picture">
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">เพิ่มประชาสัมพันธ์</button>
                        <a href="news.php" class="btn btn-secondary">ย้อนกลับ</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>