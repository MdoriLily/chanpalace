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
$room_name = $_POST['room_name'];
$detail = $_POST['detail'];
$daily_price = $_POST['daily_price'];
$monthly_price = $_POST['monthly_price'];
$beds = $_POST['beds'];
$picture = $_FILES['picture'];
if (!isset($room_name) || $room_name == "") {
    $error = "กรุณากรอกชื่อห้องพัก";
}
else if (!isset($detail) || $detail == "") {
    $error = "กรุณากรอกรายละเอียดห้องพัก";
}
else if (!isset($daily_price) || $daily_price == "") {
    $error = "กรุณากรอกราคารายวัน";
}
else if ($daily_price < 0) {
    $error = "ราคารายวันต้องไม่น้อยกว่าศูนย์";
}
else if (!isset($beds) || $beds == "") {
    $error = "กรุณากรอกจำนวนเตียง";
}
else if ($beds < 1) {
    $error = "จำนวนเตียงต้องไม่น้อยกว่าหนึ่ง";
}
else {
    $check_query = $mysqli->query("select * from room where room_id='{$id}'");
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
                    if (move_uploaded_file($picture['tmp_name'], "../img/room/".$picture_name)) {
                        if ($mysqli->query("update room set picture='{$picture_name}' where room_id='{$id}'")) {
                            $room_result = $check_query->fetch_array();
                            if (file_exists("../img/room/".$news_result['picture'])) {
                                unlink("../img/room/".$room_result['picture']);
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
        if ($mysqli->query("update room set room_name='{$room_name}',detail='{$detail}',daily_price='{$daily_price}',monthly_price='{$monthly_price}',beds='{$beds}' where room_id='{$id}'")) {
            $success = true;
            $error = "แก้ไขห้องพักเรียบร้อยแล้ว";
        }
        else {
            $error = "เกิดปัญหาการแก้ไขห้องพัก โปรดลองอีกครั้งครับ";
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
        window.location.href = "room_edit.php?id=<?php echo $id;?>";
    </script>
</body>
</html>