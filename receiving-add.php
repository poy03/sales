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
$itemID=@$_GET['id'];
$mode=@$_GET['mode'];
$supplierID=@$_GET['sup'];
if(isset($itemID)){
	$itemquery = mysql_query("SELECT * FROM tbl_cart_receiving WHERE itemID='$itemID' AND accountID='$accountID'");
	if(mysql_num_rows($itemquery)!=0){
		while($row=mysql_fetch_assoc($itemquery)){

			$quantity = $row["quantity"];
			$costprice = $row["costprice"];
		}
		$quantity++;
		$total = $quantity * $costprice;
		$item_data = mysql_fetch_assoc(mysql_query("SELECT * FROM tbl_items WHERE itemID='$itemID'"));
		if($item_data["has_serial"]==1){
			$cart_query = mysql_query("SELECT * FROM tbl_cart_receiving");
			while($cart_row=mysql_fetch_assoc($cart_query)){
				$supplierID = $cart_row["supplierID"];
				$mode = $cart_row["mode"];
				$comments = $cart_row["comments"];
				$purchase_order = $cart_row["purchase_order"];
			}

				
			mysql_query("INSERT INTO tbl_cart_receiving (itemID,quantity,accountID,supplierID,mode,costprice,subtotal,comments,purchase_order) VALUES('$itemID','1','$accountID','$supplierID','receive','$costprice','$costprice','$comments','$purchase_order')");
		}else{
			mysql_query("UPDATE tbl_cart_receiving SET quantity='$quantity', subtotal = '$total' WHERE itemID='$itemID' AND accountID='$accountID'");
		}
	}else{
		$query = mysql_query("SELECT * FROM tbl_items WHERE itemID='$itemID'");
		while($itemrow=mysql_fetch_assoc($query)){
			$costprice = $itemrow["costprice"];
		}
		$cart_query = mysql_query("SELECT * FROM tbl_cart_receiving");
		if(mysql_num_rows($cart_query)!=0){
			while($cart_row=mysql_fetch_assoc($cart_query)){
				$supplierID = $cart_row["supplierID"];
				$mode = $cart_row["mode"];
				$comments = $cart_row["comments"];
				$purchase_order = $cart_row["purchase_order"];
			}
			mysql_query("INSERT INTO tbl_cart_receiving (itemID,quantity,accountID,supplierID,mode,costprice,subtotal,comments,purchase_order) VALUES('$itemID','1','$accountID','$supplierID','receive','$costprice','$costprice','$comments','$purchase_order')");
		}else{
			mysql_query("INSERT INTO tbl_cart_receiving (itemID,quantity,accountID,mode,costprice,subtotal) VALUES('$itemID','1','$accountID','receive','$costprice','$costprice')");			
		}

	}
	header("location:receiving");
}elseif(isset($mode)){
	mysql_query("UPDATE tbl_cart_receiving SET mode='$mode' WHERE accountID='$accountID'");
	header("location:receiving");
}else{
	header("location:index");
}
?>
