<?php
ob_start();
session_start();
$accountID=@$_SESSION['accountID'];
$orderID=@$_SESSION['orderID'];
$page=@$_GET['page'];
$cat=@$_GET['cat'];
$keyword=@$_GET['keyword'];
$search=@$_GET['search'];
			$by=@$_GET['by'];
			$order=@$_GET['order'];

#$connect = mysql_connect("localhost","qfcdavao_admin","_39a11nwpm");
#mysql_select_db("qfcdavao_dbinventory");

include 'db.php';


if($_POST){
	$orderID = $_POST["id"];
	$deleted_comment = mysql_real_escape_string(htmlspecialchars(trim($_POST["deleted_comment"])));
	$receiving_data = mysql_fetch_assoc(mysql_query("SELECT * FROM tbl_orders_receiving WHERE orderID='$orderID'"));
	// var_dump($receiving_data["orderID"]);
	$receiving_query = mysql_query("SELECT * FROM tbl_receiving WHERE orderID='$orderID'");
	while($receiving_row=mysql_fetch_assoc($receiving_query)){
		$item_detail_id = $receiving_row["itemID"];
		$item_data = mysql_fetch_assoc(mysql_query("SELECT * FROM tbl_items_detail WHERE item_detail_id='$item_detail_id'"));

		$quantity = $receiving_row["quantity"];
		$new_quantity = $item_data["quantity"]-$quantity;
		mysql_query("UPDATE tbl_items_detail SET quantity='$new_quantity' WHERE item_detail_id='$item_detail_id'");

		mysql_query("INSERT INTO tbl_items_history (type,item_detail_id,itemID,serial_number,description,date_time,quantity,referenceID,accountID) VALUES ('Purchase Delete','".$item_data["item_detail_id"]."','".$item_data["itemID"]."','".$item_data["serial_number"]."','".$deleted_comment."','".strtotime(date("m/d/Y"))."','$quantity','$orderID','$accountID')");



		echo "<br>";
		// $item_query = mysql_query("UPDATE tbl_items_detail ")
		// echo "asdasd";
	}


	mysql_query("UPDATE tbl_orders_receiving SET deleted='1', deleted_comment='$deleted_comment', deleted_date='".strtotime(date("m/d/Y"))."' WHERE orderID='$orderID'");
	mysql_query("UPDATE tbl_receiving SET deleted='1' WHERE orderID='$orderID'");
	// echo "SELECT * FROM tbl_orders_receiving WHERE orderID='$orderID'";
}