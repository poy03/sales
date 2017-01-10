<?php
ob_start();
session_start();
$accountID=@$_SESSION['accountID'];
$orderID=@$_SESSION['orderID'];


include 'db.php';

if($_POST){


	$type_payment = $_POST["type_payment"];
	$id = $_POST["id"];
	$comments = mysql_real_escape_string(htmlspecialchars(trim($_POST["comments"])));

	mysql_query("UPDATE tbl_payments SET comments='$comments' WHERE orderID='$id' AND type_payment='$type_payment'");

}
?>