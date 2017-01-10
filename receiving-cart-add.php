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
$reset=@$_POST['reset'];
$delall=@$_POST['delall'];
$serial_number=@$_POST['serial_number'];
if(isset($q)){
	mysql_query("UPDATE tbl_cart SET type_payment = '$q' WHERE accountID='$accountID'");
}elseif(isset($reset)){
	mysql_query("UPDATE tbl_cart_receiving SET price = '0' WHERE accountID='$accountID'");
}elseif(isset($delall)){
	mysql_query("DELETE FROM tbl_cart_receiving WHERE accountID='$accountID'");
}elseif(isset($serial_number)){
	mysql_query("UPDATE tbl_cart_receiving SET serial_number = '".trim($serial_number)."' WHERE cartID='$id'");
}else{
	$item_data = mysql_fetch_assoc(mysql_query("SELECT * FROM tbl_items WHERE itemID='$id'"));
	if(isset($value)){
		mysql_query("UPDATE tbl_cart_receiving SET quantity = '$value' WHERE accountID='$accountID' AND itemID='$id'");
		$cart_data = mysql_fetch_assoc(mysql_query("SELECT * FROM tbl_cart_receiving WHERE accountID='$accountID' AND itemID='$id'"));
		($cart_data["costprice"]==0?$cart_data["costprice"]=$item_data["costprice"]:false);
		if($cart_data["mode"]!="restock"){
			echo number_format($cart_data["costprice"]*$cart_data["quantity"],2);
		}else{
			echo number_format(0,2);
		}
	}else{
		mysql_query("UPDATE tbl_cart_receiving SET costprice = '$price' WHERE accountID='$accountID' AND itemID='$id'");
		$cart_data = mysql_fetch_assoc(mysql_query("SELECT * FROM tbl_cart_receiving WHERE accountID='$accountID' AND itemID='$id'"));
		($cart_data["costprice"]==0?$cart_data["costprice"]=$item_data["costprice"]:false);
		if($cart_data["mode"]!="restock"){
			echo number_format($cart_data["costprice"]*$cart_data["quantity"],2);
		}else{
			echo number_format(0,2);
		}
	}
}

}
?>
