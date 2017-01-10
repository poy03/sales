<?php
include 'db.php';
//get search term
$q = $_GET['term'];
//get matched data from skills table
$query = mysql_query("SELECT * FROM tbl_customer WHERE companyname LIKE '%$q%' AND deleted = 0 ORDER BY companyname");
while ($row = mysql_fetch_assoc($query)) {
    $data[] = $row['companyname'];
}
//return json data
echo json_encode($data);
?>