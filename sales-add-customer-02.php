<?php
ob_start();
session_start();
$accountID=@$_SESSION['accountID'];
$page=@$_GET['page'];
$customerID=@$_GET['id'];
$customer=@$_GET['customer'];
$keyword=@$_GET['keyword'];
$search=@$_GET['search'];
			$by=@$_GET['by'];
			$order=@$_GET['order'];

#$connect = mysql_connect("localhost","qfcdavao_admin","_39a11nwpm");
#mysql_select_db("qfcdavao_dbinventory");

include 'db.php';


?>
<?php
if($_POST)
{
	$customer = mysql_real_escape_string(htmlspecialchars(trim($_POST["customer"])));
	mysql_query("UPDATE tbl_cart SET customer='$customer', customerID='0' WHERE accountID='$accountID'");
}

?>