<?php
ob_start();
session_start();

include 'db.php';
//get search term
$q = $_GET['term'];
//get matched data from skills table
$data = array();
$query = mysql_query("SELECT * FROM tbl_credits WHERE creditID LIKE '%$q%' ORDER BY creditID");
while ($row = mysql_fetch_assoc($query)) {
    $itemname = "AR".sprintf("%06d",$row['creditID']);
    $itemID = $row['creditID'];
    $data[] = array("label"=>$itemname,"data"=>$itemID);
}
//return json data
echo json_encode($data);
//echo "<br>";
//echo "<br>";
//var_dump($data);
?>