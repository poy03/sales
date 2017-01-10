<?php
ob_start();
session_start();
$accountID=@$_SESSION['accountID'];


include 'db.php';

if($_POST){
	$orderID = $_POST["orderID"];
	$type_payment = $_POST["type_payment"];
	$payment = $_POST["payment"];
	$comments = mysql_real_escape_string(htmlspecialchars(trim($_POST["comments"])));

	$order_data = mysql_fetch_assoc(mysql_query("SELECT * FROM tbl_orders WHERE orderID='$orderID'"));

	mysql_query("INSERT INTO tbl_payments (type_payment,payment,accountID,orderID,date,time,date_due,customerID,credit_status,comments) VALUES ('$type_payment','$payment','$accountID','$orderID','".strtotime(date("m/d/Y"))."','".strtotime("h:i:s A")."','".$order_data["date_due"]."','".$order_data["customerID"]."','1','$comments')");

	$credit_data = mysql_fetch_assoc(mysql_query("SELECT * FROM tbl_payments WHERE type_payment='credit' AND orderID='$orderID'"));
	$balance = $credit_data["payment"]-$payment;
	mysql_query("UPDATE tbl_payments SET payment='$balance' WHERE type_payment='credit' AND orderID='$orderID'");
	$data = array(
		"orderID"=>$orderID
		);
	echo json_encode($data);

}

?>