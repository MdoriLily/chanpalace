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
$title = $_POST['title'];
$content = $_POST['content'];
$picture = $_FILES['picture'];
if (!isset($title) || $title == "") {
    $error = "กรุณากรอกชื่อเรื่องประชาสัมพันธ์";
}
else if (!isset($content) || $content == "") {
    $error = "กรุณากรอกเนื้อหาประชาสัมพันธ์";
}
else {
    $check_query = $mysqli->query("select * from news where news_id='{$id}'");
    if ($check_query->num_rows == 0) {
        $error = "ไม่มีประชาสัมพันธ์นี้ในฐานข้อมูล";
    }
    else {
        if (!empty($picture['name'])) {
            $picture_extension = strtolower(pathinfo($picture['name'], PATHINFO_EXTENSION));
            if ($picture_extension != "jpg" && $picture_extension != "png") {
                $error = "อัปโหลดไฟล์นามสกุล jpg และ png ได้เท่านั้น";
            }
            else {
                $picture_size = $picture['size'];
                if ($picture['size'] > 10485760) {
                    $error = "ไฟล์รูปภาพต้องมีขนาดไม่เกิน 10MB เท่านั้น";
                }
                else {
                    $picture_name = $_SESSION['login_user'].date("ymdHis").".".$picture_extension;
                    if (move_uploaded_file($picture['tmp_name'], "../img/news/".$picture_name)) {
                        if ($mysqli->query("update news set picture='{$picture_name}' where news_id='{$id}'")) {
                            $news_result = $check_query->fetch_array();
                            if (file_exists("../img/news/".$news_result['picture'])) {
                                unlink("../img/news/".$news_result['picture']);
                            }
                        }
                        else {
                            $error = "เกิดปัญหาการอัปเดทรูปภาพ โปรดลองอีกครั้งครับ";
                        }
                    }
                    else {
                        $error = "อัปโหลดรูปภาพไม่สำเร็จ";
                    }
                }
            }
        }
        if ($mysqli->query("update news set title='{$title}',content='{$content}' where news_id='{$id}'")) {
            $success = true;
            $error = "แก้ไขประชาสัมพันธ์เรียบร้อยแล้ว";
        }
        else {
            $error = "เกิดปัญหาการแก้ไขประชาสัมพันธ์ โปรดลองอีกครั้งครับ";
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
        window.location.href = "news_edit.php?id=<?php echo $id;?>";
    </script>
</body>
</html>