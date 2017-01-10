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



include 'db.php';

	
?>
<?php
if(isset($_POST["add"])){
	$description = mysql_real_escape_string(htmlspecialchars(trim($_POST["description"])));
	$expenses = mysql_real_escape_string(htmlspecialchars(trim($_POST["expenses"])));
	mysql_query("INSERT INTO tbl_cart_expenses VALUES('','$description','$expenses','$accountID','','')");
	header("location:expenses");
}

if(isset($_POST["delete"])){
	
	mysql_query("DELETE FROM tbl_cart_expenses WHERE accountID='$accountID'");
	header("location:expenses");
}
?>