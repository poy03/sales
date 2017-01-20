<?php
ob_start();
session_start();
$accountID=@$_SESSION['accountID'];

include 'db.php';


if(APP_VERSION=="1.01"){
	mysql_query("ALTER TABLE tbl_orders_receiving ADD deleted_comment TEXT NOT NULL AFTER deleted");
	mysql_query("ALTER TABLE tbl_orders_receiving ADD deleted_date INT NOT NULL AFTER deleted_comment");
	mysql_query("ALTER TABLE tbl_receiving ADD deleted INT NOT NULL AFTER serial_number");
	mysql_query("ALTER TABLE tbl_receiving ADD supplierID INT NOT NULL AFTER deleted");

	$receiving_query = mysql_query("SELECT * FROM tbl_orders_receiving");
	while ($receiving_row=mysql_fetch_assoc($receiving_query)) {
		$orderID  = $receiving_row["orderID"];
		$supplierID = $receiving_row["supplierID"];
		mysql_query("UPDATE tbl_receiving SET supplierID='$supplierID' WHERE orderID='$orderID'");
	}
}