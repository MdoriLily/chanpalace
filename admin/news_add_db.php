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
                if ($mysqli->query("insert into news
                (user_id,title,content,picture,created)
                values('{$_SESSION['login_user']}','{$title}','{$content}','{$picture_name}','".date("Y-m-d H:i:s")."')")) {
                    $success = true;
                    $error = "เพิ่มประชาสัมพันธ์เรียบร้อยแล้ว";
                }
                else {
                    $error = "เกิดปัญหาการเพิ่มประชาสัมพันธ์ โปรดลองอีกครั้งครับ";
                }
            }
            else {
                $error = "อัปโหลดรูปภาพไม่สำเร็จ";
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
            echo "window.location.href = \"news.php\";";
        }
        else {
            echo "window.location.href = \"news_add.php\";";
        }
        ?>
    </script>
</body>
</html>