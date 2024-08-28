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
$check_in = $_POST['check_in'];
$name = $_POST['name'];
$phone_no = $_POST['phone_no'];
$id_card = $_POST['id_card'];
$note = $_POST['note'];
if (!isset($check_in) || $check_in == "") {
    $error = "กรุณากรอกวันที่เข้าพัก";
}
else if (!isset($name) || $name == "") {
    $error = "กรุณากรอกชื่อ นามสกุล";
}
else if (!isset($phone_no) || $phone_no == "") {
    $error = "กรุณากรอกเบอร์โทรศัพท์";
}
else if (!isset($id_card) || $id_card == "") {
    $error = "กรุณากรอกเลขประจำตัวประชาชน";
}
else {
    $d_check_in = date('Y-m-d', strtotime($check_in));
    $today = date('Y-m-d');
    if ($check_in < $today) {
        $error = "วันที่เข้าพักต้องไม่น้อยกว่าวันที่ปัจจุบัน";
    }
    else {
        $booking_register_query = $mysqli->query("select booking_register.*,room.room_name,room.beds,room.picture,room.daily_price,room.monthly_price
            from booking_register,room
            where booking_register.user_id='{$_SESSION['login_user']}' and booking_register.room_id=room.room_id
            order by booking_register.datetime asc");
        if ($booking_register_query->num_rows == 0) {
            $error = "ไม่พบรายการห้องพักที่เลือกไว้";
        }
        else {
            if ($mysqli->query("insert into booking
                (user_id,name,phone_no,status,check_in,note,id_card,created)
                values('{$_SESSION['login_user']}','{$name}','{$phone_no}','checking','{$check_in}','{$note}','{$id_card}','".date("Y-m-d H:i:s")."')")) {
                $booking_latest_query = $mysqli->query("select max(booking_id) as booking_id_latest from booking where user_id='{$_SESSION['login_user']}'");
                $booking_latest_result = $booking_latest_query->fetch_assoc();
                while ($booking_register_result = $booking_register_query->fetch_assoc()) {
                    $room_price = 0;
                    if ($booking_register_result['type'] == "daily") {
                        $room_price = $booking_register_result['daily_price'];
                    }
                    else {
                        $room_price = $booking_register_result['monthly_price'];
                    }
                    $price = $room_price * $booking_register_result['amount'];
                    if ($booking_register_result['extra_bed'] > 0) {
                        $price += 250 * $booking_register_result['amount'];
                    }
                    $mysqli->query("insert into booking_room
                        (booking_id,room_id,room_price,type,extra_bed,amount,total)
                        values('{$booking_latest_result['booking_id_latest']}','{$booking_register_result['room_id']}','{$room_price}','{$booking_register_result['type']}','{$booking_register_result['extra_bed']}','{$booking_register_result['amount']}','{$price}')");
                }
                if ($mysqli->query("delete from booking_register where user_id='{$_SESSION['login_user']}'")) {
                    $success = true;
                    $error = "ยืนยันการสั่งจองห้องพักเรียบร้อยแล้ว";
                }
                else {
                    $error = "เกิดปัญหาการยืนยันการสั่งจอง โปรดลองอีกครั้งครับ";
                }
            }
            else {
                $error = "เกิดปัญหาการยืนยันการสั่งจอง โปรดลองอีกครั้งครับ";
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
            echo "window.location.href = \"booking.php\";";
        }
        else {
            echo "window.location.href = \"booking_confirm.php\";";
        }
        ?>
    </script>
</body>
</html>