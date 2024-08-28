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
    header("location: news.php");
    exit;
}
$error = "";
$success = false;
$id = $_GET['id'];
$check_query = $mysqli->query("select * from news where news_id='{$id}'");
if ($check_query->num_rows == 0) {
    $error = "ไม่มีประชาสัมพันธ์นี้ในฐานข้อมูล";
}
else {
    $news_result = $check_query->fetch_assoc();
    if ($news_result['picture'] != "" && file_exists("../img/news/".$news_result['picture'])) {
        unlink("../img/news/".$news_result['picture']);
    }
    if ($mysqli->query("delete from news where news_id='{$id}'")) {
        $success = true;
        $error = "ลบประชาสัมพันธ์เรียบร้อยแล้ว";
    }
    else {
        $error = "เกิดปัญหาการลบประชาสัมพันธ์ โปรดลองอีกครั้งครับ";
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
        window.location.href = "news.php";
    </script>
</body>
</html>