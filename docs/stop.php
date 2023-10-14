<?php
include_once('inc/config.php');

$con = getConnection();
session_destroy();
$con->close();
// header('location:index.php');
echo '<script>location.replace("index.php");</script>' ;
?>