<?php
ob_start();
session_start();

include 'db.php';
//get search term
$q = $_GET['term'];
//get matched data from skills table
$data = array();
$query = mysql_query("SELECT DISTINCT quantity, itemname, itemID FROM tbl_items WHERE itemname LIKE '%$q%' AND deleted = 0 ORDER BY itemname");
while ($row = mysql_fetch_assoc($query)) {
    $itemname = $row['itemname'];
    $itemID = $row['itemID'];
    $data[] = array("label"=>$itemname,"data"=>$itemID);
}
//return json data
echo json_encode($data);
//echo "<br>";
//echo "<br>";
//var_dump($data);
?>