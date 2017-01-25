<?php
ob_start();
session_start();
$accountID=@$_SESSION['accountID'];

include 'db.php';

if($_POST){
	$orderID = $_POST["orderID"];
	$deleted_comment = mysql_real_escape_string(htmlspecialchars(trim($_POST["deleted_comment"])));
	$deleted_comment .=" (".date("m/d/Y").")";
	mysql_query("UPDATE tbl_orders_expenses SET deleted='1', deleted_by='$accountID', deleted_comment='$deleted_comment' WHERE orderID='$orderID'");

}
?>