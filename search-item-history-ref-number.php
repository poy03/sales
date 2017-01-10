<?php
ob_start();
session_start();

include 'db.php';
//get search term
$q = $_GET['term'];
//get matched data from skills table
$data = array();
$query = mysql_query("SELECT DISTINCT reference_number FROM tbl_items_history WHERE reference_number LIKE '%$q%' ORDER BY reference_number");
while ($row = mysql_fetch_assoc($query)) {
    $data[] = $row['reference_number'];
}
//return json data
echo json_encode($data);
//echo "<br>";
//echo "<br>";
//var_dump($data);
?>