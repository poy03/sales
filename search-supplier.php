<?php
ob_start();
session_start();


#$connect = mysql_connect("localhost","qfcdavao_admin","_39a11nwpm");
#mysql_select_db("qfcdavao_dbinventory");

include 'db.php';



?>

<?php
//get search term
$q = $_GET['term'];
//get matched data from skills table
$data = array();
$query = mysql_query("SELECT * FROM tbl_suppliers WHERE supplier_company LIKE '%$q%' AND deleted = 0 ORDER BY supplier_company");
while ($row = mysql_fetch_assoc($query)) {
    $supplier_company = $row["supplier_company"];
    $supplierID = $row["supplierID"];
	$data[] = array("label"=>$supplier_company,"data"=>$supplierID);
}
//return json data
echo json_encode($data);
?>
