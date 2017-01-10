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
$item_detail_id=@$_GET['id'];
if(isset($item_detail_id)){
	$itemquery = mysql_query("SELECT * FROM tbl_cart WHERE item_detail_id='$item_detail_id' AND accountID='$accountID'");
	$item_data = mysql_fetch_assoc(mysql_query("SELECT * FROM tbl_items_detail WHERE item_detail_id='$item_detail_id'"));
	$itemID = $item_data["itemID"];
	if(mysql_num_rows($itemquery)!=0){
		while($row=mysql_fetch_assoc($itemquery)){
			$quantity = $row["quantity"];
		}
		$quantity++;
		if($item_data["has_serial"]==0){
			mysql_query("UPDATE tbl_cart SET quantity='$quantity' WHERE itemID='$itemID' AND accountID='$accountID'");
		}
	}else{
		$query = "SELECT * FROM tbl_cart";
		$cart_query = mysql_query($query);
		if(mysql_num_rows($cart_query)!=0){
			while($cart_row=mysql_fetch_assoc($cart_query)){
				$customer_name = $cart_row["Customer"];
				$customerID = $cart_row["customerID"];
				$type_payment = $cart_row["type_payment"];
				$type_price = $cart_row["type_price"];
				$terms = $cart_row["terms"];
			}
			mysql_query("INSERT INTO tbl_cart VALUES('','$itemID','1','0','$accountID','$customer_name','$type_payment','$customerID','','$type_price','$terms','$item_detail_id')");
		}else{
			mysql_query("INSERT INTO tbl_cart VALUES('','$itemID','1','0','$accountID','','','','','srp','','$item_detail_id')");
		}
	}
	header("location:sales");
}else{
	header("location:index");
}
?>
