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
    header("location: member.php");
    exit;
}
$error = "";
$success = false;
$id = $_GET['id'];
$check_query = $mysqli->query("select * from user where user_id='{$id}'");
if ($check_query->num_rows == 0) {
    $error = "ไม่มีบัญชีผู้ใช้งานนี้ในฐานข้อมูล";
}
else {
    if ($mysqli->query("delete from user where user_id='{$id}'")) {
        $success = true;
        $error = "ลบบัญชีผู้ใช้งานเรียบร้อยแล้ว";
    }
    else {
        $error = "เกิดปัญหาการลบบัญชีผู้ใช้งาน โปรดลองอีกครั้งครับ";
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
        window.location.href = "user.php";
    </script>
</body>
</html>