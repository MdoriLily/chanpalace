<?php
session_start();
include("connection/database.php");
if (!isset($_SESSION['login_user'])) {
    header("location: index.php");
    exit;
}
$check_login_query = $mysqli->query("select * from user where user_id='{$_SESSION['login_user']}'");
if ($check_login_query->num_rows == 0) {
    header("location: logout.php");
    exit;
}
$booking_register_query = $mysqli->query("select booking_register.*,room.room_name,room.beds,room.picture,room.daily_price,room.monthly_price
    from booking_register,room
    where booking_register.user_id='{$_SESSION['login_user']}' and booking_register.room_id=room.room_id
    order by booking_register.datetime asc");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>โรงแรมจันทร์พาเลส</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-theme.css" rel="stylesheet">
    <link href="css/app.css" rel="stylesheet">
    <link href="css/all.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <script src="js/jquery.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</head>
<body>
    <?php
    include("header.php");
    ?>
    <div class="h-100vh">
        <div class="container pt-3" style="margin-top:90px">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title text-center">ห้องพักที่เลือกไว้</h3>
                </div>
                <div class="card-body">
                    <?php
                    if ($booking_register_query->num_rows > 0) {
                        ?>
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th width="150">รูปภาพ</th>
                                    <th>ห้องพัก</th>
                                    <th width="120" class="text-center">จำนวน</th>
                                    <th width="120" class="text-center">เตียงเสริม</th>
                                    <th width="150" class="text-center">ราคา</th>
                                    <th width="60"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $total = 0;
                                while ($booking_register_result = $booking_register_query->fetch_assoc()) {
                                    $room_price = 0;
                                    if ($booking_register_result['type'] == "daily") {
                                        $room_price = $booking_register_result['daily_price'];
                                    }
                                    else {
                                        $room_price = $booking_register_result['monthly_price'];
                                    }
                                    $price = $booking_register_result['amount'] * $room_price;
                                    if ($booking_register_result['extra_bed'] > 0) {
                                        $price += $booking_register_result['amount'] * 250;
                                    }
                                    $total += $price;
                                    ?>
                                    <tr>
                                        <td class="text-center">
                                            <img src="img/room/<?php echo $booking_register_result['picture'];?>" class="img-fluid">
                                        </td>
                                        <td>
                                            <strong><?php echo $booking_register_result['room_name'];?>
                                                <?php
                                                if ($booking_register_result['type'] == "daily") {
                                                    echo "(รายวัน)";
                                                }
                                                else {
                                                    echo "(รายเดือน)";
                                                }
                                                ?>
                                            </strong>
                                            <p class="fs-14 text-muted"><i class="fa-solid fa-bed"></i> <?php echo $booking_register_result['beds'];?> เตียง (เสริมได้ 1 เตียง)</p>
                                            <p class="text-primary">
                                                <?php
                                                if ($booking_register_result['type'] == "daily") {
                                                    echo number_format($room_price,0)." บาท/วัน";
                                                }
                                                else {
                                                    echo number_format($room_price,0)." บาท/เดือน";
                                                }
                                                ?>
                                            </p>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            echo number_format($booking_register_result['amount'],0);
                                            if ($booking_register_result['type'] == "daily") {
                                                echo " วัน";
                                            }
                                            else {
                                                echo " เดือน";
                                            }
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            if ($booking_register_result['extra_bed'] > 0) {
                                                if ($booking_register_result['type'] == "daily") {
                                                    echo "+250 บาท<br>ต่อวัน";
                                                }
                                                else {
                                                    echo "+250 บาท<br>ต่อเดือน";
                                                }
                                            }
                                            else {
                                                echo "ไม่มี";
                                            }
                                            ?>
                                        </td>
                                        <td class="text-center"><?php echo number_format($price,0);?></td>
                                        <td>
                                        <a href="booking_register_delete.php?dt=<?php echo $booking_register_result['datetime'];?>" class="btn btn-danger" title="ลบ" onclick="return confirm('คุณต้องการลบรายการที่เลือกไว้หรือไม่')"><i class="fa-solid fa-trash"></i></a>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="4" class="text-end">ยอดรวมทั้งหมด</th>
                                    <th class="text-center"><?php echo number_format($total,0);?></th>
                                    <th>บาท</th>
                                </tr>
                            </tfoot>
                        </table>
                        <div class="d-grid gap-2">
                            <a href="booking_confirm.php" class="btn btn-primary">กรอกรายละเอียดสั่งจอง</a>
                            <a href="booking_register_clear.php" class="btn btn-danger" onclick="return confirm('คุณต้องยกเลิกรายการห้องพักกี่เลือกไว้หรือไม่')">ยกเลิกรายการ</a>
                        </div>
                        <?php
                    }
                    else {
                        ?>
                        <div class="alert alert-warning text-dark text-center" role="alert">ไม่มีรายการห้องพักที่เลือกไว้</div>
                        <?php
                    }
                    $mysqli->close();
                    ?>
                </div>
            </div>
        </div>
    </div>
    <?php
    include("footer.php");
    ?>
</body>
</html>