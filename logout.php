<?php
session_start();
session_destroy();
include("connection/database.php");
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
        alert("ออกจากระบบเรียบร้อยแล้ว");
        window.location.href = "index.php";
    </script>
</body>
</html>