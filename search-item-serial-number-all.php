<?php
ob_start();
session_start();

include 'db.php';
//get search term
$q = $_GET['term'];
//get matched data from skills table
$data = array();
$query = mysql_query("SELECT DISTINCT serial_number,item_detail_id FROM tbl_items_detail WHERE serial_number LIKE '%$q%' AND has_serial = 1 ORDER BY serial_number");
while ($row = mysql_fetch_assoc($query)) {
	$item_detail_id = $row["item_detail_id"];
	$itemname = $row["serial_number"];
    $data[] = array("label"=>$itemname,"data"=>$item_detail_id);
}
//return json data
echo json_encode($data);
//echo "<br>";
//echo "<br>";
//var_dump($data);
?>