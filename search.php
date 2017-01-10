<?php
ob_start();
session_start();

include 'db.php';
//get search term
$q = mysql_real_escape_string(htmlspecialchars(trim($_GET['term'])));
//get matched data from skills table
$query = mysql_query("SELECT DISTINCT category FROM tbl_items WHERE category LIKE '%$q%' AND deleted = 0 ORDER BY itemname");
while ($row = mysql_fetch_assoc($query)) {
    $data[] = htmlspecialchars_decode($row['category']);
}
//return json data
echo json_encode($data);
?>