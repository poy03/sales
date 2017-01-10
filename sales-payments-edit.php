<?php
ob_start();
session_start();
$accountID=@$_SESSION['accountID'];
$page=@$_GET['page'];
$cat=@$_GET['cat'];
$keyword=@$_GET['keyword'];
$search=@$_GET['search'];
			$by=@$_GET['by'];
			$order=@$_GET['order'];

#$connect = mysql_connect("localhost","qfcdavao_admin","_39a11nwpm");
#mysql_select_db("qfcdavao_dbinventory");

include 'db.php';


?>
<?php
if($_POST){
	$payment = $_POST["payment"];
	$orderID=@$_POST['id'];
	$balquery = mysql_query("SELECT * FROM tbl_orders WHERE orderID='$orderID'");	
	while($row=mysql_fetch_assoc($balquery)){
		$total = $row["total"];
	}
	$balance = $total - $payment;
	if($balance<0){
		$balance=0;
	}
	mysql_query("UPDATE tbl_orders SET payment='$payment', balance='$balance' WHERE orderID='$orderID'");
}
?>