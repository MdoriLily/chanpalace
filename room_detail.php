<?php
session_start();
include("connection/database.php");
if (isset($_SESSION['login_user'])) {
    $check_login_query = $mysqli->query("select * from user where user_id='{$_SESSION['login_user']}'");
    if ($check_login_query->num_rows == 0) {
        header("location: logout.php");
        exit;
    }
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
$room_result = $room_query->fetch_assoc();
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
    $mysqli->close();
    ?>
    <div class="h-100vh">
        <div class="container pt-3" style="margin-top:90px">
            <div class="card">
                <div class="card-body">
                    <div class="row g-5">
                        <div class="col-lg-5">
                            <img src="img/room/<?php echo $room_result['picture'];?>" class="img-fluid rounded">
                        </div>
                        <div class="col-lg-7">
                            <h3><?php echo $room_result['room_name'];?></h3>
                            <p><?php echo nl2br($room_result['detail']);?></p>
                            <p><i class="fa-solid fa-bed"></i> <?php echo $room_result['beds'];?> เตียง (เสริมได้ 1 เตียง)</p>
                            <h4 class="text-primary">
                                <?php
                                if ($_GET['type'] == "daily") {
                                    echo number_format($room_result['daily_price'],0)." <small>บาท/วัน</small>";
                                }
                                else {
                                    echo number_format($room_result['monthly_price'],0)." <small>บาท/เดือน</small>";
                                }
                                ?>
                            </h4>
                        </div>
                    </div>
                    <div class="d-grid gap-2 pt-3">
                        <a href="room.php" class="btn btn-secondary">ย้อนกลับ</a>
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