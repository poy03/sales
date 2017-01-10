<?php
ob_start();
session_start();
$accountID=@$_SESSION['accountID'];
$typeprice=@$_GET['type'];
$keyword=@$_GET['keyword'];
$search=@$_GET['search'];
			$by=@$_GET['by'];
			$order=@$_GET['order'];

#$connect = mysql_connect("localhost","qfcdavao_admin","_39a11nwpm");
#mysql_select_db("qfcdavao_dbinventory");

include 'db.php';

mysql_query("UPDATE tbl_cart SET type_price='$typeprice' WHERE accountID='$accountID'");
header("location:sales");

?>
