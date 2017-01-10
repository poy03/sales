<?php
ob_start();
session_start();

include 'db.php';

?>

<?php
if($_POST)
{
$total = @$_POST["total"];
if(isset($total)){
	if($total=="receiving"){
		$total_query = mysql_query("SELECT * FROM tbl_cart_receiving WHERE accountID='$accountID'");
		if(mysql_num_rows($total_query)!=0){
			$total=0;
			while($total_row=mysql_fetch_assoc($total_query)){
				$costprice = $total_row["costprice"];
				$quantity = $total_row["quantity"];
				$mode = $total_row["mode"];
				$itemID = $total_row["itemID"];
				$item_data = mysql_fetch_assoc(mysql_query("SELECT * FROM tbl_items WHERE itemID='$itemID'"));
				($costprice==0?$costprice=$item_data["costprice"]:false);
				$subtotal = ($quantity*$costprice);
				$total += $subtotal;
			}

			if($mode!="restock"){
				echo number_format($total,2);
			}else{
				echo number_format(0,2);
			}
		}
	}

	if($total=="sales"){
		$total_query = mysql_query("SELECT * FROM tbl_cart WHERE accountID='$accountID'");
		if(mysql_num_rows($total_query)!=0){
			$total=0;
			while($total_row=mysql_fetch_assoc($total_query)){
				$quantity = $total_row["quantity"];
				$itemID = $total_row["itemID"];
				$type_price = $total_row["type_price"];
				$price = $total_row["price"];
				$item_data = mysql_fetch_assoc(mysql_query("SELECT * FROM tbl_items WHERE itemID='$itemID'"));
				($price==0?$price=$item_data["$type_price"]:false);
				$subtotal = ($quantity*$price);
				$total += $subtotal;
			}
			echo number_format($total,2);
		}
	}

	if($total=="sales-complete"){
		$id = @$_POST["id"];
		$total_query = mysql_fetch_assoc(mysql_query("SELECT * FROM tbl_payments WHERE orderID='$id'"));
		echo number_format($total_query["cash_change"],2);
	}
	if($total=="sales-complete-balance"){
		$id = @$_POST["id"];
		$total_query = mysql_fetch_assoc(mysql_query("SELECT * FROM tbl_orders WHERE orderID='$id'"));
		echo number_format($total_query["balance"],2);
	}


}				
}
?>
