<?php
ob_start();
session_start();

#$connect = mysql_connect("localhost","qfcdavao_admin","_39a11nwpm");
#mysql_select_db("qfcdavao_dbinventory");

include 'db.php';

?>

<?php
if($_POST)
{
$q=@$_POST['search'];
$id=@$_POST['id'];
$value=@$_POST['value'];
$price=@$_POST['price'];
$terms=@$_POST['terms'];
$type_payment_post=@$_POST['type_payment'];
$reset=@$_POST['reset'];
$delall=@$_POST['delall'];
if(isset($q)){
	mysql_query("UPDATE tbl_cart SET type_payment = '$q' WHERE accountID='$accountID'");
}elseif(isset($reset)){
	mysql_query("UPDATE tbl_cart SET price = '0' WHERE accountID='$accountID'");
}elseif(isset($delall)){
	mysql_query("DELETE FROM tbl_cart WHERE accountID='$accountID'");
}elseif(isset($type_payment_post)){
		mysql_query("UPDATE tbl_cart SET type_payment = '$type_payment_post' WHERE accountID='$accountID'");
}elseif(isset($terms)){
		mysql_query("UPDATE tbl_cart SET terms = '$terms' WHERE accountID='$accountID'");
}else{
	$item_data = mysql_fetch_assoc(mysql_query("SELECT * FROM tbl_items WHERE itemID='$id'"));
	if(isset($value)){
		mysql_query("UPDATE tbl_cart SET quantity = '$value' WHERE accountID='$accountID' AND itemID='$id'");
		$cart_data = mysql_fetch_assoc(mysql_query("SELECT * FROM tbl_cart WHERE accountID='$accountID' AND itemID='$id'"));
		$type_price = $cart_data["type_price"];
		($cart_data["price"]==0?$cart_data["price"]=$item_data["$type_price"]:false);
		echo number_format($cart_data["price"]*$cart_data["quantity"],2);
	}else{
		mysql_query("UPDATE tbl_cart SET price = '$price' WHERE accountID='$accountID' AND itemID='$id'");
		$cart_data = mysql_fetch_assoc(mysql_query("SELECT * FROM tbl_cart WHERE accountID='$accountID' AND itemID='$id'"));
		$type_price = $cart_data["type_price"];
		($cart_data["price"]==0?$cart_data["price"]=$item_data["$type_price"]:false);
		echo number_format($cart_data["price"]*$cart_data["quantity"],2);
	}
}

}
?>
