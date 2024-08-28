<?php
$mysqli = new mysqli("localhost","root","","chanpalace");
if (mysqli_connect_error()) {
    die("Connect Error (".mysqli_connect_errno().") ".mysqli_connect_error());
    exit;
}
$mysqli->set_charset("utf8");
?>