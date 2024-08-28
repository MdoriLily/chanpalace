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
$name = $_POST['name'];
$email = $_POST['email'];
$phone_no = $_POST['phone_no'];
$level = $_POST['level'];
$username = $_POST['username'];
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];
if (!isset($name) || $name == "") {
    $error = "กรุณากรอกชื่อ นามสกุล";
}
else if (!isset($email) || $email == "") {
    $error = "กรุณากรอกอีเมล";
}
else if (!isset($phone_no) || $phone_no == "") {
    $error = "กรุณากรอกเบอร์โทรศัพท์";
}
else if (!isset($username) || $username == "") {
    $error = "กรุณากรอกชื่อผู้ใช้";
}
else if (!isset($password) || $password == "") {
    $error = "กรุณากรอกรหัสผ่าน";
}
else if (!isset($confirm_password) || $confirm_password == "") {
    $error = "กรุณายืนยันรหัสผ่าน";
}
else if ($password != $confirm_password) {
    $error = "การยืนยันรหัสผ่านไม่ตรงกัน";
}
else {
    $check_email_query = $mysqli->query("select * from user where email='{$email}'");
    if ($check_email_query->num_rows > 0) {
        $error = "มีอีเมลนี้ในระบบแล้ว กรุณาใช้อีเมลอื่น";
    }
    else {
        $check_username_query = $mysqli->query("select * from user where username='{$username}'");
        if ($check_username_query->num_rows > 0) {
            $error = "มีชื่อผู้ใช้นี้ในระบบแล้ว กรุณาใช้ชื่อผู้ใช้อื่น";
        }
        else {
            if ($mysqli->query("insert into user
            (level,username,password,name,email,phone_no,register)
            values('{$level}','{$username}','{$password}','{$name}','{$email}','{$phone_no}','".date("Y-m-d H:i:s")."')")) {
                $success = true;
                $error = "เพิ่มบัญชีผู้ใช้งานเรียบร้อยแล้ว";
            }
            else {
                $error = "เกิดปัญหาการเพิ่มบัญชีผู้ใช้งาน โปรดลองอีกครั้งครับ";
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
        <?php
        if ($success) {
            echo "window.location.href = \"user.php\";";
        }
        else {
            echo "window.location.href = \"user_add.php\";";
        }
        ?>
    </script>
</body>
</html>