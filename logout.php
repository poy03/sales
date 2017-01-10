<?php
ob_start();
session_start();
$accountID=@$_SESSION['accountID'];
include 'db.php';

mysql_query("DELETE FROM tbl_cart WHERE accountID='$accountID'");
mysql_query("DELETE FROM tbl_cart_receiving WHERE accountID='$accountID'");
mysql_query("DELETE FROM tbl_cart_expenseso WHERE accountID='$accountID'");
session_destroy();
setcookie('LOGGED', null, -1, '/');
header("location:index");
?>