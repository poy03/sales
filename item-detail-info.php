<?php
ob_start();
session_start();
$accountID=@$_SESSION['accountID'];
$page=@$_POST['page'];

include 'db.php';
$item_detail_id=@$_POST['id'];
$item_detail_data = mysql_fetch_assoc(mysql_query("SELECT * FROM tbl_items_detail WHERE item_detail_id='$item_detail_id'"));
$item_data = mysql_fetch_assoc(mysql_query("SELECT * FROM tbl_items WHERE itemID='".$item_detail_data["itemID"]."'"));
$data = array(
	"title"=>$item_data["itemname"],
	"serial_number"=>$item_detail_data["serial_number"],
	"item_detail_id"=>$item_detail_data["item_detail_id"]
	);
echo json_encode($data);

?>
