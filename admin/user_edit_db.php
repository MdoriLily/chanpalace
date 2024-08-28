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
$error = "";
$success = false;
$id = $_POST['id'];
$name = $_POST['name'];
$email = $_POST['email'];
$phone_no = $_POST['phone_no'];
$level = $_POST['level'];
$password = $_POST['password'];
if (!isset($name) || $name == "") {
    $error = "กรุณากรอกชื่อ นามสกุล";
}
else if (!isset($email) || $email == "") {
    $error = "กรุณากรอกอีเมล";
}
else if (!isset($phone_no) || $phone_no == "") {
    $error = "กรุณากรอกเบอร์โทรศัพท์";
}
else {
    $check_query = $mysqli->query("select * from user where user_id='{$id}'");
    if ($check_query->num_rows == 0) {
        $error = "ไม่มีบัญชีผู้ใช้งานนี้ในฐานข้อมูล";
    }
    else {
        $check_email_query = $mysqli->query("select * from user where email='{$email}' and user_id!='{$id}'");
        if ($check_email_query->num_rows > 0) {
            $error = "มีอีเมลนี้ในระบบแล้ว กรุณาใช้อีเมลอื่น";
        }
        else {
            if (isset($password) && $password != "") {
                $mysqli->query("update user set password='{$password}' where user_id='{$id}'");
            }
            if ($mysqli->query("update user set level='{$level}',name='{$name}',email='{$email}',phone_no='{$phone_no}' where user_id='{$id}'")) {
                $success = true;
                $error = "แก้ไขบัญชีผู้ใช้งานเรียบร้อยแล้ว";
            }
            else {
                $error = "เกิดปัญหาการแก้ไขบัญชีผู้ใช้งาน โปรดลองอีกครั้งครับ";
            }
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
        window.location.href = "user_edit.php?id=<?php echo $id;?>";
    </script>
</body>
</html>