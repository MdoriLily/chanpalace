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
$status_total = 0;
$status_checking = 0;
$status_pending_payment = 0;
$status_checking_payment = 0;
$status_success = 0;
$status_canceled = 0;
$booking_all_query = $mysqli->query("select * from booking");
if ($booking_all_query->num_rows > 0) {
    $status_total = $booking_all_query->num_rows;
    while ($booking_all_result = $booking_all_query->fetch_assoc()) {
        if ($booking_all_result['status'] == "checking") {
            $status_checking++;
        }
        else if ($booking_all_result['status'] == "checking_payment") {
            $status_checking_payment++;
        }
        else if ($booking_all_result['status'] == "pending_payment") {
            $status_pending_payment++;
        }
        else if ($booking_all_result['status'] == "canceled") {
            $status_canceled++;
        }
        else {
            $status_success++;
        }
    }
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
    $mysqli->close();
    ?>
    <div class="container pt-3" style="margin-top:90px">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h4>หน้าแรก</h4>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-md-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item active" aria-current="page">หน้าแรก</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <div class="row row-cols-1 row-cols-md-3 g-4">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fa-solid fa-file-invoice"></i> สั่งจองทั้งหมด</h5>
                        <h1 class="card-text"><?php echo number_format($status_total,0)?></h1>
                    </div>
                    <div class="card-footer p-2">
                        <div class="d-grid gap-2">
                            <a href="booking.php" class="btn btn-light stretched-link">ดูรายการ</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fa-solid fa-id-card"></i> รอตรวจสอบ</h5>
                        <h1 class="card-text"><?php echo number_format($status_checking,0)?></h1>
                    </div>
                    <div class="card-footer p-2">
                        <div class="d-grid gap-2">
                            <a href="booking.php?status=checking" class="btn btn-secondary stretched-link">ดูรายการ</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fa-solid fa-hand-holding-dollar"></i> รอชำระเงิน</h5>
                        <h1 class="card-text"><?php echo number_format($status_pending_payment,0)?></h1>
                    </div>
                    <div class="card-footer p-2">
                        <div class="d-grid gap-2">
                            <a href="booking.php?status=pending_payment" class="btn btn-primary stretched-link">ดูรายการ</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fa-solid fa-money-check-dollar"></i> รอตรวจสอบการชำระ</h5>
                        <h1 class="card-text"><?php echo number_format($status_checking_payment,0)?></h1>
                    </div>
                    <div class="card-footer p-2">
                        <div class="d-grid gap-2">
                            <a href="booking.php?status=checking_payment" class="btn btn-warning text-dark stretched-link">ดูรายการ</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fa-solid fa-circle-check"></i> จองสำเร็จแล้ว</h5>
                        <h1 class="card-text"><?php echo number_format($status_success,0)?></h1>
                    </div>
                    <div class="card-footer p-2">
                        <div class="d-grid gap-2">
                            <a href="booking.php?status=success" class="btn btn-success stretched-link">ดูรายการ</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fa-solid fa-xmark"></i> ยกเลิกแล้ว</h5>
                        <h1 class="card-text"><?php echo number_format($status_canceled,0)?></h1>
                    </div>
                    <div class="card-footer p-2">
                        <div class="d-grid gap-2">
                            <a href="booking.php?status=canceled" class="btn btn-danger stretched-link">ดูรายการ</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>