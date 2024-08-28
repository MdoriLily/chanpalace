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
else if (empty($picture['name'])) {
    $error = "กรุณาอัปโหลดรูปภาพ";
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
            if (move_uploaded_file($picture['tmp_name'], "../img/room/".$picture_name)) {
                if ($mysqli->query("insert into room
                (room_name,detail,picture,daily_price,monthly_price,beds)
                values('{$room_name}','{$detail}','{$picture_name}','{$daily_price}','{$monthly_price}','{$beds}')")) {
                    $success = true;
                    $error = "เพิ่มห้องพักเรียบร้อยแล้ว";
                }
                else {
                    $error = "เกิดปัญหาการเพิ่มห้องพัก โปรดลองอีกครั้งครับ";
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
            echo "window.location.href = \"room.php\";";
        }
        else {
            echo "window.location.href = \"room_add.php\";";
        }
        ?>
    </script>
</body>
</html>