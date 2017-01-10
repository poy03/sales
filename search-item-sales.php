<?php
ob_start();
session_start();

include 'db.php';
//get search term
$q = $_GET['term'];
//get matched data from skills table
$data = array();
$query = mysql_query("SELECT DISTINCT tbl_items.itemname, tbl_items.itemID FROM tbl_items INNER JOIN tbl_items_detail ON tbl_items.itemID=tbl_items_detail.itemID WHERE tbl_items.itemname LIKE '%$q%' AND tbl_items.deleted = 0 AND tbl_items.has_serial = 0 AND tbl_items_detail.quantity > 0 ORDER BY tbl_items.itemname");
while ($row = mysql_fetch_assoc($query)) {
    $itemname = $row['itemname'];
    $itemID = $row['itemID'];
	$item_detail_data = mysql_fetch_assoc(mysql_query("SELECT * FROM tbl_items_detail WHERE itemID='$itemID'"));
	$item_detail_id = $item_detail_data["item_detail_id"];
    $data[] = array("label"=>$itemname,"data"=>$item_detail_id);
}
//return json data
echo json_encode($data);
//echo "<br>";
//echo "<br>";
//var_dump($data);
?>