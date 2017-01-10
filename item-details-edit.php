<?php
ob_start();
session_start();
$accountID=@$_SESSION['accountID'];
$page=@$_POST['page'];

include 'db.php';

if($_POST){
	$serial_number = $_POST["serial_number"];
	$item_detail_id = $_POST["item_detail_id"];
	$item_detail_data = mysql_fetch_assoc(mysql_query("SELECT * FROM tbl_items_detail WHERE item_detail_id='$item_detail_id'"));
	mysql_query("UPDATE tbl_items_detail SET serial_number='$serial_number' WHERE item_detail_id='$item_detail_id'");
	$data = array(
		"item_detail_id"=>$item_detail_id,
		"serial_number"=>$serial_number
		);
	$description = "Serial Number: ".$item_detail_data["serial_number"]." => ".$serial_number;
	$description = mysql_real_escape_string($description);
	mysql_query("INSERT INTO tbl_items_history (type,item_detail_id,itemID,serial_number,description,date_time,accountID) VALUES 
		('Item Edit',$item_detail_id,'".$item_detail_data["itemID"]."','".$serial_number."','$description','".strtotime(date("m/d/Y"))."','$accountID')");
	echo json_encode($data);
}
?>
