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
if ($mysqli->query("delete from booking_register where user_id='{$_SESSION['login_user']}'")) {
    $error = "ยกเลิกรายการจองห้องพักเรียบร้อยแล้ว";
    $success = true;
}
else {
    $error = "เกิดปัญหาการลบทุกรายการ โปรดลองอีกครั้งครับ";
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
        window.location.href = "booking_register.php";
    </script>
</body>
</html>