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
$current_password = $_POST['current_password'];
$new_password = $_POST['new_password'];
$confirm_password = $_POST['confirm_password'];
if (!isset($current_password) || $current_password == "") {
    $error = "กรุณากรอกรหัสปัจจุบันของคุณ";
}
else if (!isset($new_password) || $new_password == "") {
    $error = "กรุณากรอกรหัสผ่านใหม่ของคุณ";
}
else if (!isset($confirm_password) || $confirm_password == "") {
    $error = "กรุณายืนยันรหัสผ่านของคุณ";
}
else if ($current_password == $new_password) {
    $error = "รหัสผ่านใหม่ของคุณต้องไม่ตรงกับรหัสปัจจุบัน";
}
else if ($new_password != $confirm_password) {
    $error = "การยืนยันรหัสผ่านไม่ตรงกัน";
}
else {
    $check_password_query = $mysqli->query("select * from user where user_id='{$_SESSION['login_user']}' and password='{$current_password}'");
    if ($check_password_query->num_rows == 0) {
        $error = "รหัสปัจจุบันของคุณไม่ถูกต้อง";
    }
    else {
        if ($mysqli->query("update user set password='{$new_password}' where user_id='{$_SESSION['login_user']}'")) {
            $error = "เปลี่ยนรหัสผ่านเรียบร้อยแล้ว";
        }
        else {
            $error = "เกิดปัญหาการเปลี่ยนรหัสผ่าน โปรดลองอีกครั้งครับ";
        }
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
        window.location.href = "change_password.php";
    </script>
</body>
</html>