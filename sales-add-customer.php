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
if(isset($customerID)){
	$customer_query = mysql_query("SELECT * FROM tbl_customer WHERE customerID='$customerID'");
	while($row=mysql_fetch_assoc($customer_query)){
		$customername=mysql_real_escape_string(htmlspecialchars(trim($row["companyname"])));
	}
	mysql_query("UPDATE tbl_cart SET customerID='$customerID', Customer='$customername' WHERE accountID='$accountID'");
	if($customerID==0){
		mysql_query("UPDATE tbl_cart SET type_payment='' WHERE accountID='$accountID'");
	}
	header("location:sales");
}elseif(isset($customer)){
	$customer = mysql_real_escape_string(htmlspecialchars(trim($customer)));
	mysql_query("UPDATE tbl_cart SET customerID='0', Customer='$customer' WHERE accountID='$accountID'");

	header("location:sales");
}else{
	header("location:sales");
}

?>