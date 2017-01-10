<?php
ob_start();
session_start();
$accountID=@$_SESSION['accountID'];
$cartID=@$_GET['id'];
$cat=@$_GET['cat'];
$keyword=@$_GET['keyword'];
$search=@$_GET['search'];
			$by=@$_GET['by'];
			$order=@$_GET['order'];



include 'db.php';


?>
<?php
if(isset($cartID)){
	mysql_query("DELETE FROM tbl_cart_expenses WHERE cartID='$cartID'");
	header("location:expenses");
}else{
	header("location:expenses");
}

	//mysql_query("DELETE FROM tbl_cart_expenses WHERE accountID='$accountID'");
?>