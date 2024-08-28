<?php
session_start();
include("connection/database.php");
if (isset($_SESSION['login_user'])) {
    $check_login_query = $mysqli->query("select * from user where user_id='{$_SESSION['login_user']}'");
    if ($check_login_query->num_rows == 0) {
        header("location: logout.php");
        exit;
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
    <div class="h-100vh d-flex align-items-center" id="booking_section">
        <div class="container">
            <div class="card m-0">
                <div class="card-body">
                <h3 class="card-title text-center mb-4">ติดต่อเรา</h3>
                    <div class="row">
                        <div class="col-lg-6">
                            <div id="google_map" class="rounded mb-3" style="width:100%;height:300px"></div>
                            <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCYYa6y4UVU4WUYNbjhP9oS-2ugir2uzXM&callback=initMap"></script>
                            <script>
                                let locat = {lat:13.82423,lng:100.04223};
                                let map;
                                let marker;
                                function initMap() {
                                    map = new google.maps.Map(document.getElementById('google_map'), {
                                        center: locat,
                                        zoom: 16,
                                        mapTypeId: google.maps.MapTypeId.ROADMAP,
                                        mapTypeControl: false,
                                        streetViewControl: false,
                                    });
                                    marker = new google.maps.Marker({
                                        position: locat,
                                        draggable: true,
                                        map: map,
                                        title: "สถานที่ตั้ง",
                                    });
                                }
                            </script>
                        </div>
                        <div class="col-lg-6">
                            <p><i class="fa-solid fa-location-dot me-3"></i>เลขที่ 610/7 ถ.ทางรถไฟตะวันตก ต.พระปฐมเจดีย์<br>อ.เมือง จ.นครปฐม 73000</p>
                            <p><i class="fa-solid fa-phone me-3"></i>034-363-561</p>
                            <p><i class="fa-solid fa-phone me-3"></i>06-3894-0438</p>
                            <p><i class="fa-brands fa-facebook me-3"></i><a href="https://www.facebook.com/Junpalace-%E0%B8%88%E0%B8%B1%E0%B8%99%E0%B8%97%E0%B8%A3%E0%B9%8C%E0%B8%9E%E0%B8%B2%E0%B9%80%E0%B8%A5%E0%B8%AA-%E0%B8%99%E0%B8%84%E0%B8%A3%E0%B8%9B%E0%B8%90%E0%B8%A1-1034168143297184/">Junpalace จันทร์พาเลส นครปฐม</a></p>
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