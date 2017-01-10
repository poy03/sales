<?php
ob_start();
session_start();

include 'db.php';
//get search term
$q = mysql_real_escape_string(htmlspecialchars(trim($_GET['term'])));
//get matched data from skills table
$data = array();
$query = mysql_query("SELECT DISTINCT quantity, itemname, itemID, category FROM tbl_items WHERE category LIKE '$q%' AND deleted = 0 ORDER BY category");
while ($row = mysql_fetch_assoc($query)) {
    $itemname = $row['itemname'];
    $category = htmlspecialchars_decode($row['category']);
    $itemID = $row['itemID'];
    if($category!=""){
    	$category = "[$category] ";
    }
    $data[] = array("label"=>$category.$itemname,"data"=>$itemID);
}
//return json data
echo json_encode($data);
//echo "<br>";
//echo "<br>";
//var_dump($data);
?>