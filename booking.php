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
$query_status = "";
if (isset($_GET['status'])) {
    $status = $_GET['status'];
    if ($status == "checking") {
        $query_status = " and status='checking'";
    }
    else if ($status == "pending_payment") {
        $query_status = " and status='pending_payment'";
    }
    else if ($status == "checking_payment") {
        $query_status = " and status='checking_payment'";
    }
    else if ($status == "success") {
        $query_status = " and status='success'";
    }
    else if ($status == "canceled") {
        $query_status = " and status='canceled'";
    }
}
$search = "";
if (isset($_GET['search'])) {
    $search = $_GET['search'];
}
$booking_query = $mysqli->query("select booking.*,sum(booking_room.total) as net_total
    from booking left join booking_room on booking.booking_id=booking_room.booking_id
    where booking.user_id='{$_SESSION['login_user']}'{$query_status}
    and (booking.name like '%{$search}%' or phone_no like '%{$search}%')
    group by booking_room.booking_id
    order by booking.created asc");
$status_total = 0;
$status_checking = 0;
$status_pending_payment = 0;
$status_checking_payment = 0;
$status_success = 0;
$status_canceled = 0;
$booking_all_query = $mysqli->query("select * from booking where user_id='{$_SESSION['login_user']}' and (booking.name like '%{$search}%' or phone_no like '%{$search}%')");
if ($booking_all_query->num_rows > 0) {
    $status_total = $booking_all_query->num_rows;
    while ($booking_all_result = $booking_all_query->fetch_assoc()) {
        if ($booking_all_result['status'] == "checking") {
            $status_checking++;
        }
        else if ($booking_all_result['status'] == "pending_payment") {
            $status_pending_payment++;
        }
        else if ($booking_all_result['status'] == "checking_payment") {
            $status_checking_payment++;
        }
        else if ($booking_all_result['status'] == "canceled") {
            $status_canceled++;
        }
        else {
            $status_success++;
        }
    }
}
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
                    <h3 class="card-title text-center">รายการสั่งจอง</h3>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <a href="booking.php" class="btn btn-light">ทั้งหมด <span class="badge rounded-pill text-dark bg-white"><?php echo number_format($status_total,0)?></span></a>
                        <a href="booking.php?status=checking" class="btn btn-secondary">รอตรวจสอบ <span class="badge rounded-pill text-dark bg-white"><?php echo number_format($status_checking,0)?></span></a>
                        <a href="booking.php?status=pending_payment" class="btn btn-primary">รอชำระเงิน <span class="badge rounded-pill text-dark bg-white"><?php echo number_format($status_pending_payment,0)?></span></a>
                        <a href="booking.php?status=checking_payment" class="btn btn-warning text-dark">รอตรวจสอบการชำระ <span class="badge rounded-pill text-dark bg-white"><?php echo number_format($status_checking_payment,0)?></span></a>
                        <a href="booking.php?status=success" class="btn btn-success">จองสำเร็จแล้ว <span class="badge rounded-pill text-dark bg-white"><?php echo number_format($status_success,0)?></span></a>
                        <a href="booking.php?status=canceled" class="btn btn-danger">ยกเลิกแล้ว <span class="badge rounded-pill text-dark bg-white"><?php echo number_format($status_canceled,0)?></span></a>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div>
                            <form method="get">
                                <div class="input-group mb-2">
                                    <input type="text" class="form-control" name="search" placeholder="ค้นหา..." value="<?php echo $search;?>">
                                    <button class="btn btn-secondary" type="submit">ค้นหา</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <?php
                    if ($booking_query->num_rows > 0) {
                        ?>
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th width="100" class="text-center">วันที่จอง</th>
                                    <th width="100" class="text-center">วันที่เข้าพัก</th>
                                    <th>ผู้สั่งจอง</th>
                                    <th class="text-center">สถานะ</th>
                                    <th width="100" class="text-center">ยอดชำระ</th>
                                    <th width="60"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $total = 0;
                                while ($booking_result = $booking_query->fetch_assoc()) {
                                    ?>
                                    <tr>
                                        <td class="text-center"><?php echo date_format(date_create($booking_result['created']), 'd/m/Y');?></td>
                                        <td class="text-center"><?php echo date_format(date_create($booking_result['check_in']), 'd/m/Y');?></td>
                                        <td><?php echo $booking_result['name'];?><br><span class="fs-14">ติดต่อ: <?php echo $booking_result['phone_no'];?></span></td>
                                        <td class="text-center fw-bold">
                                            <?php
                                            if ($booking_result['status'] == "checking") {
                                                echo "<span class=\"text-secondary\">รอตรวจสอบ</span>";
                                            }
                                            else if ($booking_result['status'] == "pending_payment") {
                                                echo "<span class=\"text-primary\">รอชำระเงิน</span>";
                                            }
                                            else if ($booking_result['status'] == "checking_payment") {
                                                echo "<span class=\"text-warning\">รอตรวจสอบการชำระ</span>";
                                            }
                                            else if ($booking_result['status'] == "canceled") {
                                                echo "<span class=\"text-danger\">ยกเลิกแล้ว</span>";
                                            }
                                            else {
                                                echo "<span class=\"text-success\">จองสำเร็จแล้ว</span>";
                                            }
                                            ?>
                                        </td>
                                        <td class="text-center"><?php echo number_format($booking_result['net_total'],0);?></td>
                                        <td>
										<div class="btn-group">
                                            <a href="booking_detail.php?id=<?php echo $booking_result['booking_id'];?>" class="btn btn-primary" title="ดูรายละเอียด"><i class="fa-solid fa-eye"></i></a>
											<a href="booking_delete_db.php?id=<?php echo $booking_result['booking_id'];?>" class="btn btn-danger" title="ลบ" onclick="return confirm('คุณต้องการยกเลิกการจองห้องพักหรือไม่')"><i class="fa-solid fa-trash"></i></a>
                                       </div>
									   </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
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
                </div>
            </div>
        </div>
    </div>
    <?php
    include("footer.php");
    ?>
</body>
</html>