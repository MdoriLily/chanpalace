<?php
session_start();
include("connection/database.php");
if (!isset($_SESSION['login_user'])) {
    header("location: login.php");
    exit;
}
$check_login_query = $mysqli->query("select * from user where user_id='{$_SESSION['login_user']}'");
if ($check_login_query->num_rows == 0) {
    header("location: logout.php");
    exit;
}
if (!isset($_GET['id'])) {
    header("location: booking.php");
    exit;
}
$error = "";
$success = false;
$id = $_GET['id'];
$check_query = $mysqli->query("select * from booking where booking_id='{$id}'");
if ($check_query->num_rows == 0) {
    $error = "ไม่มีรายการสั่งจองนี้ในฐานข้อมูล";
}
else {
    $booking_result = $check_query->fetch_assoc();
    if ($booking_result['id_card'] != "" && file_exists("img/id_card/".$booking_result['id_card'])) {
        unlink("../img/id_card/".$booking_result['id_card']);
    }
    if ($booking_result['slip'] != "" && file_exists("img/slip/".$booking_result['slip'])) {
        unlink("img/slip/".$booking_result['slip']);
    }
    if ($mysqli->query("delete from booking where booking_id='{$id}'")) {
        $mysqli->query("delete from booking_room where booking_id='{$id}'");
        $success = true;
        $error = "ยกเลิกห้องพักรียบร้อยแล้ว";
    }
    else {
        $error = "เกิดปัญหาการลบรายการสั่งจอง โปรดลองอีกครั้งครับ";
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
        window.location.href = "booking.php";
    </script>
</body>
</html>