<?php
session_start();
if (isset($_SESSION['login_user'])) {
    header("location: index.php");
    exit;
}
include("../connection/database.php");
$username = $_POST['username'];
$password = $_POST['password'];
$error = "";
$success = false;
if (!isset($username) || $username == "") {
    $error = "กรุณากรอกชื่อผู้ใช้ของคุณ";
}
else if (!isset($password) || $password == "") {
    $error = "กรุณากรอกรหัสผ่านของคุณ";
}
else {
    $login_query = $mysqli->query("select * from user where username='{$username}' and password='{$password}'");
    if ($login_query->num_rows > 0) {
        $login_result = $login_query->fetch_assoc();
        if ($login_result['level'] != "admin") {
            $error = "บัญชีผู้ใช้คุณไม่ใช่สิทธิ์ผู้ดูแลระบบ";
        }
        else {
            $_SESSION['login_user'] = $login_result['user_id'];
            $error = "ยินดีต้อนรับคุณ ".$login_result['name'];
            $success = true;
        }
    }
    else {
        $error = "ชื่อผู้ใช้หรือรหัสผ่านผิดพลาด โปรดลองใหม่อีกครั้ง";
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
        alert("<?php echo $error;?>");
        <?php
        if ($success) {
            echo "window.location.href = \"index.php\";";
        }
        else {
            echo "window.location.href = \"login.php\";";
        }
        ?>
    </script>
</body>
</html>