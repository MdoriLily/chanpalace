<?php
session_start();
include("connection/database.php");
if (!isset($_SESSION['login_user'])) {
    header("location: login.php");
    exit;
}
$check_login_query = $mysqli->query("select * from user where user_id='{$_SESSION['login_user']}'");
if ($check_login_query->num_rows == 0) {
    header("location: logout.php");
    exit;
}
$error = "";
$success = false;
$id = $_POST['id'];
$id_card = $_POST['id_card'];
$slip = $_FILES['slip'];
$check_query = $mysqli->query("select * from booking where booking_id='{$id}'");
if ($check_query->num_rows == 0) {
    $error = "ไม่มีรายการสั่งจองนี้ในฐานข้อมูล";
}
else {
    $booking_result = $check_query->fetch_assoc();
    if ($booking_result['status'] != "pending_payment") {
        $error = "การจองห้องพักไม่ใช่สถานะรอชำระเงิน";
    }
    else {
        if ($booking_result['id_card'] != $id_card) {
            $error = "เลขประจำตัวประชาชนไม่ถูกต้อง ไม่สามารถทำรายการได้";
        }
        else if (empty($slip['name'])) {
            $error = "กรุณาอัปโหลดรูปสลิปการชำระเงิน";
        }
        else {
            $slip_extension = strtolower(pathinfo($slip['name'], PATHINFO_EXTENSION));
            if ($slip_extension != "jpg" && $slip_extension != "png") {
                $error = "อัปโหลดไฟล์นามสกุล jpg และ png ได้เท่านั้น";
            }
            else {
                $slip_size = $slip['size'];
                if ($slip['size'] > 10485760) {
                    $error = "ไฟล์รูปภาพต้องมีขนาดไม่เกิน 10MB เท่านั้น";
                }
                else {
                    $slip_name = $_SESSION['login_user'].date("ymdHis").".".$slip_extension;
                    if (!is_dir("img/slip")) {
                        mkdir("img/slip", 0777, true);
                    }
                    if (move_uploaded_file($slip['tmp_name'], "img/slip/".$slip_name)) {
                        if ($mysqli->query("update booking set status='checking_payment',slip='{$slip_name}',uploaded_slip='".date("Y-m-d H:i:s")."' where booking_id='{$id}'")) {
                            $success = true;
                            $error = "อัปโหลดรูปสลิปการชำระเงินเรียบร้อยแล้ว โปรดติดตามสถานะการชำระเงิน";
                        }
                        else {
                            $error = "เกิดปัญหาการอัปโหลดรูปสลิปการชำระเงิน โปรดลองอีกครั้งครับ";
                        }
                    }
                    else {
                        $error = "อัปโหลดรูปภาพไม่สำเร็จ";
                    }
                }
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
        window.location.href = "booking_detail.php?id=<?php echo $id;?>";
    </script>
</body>
</html>