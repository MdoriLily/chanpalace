<?php
session_start();
if (!isset($_SESSION['login_user'])) {
    header("location: index.php");
    exit;
}
include("connection/database.php");
$check_login_query = $mysqli->query("select * from user where user_id='{$_SESSION['login_user']}'");
if ($check_login_query->num_rows == 0) {
    header("location: logout.php");
    exit;
}
$error = "";
$success = false;
$room_id = $_POST['room_id'];
$type = $_POST['type'];
$extra_beds = 0;
if (isset($_POST['extra_beds'])) {
    $extra_beds = 1;
}
$amount = $_POST['amount'];
if (!isset($amount) || $amount == "") {
    if ($type == "daily") {
        $error = "กรุณากรอกจำนวนวัน";
    }
    else {
        $error = "กรุณากรอกจำนวนเดือน";
    }
}
else if ($amount < 1) {
    if ($type == "daily") {
        $error = "จำนวนวันต้องไม่น้อยกว่าหนึ่งวัน";
    }
    else {
        $error = "จำนวนเดือนต้องไม่น้อยกว่าหนึ่งเดือน";
    }
}
else {
    if ($mysqli->query("insert into booking_register
    (user_id,datetime,room_id,type,extra_bed,amount)
    values('{$_SESSION['login_user']}','".date("Y-m-d H:i:s")."','{$room_id}','{$type}','{$extra_beds}','{$amount}')")) {
        $error = "เพิ่มการจองห้องพักเรียบร้อยแล้ว โปรดตรวจสอบก่อนยืนยันการสั่งจอง";
        $success = true;
    }
    else {
        $error = "เกิดปัญหาการจองห้องพัก โปรดลองอีกครั้งครับ";
    }
}
$mysqli->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>โรงแรมจันทร์พาเลส</title>
</head>
<body>
    <script>
        alert('<?php echo $error;?>');
        window.location.href = "prebooking.php";
    </script>
</body>
</html>