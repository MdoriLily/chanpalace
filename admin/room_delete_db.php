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
$error = "";
$success = false;
$id = $_GET['id'];
$check_query = $mysqli->query("select * from room where room_id='{$id}'");
if ($check_query->num_rows == 0) {
    $error = "ไม่มีห้องพักนี้ในฐานข้อมูล";
}
else {
    $room_result = $check_query->fetch_assoc();
    if ($room_result['picture'] != "" && file_exists("../img/room/".$room_result['picture'])) {
        unlink("../img/room/".$room_result['picture']);
    }
    if ($mysqli->query("delete from room where room_id='{$id}'")) {
        $success = true;
        $error = "ลบห้องพักเรียบร้อยแล้ว";
    }
    else {
        $error = "เกิดปัญหาการลบห้องพัก โปรดลองอีกครั้งครับ";
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
        window.location.href = "room.php";
    </script>
</body>
</html>