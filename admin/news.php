<?php
session_start();
include("../connection/database.php");
if (!isset($_SESSION['login_user'])) {
    header("location: login.php");
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
$search = "";
if (isset($_GET['search'])) {
    $search = $_GET['search'];
}
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
                    <h4>ประชาสัมพันธ์</h4>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-md-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php">หน้าแรก</a></li>
                            <li class="breadcrumb-item active" aria-current="page">ประชาสัมพันธ์</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <div class="card mt-3">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <form method="get">
                            <div class="input-group mb-2">
                                <input type="text" class="form-control" name="search" placeholder="ค้นหา..." value="<?php echo $search;?>">
                                <button class="btn btn-secondary" type="submit">ค้นหา</button>
                            </div>
                        </form>
                    </div>
                    <div>
                        <a href="news_add.php" class="btn btn-primary mb-2">เพิ่มประชาสัมพันธ์</a>
                    </div>
                </div>
                <?php
                $news_query = $mysqli->query("select * from news where title like '%{$search}%' order by created desc");
                if ($news_query->num_rows > 0) {
                    ?>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ชื่อเรื่อง</th>
                                <th width="120" class="text-center">วันที่</th>
                                <th width="250" class="text-center">ผู้เขียน</th>
                                <th width="90"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while ($news_result = $news_query->fetch_assoc()) {
                                ?>
                                <tr>
                                    <td><?php echo $news_result['title'];?></td>
                                    <td class="text-center"><?php echo date_format(date_create($news_result['created']),'d/m/Y');?></td>
                                    <td class="text-center">
                                        <?php
                                        $writer_query = $mysqli->query("select * from user where user_id='{$news_result['user_id']}'");
                                        if ($writer_query->num_rows > 0) {
                                            $writer_result = $writer_query->fetch_assoc();
                                            echo $writer_result['name'];
                                        }
                                        else {
                                            echo "ผู้ดูแลระบบ";
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="news_edit.php?id=<?php echo $news_result['news_id'];?>" class="btn btn-primary" title="แก้ไข"><i class="fa-solid fa-pen-to-square"></i></a>
                                            <a href="news_delete_db.php?id=<?php echo $news_result['news_id'];?>" class="btn btn-danger" title="ลบ" onclick="return confirm('คุณต้องการลบประชาสัมพันธ์วันที่ <?php echo date_format(date_create($news_result['created']),'d/m/Y');?> หรือไม่')"><i class="fa-solid fa-trash"></i></a>
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
                    <div class="alert alert-warning text-dark text-center" role="alert">ไม่มีรายการประชาสัมพันธ์</div>
                    <?php
                }
                $mysqli->close();
                ?>
            </div>
        </div>
    </div>
</body>
</html>