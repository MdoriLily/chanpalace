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
$booking_register_query = $mysqli->query("select * from booking_register where user_id='{$_SESSION['login_user']}'");
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
            <div class="text-end mb-3">
                <a href="booking_register.php" class="btn btn-primary">ห้องพักที่เลือกไว้ <span class="badge rounded-pill text-dark bg-white"><?php echo number_format($booking_register_query->num_rows,0)?></span></a>
            </div>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title text-center">เลือกห้องพัก</h3>
                    <ul class="nav nav-tabs card-header-tabs nav-justified mb-2">
                        <li class="nav-item">
                            <button class="nav-link active" id="daily_price-tab" data-bs-toggle="tab" data-bs-target="#daily_price" type="button" role="tab" aria-controls="daily_price" aria-selected="true">แบบรายวัน</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" id="monthly_price-tab" data-bs-toggle="tab" data-bs-target="#monthly_price" type="button" role="tab" aria-controls="monthly_price" aria-selected="false">แบบรายเดือน</button>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="daily_price" role="tabpanel" aria-labelledby="daily_price-tab">
                            <?php
                            $room_query = $mysqli->query("select * from room order by daily_price asc");
                            if ($room_query->num_rows > 0) {
                                while ($room_result = $room_query->fetch_assoc()) {
                                    ?>
                                    <div class="card border mb-4">
                                        <div class="row g-0">
                                            <div class="col-md-4">
                                                <img src="img/room/<?php echo $room_result['picture'];?>" class="img-fluid rounded-start w-100" alt="<?php echo $room_result['room_name'];?>" style="max-height:200px">
                                            </div>
                                            <div class="col-md-8">
                                                <div class="card-body">
                                                    <div class="row g-3">
                                                        <div class="col-lg-8">
                                                            <h5 class="card-title"><?php echo $room_result['room_name'];?></h5>
                                                            <p class="card-text fs-14 max-3-lines"><?php echo $room_result['detail'];?></p>
                                                            <p class="card-text fs-14"><i class="fa-solid fa-bed"></i> <?php echo $room_result['beds'];?> เตียง (เสริมได้ 1 เตียง)</p>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <h4 class="text-lg-end text-primary"><?php echo number_format($room_result['daily_price'],0);?> <small>บาท/วัน</small></h4>
                                                            <form action="booking_register_add.php" method="post">
                                                                <input type="hidden" name="type" value="daily">
                                                                <input type="hidden" name="room_id" value="<?php echo $room_result['room_id'];?>">
                                                                <div class="mb-2">
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="checkbox" value="1" name="extra_beds" id="extra_beds_d<?php echo $room_result['room_id'];?>">
                                                                        <label class="form-check-label fs-14" for="extra_beds_d<?php echo $room_result['room_id'];?>">
                                                                            เสริมเตียง (250 บาท/วัน)
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="input-group mb-2">
                                                                    <input type="number" name="amount" class="form-control text-center" min="1" max="30" step="1" value="1">
                                                                    <span class="input-group-text">วัน</span>
                                                                </div>
                                                                <div class="d-grid d-lg-block text-lg-end gap-2">
                                                                    <button type="submit" class="btn btn-primary">สั่งจอง</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                }
                            }
                            else {
                                ?>
                                <div class="alert alert-warning text-dark text-center" role="alert">ไม่มีรายการห้องพัก</div>
                                <?php
                            }
                            ?>
                        </div>
                        <div class="tab-pane fade" id="monthly_price" role="tabpanel" aria-labelledby="monthly_price-tab">
                            <?php
                            $room_query = $mysqli->query("select * from room order by monthly_price asc");
                            if ($room_query->num_rows > 0) {
                                while ($room_result = $room_query->fetch_assoc()) {
                                    ?>
                                    <div class="card border mb-4">
                                        <div class="row g-0">
                                            <div class="col-md-4">
                                                <img src="img/room/<?php echo $room_result['picture'];?>" class="img-fluid rounded-start w-100" alt="<?php echo $room_result['room_name'];?>" style="max-height:200px">
                                            </div>
                                            <div class="col-md-8">
                                                <div class="card-body">
                                                    <div class="row g-3">
                                                        <div class="col-lg-8">
                                                            <h5 class="card-title"><?php echo $room_result['room_name'];?></h5>
                                                            <p class="card-text max-3-lines"><?php echo $room_result['detail'];?></p>
                                                            <p class="card-text"><i class="fa-solid fa-bed"></i> <?php echo $room_result['beds'];?> เตียง (เสริมได้ 1 เตียง)</p>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <h4 class="text-lg-end text-primary"><?php echo number_format($room_result['monthly_price'],0);?> <small>บาท/เดือน</small></h4>
                                                            <form action="booking_register_add.php" method="post">
                                                                <input type="hidden" name="type" value="monthly">
                                                                <input type="hidden" name="room_id" value="<?php echo $room_result['room_id'];?>">
                                                                <div class="mb-2">
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="checkbox" value="1" name="extra_beds" id="extra_beds_m<?php echo $room_result['room_id'];?>">
                                                                        <label class="form-check-label fs-14" for="extra_beds_m<?php echo $room_result['room_id'];?>">
                                                                            เสริมเตียง (250 บาท/เดือน)
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="input-group mb-2">
                                                                    <input type="number" name="amount" class="form-control text-center" min="1" max="6" step="1" value="1">
                                                                    <span class="input-group-text">เดือน</span>
                                                                </div>
                                                                <div class="d-grid d-lg-block text-lg-end gap-2">
                                                                    <button type="submit" class="btn btn-primary">สั่งจอง</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                }
                            }
                            else {
                                ?>
                                <div class="alert alert-warning text-dark text-center" role="alert">ไม่มีรายการห้องพัก</div>
                                <?php
                            }
                            $mysqli->close();
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    include("footer.php");
    ?>
</body>
</html>