<?php
session_start();
if (isset($_SESSION['login_user'])) {
    header("location: index.php");
    exit;
}
include("connection/database.php");
$error = "";
$success = false;
$name = $_POST['name'];
$email = $_POST['email'];
$phone_no = $_POST['phone_no'];
$username = $_POST['username'];
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];
if (!isset($name) || $name == "") {
    $error = "กรุณากรอกชื่อ นามสกุลของคุณ";
}
else if (!isset($email) || $email == "") {
    $error = "กรุณากรอกอีเมลของคุณ";
}
else if (!isset($phone_no) || $phone_no == "") {
    $error = "กรุณากรอกเบอร์โทรศัพท์ของคุณ";
}
else if (!isset($username) || $username == "") {
    $error = "กรุณากรอกชื่อผู้ใช้ของคุณ";
}
else if (!isset($password) || $password == "") {
    $error = "กรุณากรอกรหัสผ่านของคุณ";
}
else if (!isset($confirm_password) || $confirm_password == "") {
    $error = "กรุณายืนยันรหัสผ่านของคุณ";
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
            values('customer','{$username}','{$password}','{$name}','{$email}','{$phone_no}','".date("Y-m-d H:i:s")."')")) {
                $success = true;
                $error = "สมัครสมาชิกเรียบร้อยแล้ว";
            }
            else {
                $error = "เกิดปัญหาการสมัครสมาชิก โปรดลองอีกครั้งครับ";
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
            echo "window.location.href = \"index.php\";";
        }
        else {
            echo "window.location.href = \"register.php\";";
        }
        ?>
    </script>
</body>
</html>