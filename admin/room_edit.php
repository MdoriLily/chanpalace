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
    header("location: room.php");
    exit;
}
$id = $_GET['id'];
$room_query = $mysqli->query("select * from room where room_id='{$id}'");
if ($room_query->num_rows == 0) {
    header("location: room.php");
    exit;
}
$room_result = $room_query->fetch_array();
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
                    <h4>แก้ไขห้องพัก</h4>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-md-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php">หน้าแรก</a></li>
                            <li class="breadcrumb-item"><a href="room.php">ห้องพัก</a></li>
                            <li class="breadcrumb-item active" aria-current="page">แก้ไขห้องพัก</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <div class="card mt-3">
            <div class="card-body">
                <form method="post" action="room_edit_db.php" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?php echo $room_result['room_id'];?>">
                    <div class="mb-3">
                        <label for="room_name" class="form-label">ชื่อห้อง</label>
                        <input type="text" class="form-control" name="room_name" id="room_name" maxlength="50" value="<?php echo $room_result['room_name'];?>">
                    </div>
                    <div class="mb-3">
                        <label for="detail" class="form-label">รายละเอียด</label>
                        <textarea class="form-control" name="detail" id="detail"><?php echo $room_result['detail'];?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="daily_price" class="form-label">ราคารายวัน</label>
                        <input type="text" class="form-control" name="daily_price" id="daily_price" min="0" step="1" value="<?php echo $room_result['daily_price'];?>">
                    </div>
                    <div class="mb-3">
                        <label for="monthly_price" class="form-label">ราคารายเดือน</label>
                        <input type="text" class="form-control" name="monthly_price" id="monthly_price" min="0" step="1" value="<?php echo $room_result['monthly_price'];?>">
                    </div>
                    <div class="mb-3">
                        <label for="beds" class="form-label">จำนวนเตียง</label>
                        <input type="number" class="form-control" name="beds" id="beds" min="1" step="1" value="<?php echo $room_result['beds'];?>">
                    </div>
                    <div class="mb-3">
                        <label for="picture" class="form-label">เปลี่ยนรูปภาพ</label>
                        <input class="form-control" type="file" name="picture" id="picture">
                    </div>
                    <?php
                    if ($room_result['picture'] != "") {
                        ?>
                        <div class="mb-3">
                            <img src="../img/room/<?php echo $room_result['picture'];?>" class="img-thumbnail" style="width:200px">
                        </div>
                        <?php
                    }
                    ?>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">แก้ไขห้องพัก</button>
                        <a href="room.php" class="btn btn-secondary">ย้อนกลับ</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>