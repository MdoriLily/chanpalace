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
    <div class="h-100vh">
        <div class="container pt-3" style="margin-top:90px">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title text-center">ประชาสัมพันธ์</h3>
                </div>
                <div class="card-body">
                    <?php
                    $news_query = $mysqli->query("select * from news order by created desc");
                    if ($news_query->num_rows > 0) {
                        ?>
                        <table class="table table-hover table-lg">
                            <thead>
                                <tr>
                                    <th class="text-center" width="100">วันที่</th>
                                    <th>ชื่อเรื่อง</th>
                                    <th class="text-center" width="90">อ่าน</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                while ($news_result = $news_query->fetch_assoc()) {
                                    ?>
                                    <tr>
                                        <td class="text-center"><?php echo date_format(date_create($news_result['created']),'d/m/y');?></td>
                                        <td><?php echo $news_result['title'];?></td>
                                        <td>
                                            <a href="news_detail.php?id=<?php echo $news_result['news_id'];?>" class="btn btn-primary">อ่าน</a>
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
                        <div class="alert alert-warning text-dark text-center" role="alert">ไม่มีรายการประชาสัมพันธ์</div>
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