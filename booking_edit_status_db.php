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
if (!isset($_GET['id'])) {
    header("location: booking.php");
    exit;
}
$error = "";
$success = false;
$id = $_GET['id'];
$check_query = $mysqli->query("select * from booking where booking_id='{$id}'");
if ($check_query->num_rows == 0) {
    $error = "ไม่มีรายการสั่งจองนี้ในฐานข้อมูล";
}
else {
    $booking_result = $check_query->fetch_assoc();
    $status = $_GET['status'];
    if (!isset($status) || $status == "") {
        $error = "โปรดระบุสถานะการจองห้องพัก";
    }
    else {
        if (($booking_result['status'] == "checking" ||
        $booking_result['status'] == "canceled") && $status == "success") {
            $error = "ไม่สามารถเปลี่ยนเป็นสถานะสำเร็จแล้วได้";
        }
        else if ($booking_result['status'] == "success" && $status == "canceled") {
            $error = "ไม่สามารถเปลี่ยนเป็นสถานะยกเลิกได้";
        }
        else if (($booking_result['status'] == "pending_payment" ||
        $booking_result['status'] == "success" ||
        $booking_result['status'] == "canceled") && $status == "checking") {
            $error = "ไม่สามารถเปลี่ยนเป็นสถานะรอตรวจสอบได้";
        }
        else if (($booking_result['status'] == "success" ||
        $booking_result['status'] == "canceled") && $status == "pending_payment") {
            $error = "ไม่สามารถเปลี่ยนเป็นสถานะรอชำระเงินได้";
        }
        else {
            $updated = false;
            if ($status == "pending_payment") {
                $updated = $mysqli->query("update booking set status='{$status}',confirmed_by='{$_SESSION['login_user']}' where booking_id='{$booking_result['booking_id']}'");
            }
            else if ($status == "success") {
                $updated = $mysqli->query("update booking set status='{$status}',check_payment_by='{$_SESSION['login_user']}' where booking_id='{$booking_result['booking_id']}'");
            }
            else if ($status == "canceled") {
                $updated = $mysqli->query("update booking set status='{$status}',canceled_by='{$_SESSION['login_user']}' where booking_id='{$booking_result['booking_id']}'");
            }
            else if ($status == "checking" || $status == "reset") {
                if ($booking_result['slip'] != "" && file_exists("img/slip/".$booking_result['slip'])) {
                    unlink("img/slip/".$booking_result['slip']);
                }
                $updated = $mysqli->query("update booking set status='checking',slip='',uploaded_slip='0000-00-00 00:00:00',confirmed_by=0,check_payment_by=0,canceled_by=0 where booking_id='{$booking_result['booking_id']}'");
            }
            if ($updated) {
                $success = true;
                $error = "ยกเลิกห้องพักเรียบร้อยแล้ว";
            }
            else {
                $error = "เกิดปัญหาการเปลี่ยนสถานะ โปรดลองอีกครั้งครับ";
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
        window.location.href = "booking.php?id=<?php echo $booking_result['booking_id'];?>";
    </script>
</body>
</html>