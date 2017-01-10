<?php
ob_start();
session_start();
$accountID=@$_SESSION['accountID'];
$page=@$_GET['page'];
$tab=@$_GET['tab'];
$id=@$_GET['id'];
if(!isset($tab)){
	$tab=1;
}
$keyword=@$_GET['keyword'];
$search=@$_GET['search'];
$by=@$_GET['by'];
$order=@$_GET['order'];

include 'db.php';



if($_POST){
	$orderID = $_POST["id"];
	$credit_data = mysql_fetch_assoc(mysql_query("SELECT * FROM tbl_payments WHERE orderID='$orderID' AND type_payment = 'credit'"));
	$credit_data["payment"] = number_format($credit_data["payment"],2);
	echo json_encode($credit_data);
}
?>