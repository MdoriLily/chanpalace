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
if (!isset($_GET['id'])) {
    header("location: news.php");
    exit;
}
$id = $_GET['id'];
$news_query = $mysqli->query("select * from news where news_id='{$id}'");
if ($news_query->num_rows == 0) {
    header("location: news.php");
    exit;
}
$news_result = $news_query->fetch_assoc();
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
    $mysqli->close();
    ?>
    <div class="h-100vh">
        <div class="container pt-3" style="margin-top:90px">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title"><?php echo $news_result['title'];?></h3>
                    <p>วันที่ <?php echo date_format(date_create($news_result['created']),'d/m/y');?></p>
                    <?php
                    if ($news_result['picture'] != "" && file_exists("./img/news/".$news_result['picture'])) {
                        ?>
                        <div class="text-center mb-4">
                            <img src="<?php echo "./img/news/".$news_result['picture']; ?>" class="img-fluid rounded-4">
                        </div>
                        <?php
                    }
                    ?>
                    <p><?php echo nl2br($news_result['content']);?></p>
                    <div class="d-grid gap-2 pt-3">
                        <a href="news.php" class="btn btn-secondary">ย้อนกลับ</a>
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