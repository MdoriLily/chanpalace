<?php
session_start();
include("../connection/database.php");
if (!isset($_SESSION['login_user'])) {
    header("location: index.php");
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
$id = $_GET['id'];
$booking_query = $mysqli->query("select * from booking where booking_id='{$id}'");
if ($booking_query->num_rows == 0) {
    header("location: booking.php");
    exit;
}
$booking_result = $booking_query->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>โรงแรมจันทร์พาเลส</title>
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/bootstrap-theme.css" rel="stylesheet">
    <link href="../css/app.css" rel="stylesheet">
    <link href="../css/all.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
    <script src="../js/jquery.min.js"></script>
    <script src="../js/popper.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
</head>
<body>
    <?php
    include("header.php");
    ?>
    <div class="container pt-3" style="margin-top:90px">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h4>รายละเอียดการจอง</h4>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-md-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php">หน้าแรก</a></li>
                            <li class="breadcrumb-item"><a href="booking.php">รายการสั่งจอง</a></li>
                            <li class="breadcrumb-item active" aria-current="page">รายละเอียดการจอง</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <div class="card mt-3">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="row mb-3">
                            <div class="col-4 fw-bold">วันที่สั่งจอง</div>
                            <div class="col-8"><?php echo date_format(date_create($booking_result['created']),"d/m/Y");?></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-4 fw-bold">วันที่เข้าพัก</div>
                            <div class="col-8"><?php echo date_format(date_create($booking_result['check_in']),"d/m/Y");?></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-4 fw-bold">สถานะ</div>
                            <div class="col-8">
                                <?php
                                if ($booking_result['status'] == "checking") {
                                    echo "รอตรวจสอบ";
                                }
                                else if ($booking_result['status'] == "pending_payment") {
                                    echo "รอชำระเงิน";
                                }
                                else if ($booking_result['status'] == "checking_payment") {
                                    echo "รอตรวจสอบการชำระ";
                                }
                                else if ($booking_result['status'] == "canceled") {
                                    echo "ยกเลิกแล้ว";
                                }
                                else {
                                    echo "จองสำเร็จแล้ว";
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="row mb-3">
                            <div class="col-4 fw-bold">ชื่อผู้จอง</div>
                            <div class="col-8">
                                <?php echo $booking_result['name'];?>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-4 fw-bold">เลขประจำตัว</div>
                            <div class="col-8">
                                <?php echo $booking_result['id_card'];?>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-4 fw-bold">เบอร์โทรศัพท์</div>
                            <div class="col-8"><?php echo $booking_result['phone_no'];?></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-4 fw-bold">เพิ่มเติม</div>
                            <div class="col-8"><?php echo $booking_result['note'];?></div>
                        </div>
                    </div>
                </div>
                <?php
                if ($booking_result['slip'] != "") {
                    ?>
                    <hr>
                    <div class="modal fade" id="slip_Modal" tabindex="-1" aria-labelledby="slip_ModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="slip_ModalLabel">รูปสลิปการชำระเงิน</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <img src="../img/slip/<?php echo $booking_result['slip'];?>" class="img-fluid">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="row mb-3">
                                <div class="col-4 fw-bold">วันที่อัปโหลดสลิป</div>
                                <div class="col-8">
                                    <?php echo date_format(date_create($booking_result['uploaded_slip']),"d/m/Y H:i น.");?>
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#slip_Modal" title="รูปสลิปการชำระเงิน"><i class="fa-solid fa-image"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                }
                $booking_room_query = $mysqli->query("select * from booking_room
                where booking_id='{$booking_result['booking_id']}' order by booking_room_id asc");
                if ($booking_query->num_rows > 0) {
                    ?>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th width="150">รูปภาพ</th>
                                <th>ห้องพัก</th>
                                <th width="120" class="text-center">จำนวน</th>
                                <th width="120" class="text-center">เตียงเสริม</th>
                                <th width="150" class="text-center">ราคา</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $total = 0;
                            while ($booking_room_result = $booking_room_query->fetch_assoc()) {
                                $room_query = $mysqli->query("select * from room where room_id='{$booking_room_result['room_id']}'");
                                $room_result = $room_query->fetch_assoc();
                                $total += $booking_room_result['total'];
                                ?>
                                <tr>
                                    <td class="text-center">
                                        <img src="../img/room/<?php echo $room_result['picture'];?>" class="img-fluid">
                                    </td>
                                    <td>
                                        <strong><?php echo $room_result['room_name'];?>
                                            <?php
                                            if ($booking_room_result['type'] == "daily") {
                                                echo "(รายวัน)";
                                            }
                                            else {
                                                echo "(รายเดือน)";
                                            }
                                            ?>
                                        </strong>
                                        <p class="fs-14 text-muted"><i class="fa-solid fa-bed"></i> <?php echo $room_result['beds'];?> เตียง</p>
                                        <p class="text-primary">
                                            <?php
                                            if ($booking_room_result['type'] == "daily") {
                                                echo number_format($booking_room_result['room_price'],0)." บาท/วัน";
                                            }
                                            else {
                                                echo number_format($booking_room_result['room_price'],0)." บาท/เดือน";
                                            }
                                            ?>
                                        </p>
                                    </td>
                                    <td class="text-center">
                                        <?php
                                        $date_check_in = date_create($booking_result['check_in']);
                                        date_add($date_check_in,date_interval_create_from_date_string($booking_room_result['amount']." days"));
                                        echo date_format($date_check_in, "d/m/Y")."<br>(".number_format($booking_room_result['amount'],0);
                                        if ($booking_room_result['type'] == "daily") {
                                            echo " วัน)";
                                        }
                                        else {
                                            echo " เดือน)";
                                        }
                                        ?>
                                    </td>
                                    <td class="text-center">
                                        <?php
                                        if ($booking_room_result['extra_bed'] > 0) {
                                            if ($booking_room_result['type'] == "daily") {
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
                                    <td class="text-center"><?php echo number_format($booking_room_result['total'],0);?></td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="4" class="text-end">ยอดรวมทั้งหมด (บาท)</th>
                                <th class="text-center"><?php echo number_format($total,0);?></th>
                            </tr>
                        </tfoot>
                    </table>
                    <?php
                }
                else {
                    ?>
                    <div class="alert alert-warning text-dark text-center" role="alert">ไม่มีรายการสั่งจองห้องพัก</div>
                    <?php
                }
                $mysqli->close();
                ?>
                <div class="d-grid gap-2">
                    <?php
                    if ($booking_result['status'] == "checking") {
                        ?>
                        <a href="booking_edit_status_db.php?id=<?php echo $booking_result['booking_id'];?>&status=pending_payment" class="btn btn-primary" onclick="return confirm('คุณได้ตรวจสอบข้อมูลผู้จองเรียบร้อยแล้วหรือไม่')">ตรวจสอบแล้ว</a>
                        <?php
                    }
                    else if ($booking_result['status'] == "checking_payment") {
                        ?>
                        <a href="booking_edit_status_db.php?id=<?php echo $booking_result['booking_id'];?>&status=success" class="btn btn-primary" onclick="return confirm('คุณได้ตรวจสอบการชำระเงินเรียบร้อยแล้วหรือไม่')">ยืนยันการชำระ</a>
                        <a href="booking_edit_status_db.php?id=<?php echo $booking_result['booking_id'];?>&status=reset" class="btn btn-info" onclick="return confirm('คุณต้องการรีเซ็ตสถานะการจองห้องพักหรือไม่')">รีเซ็ตสถานะ</a>
                        <?php
                    }
                    if ($booking_result['status'] != "success") {
                        ?>
                        <a href="booking_edit_status_db.php?id=<?php echo $booking_result['booking_id'];?>&status=canceled" class="btn btn-danger" onclick="return confirm('คุณต้องการยกเลิกการจองห้องพักหรือไม่')">ยกเลิกการจอง</a>
                        <?php
                    }
                    ?>
                    <a href="booking.php" class="btn btn-secondary mt-3">ย้อนกลับ</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>