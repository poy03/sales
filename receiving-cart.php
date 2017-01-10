<?php
ob_start();
session_start();

#$connect = mysql_connect("localhost","qfcdavao_admin","_39a11nwpm");
#mysql_select_db("qfcdavao_dbinventory");
error_reporting(0);
include 'db.php';

?>

<?php
if($_POST)
{
$supplierID = $_POST["supplierID"];
$comments = $_POST["comments"];
$purchase_order = $_POST["purchase_order"];


if(isset($supplierID)){
	mysql_query("UPDATE tbl_cart_receiving SET supplierID = '$supplierID' WHERE accountID='$accountID'");
}elseif(isset($comments)){
	$comments = mysql_real_escape_string($_POST["comments"]);
	mysql_query("UPDATE tbl_cart_receiving SET comments = '$comments' WHERE accountID='$accountID'");
}elseif(isset($purchase_order)){
	$purchase_order = mysql_real_escape_string($_POST["purchase_order"]);
	mysql_query("UPDATE tbl_cart_receiving SET purchase_order = '$purchase_order' WHERE accountID='$accountID'");
}

}
?>
