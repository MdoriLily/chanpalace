<?php
session_start();
if (!isset($_SESSION['login_user'])) {
    header("location: index.php");
    exit;
}
include("../connection/database.php");
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
$name = $_POST['name'];
$email = $_POST['email'];
$phone_no = $_POST['phone_no'];
if (!isset($name) || $name == "") {
    $error = "กรุณากรอกชื่อ นามสกุลของคุณ";
}
else if (!isset($email) || $email == "") {
    $error = "กรุณากรอกอีเมลของคุณ";
}
else if (!isset($phone_no) || $phone_no == "") {
    $error = "กรุณากรอกเบอร์โทรศัพท์ของคุณ";
}
else {
    $check_email_query = $mysqli->query("select * from user where email='{$email}' and user_id!='{$_SESSION['login_user']}'");
    if ($check_email_query->num_rows > 0) {
        $error = "มีอีเมลนี้ในระบบแล้ว กรุณาใช้อีเมลอื่น";
    }
    else {
        if ($mysqli->query("update user set
        name='{$name}',email='{$email}',phone_no='{$phone_no}'
        where user_id='{$_SESSION['login_user']}'")) {
            $success = true;
            $error = "แก้ไขข้อมูลผู้ใช้เรียบร้อยแล้ว";
        }
        else {
            $error = "เกิดปัญหาการแก้ไขข้อมูลผู้ใช้ โปรดลองอีกครั้งครับ";
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
        window.location.href = "user_info.php";
    </script>
</body>
</html>