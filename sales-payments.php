<?php
ob_start();
session_start();
$accountID=@$_SESSION['accountID'];
$orderID=@$_SESSION['orderID'];


include 'db.php';

?>
<?php
if($_POST){
	$value = @$_POST["value"];
	$type_payment_post = @$_POST["type_payment"];
	$comments = mysql_real_escape_string(@$_POST["comments"]);
	$id = @$_POST["id"];
	$timezone  = 0; //(GMT -5:00) EST (U.S. & Canada) 
	$datenow=gmdate("m/d/Y", time() + 3600*($timezone+date("I")));
	$timenow=gmdate("h:i:s A", time() + 3600*($timezone+date("I")));

		mysql_query("UPDATE tbl_payments SET payment='$value' WHERE type_payment = '$type_payment_post' AND orderID='$orderID'");
		mysql_query("UPDATE tbl_payments SET date='".strtotime(date("m/d/Y"))."', time='$timenow' WHERE orderID='$orderID'");
		
		$payment_query = mysql_query("SELECT SUM(payment) as total_payment FROM tbl_payments WHERE orderID='$orderID'");
		while($payment_row=mysql_fetch_assoc($payment_query)){
			$total_payment = $payment_row["total_payment"];
		}
		
		$balquery = mysql_query("SELECT * FROM tbl_orders WHERE orderID='$orderID'");	
		while($row=mysql_fetch_assoc($balquery)){
			$total = $row["total"];
		}
		$balance = $total - $total_payment;
		if($balance<0){
			$balance=0;
		}

		mysql_query("UPDATE tbl_orders SET payment='$total_payment', balance='$balance' WHERE orderID='$orderID'");
	
		$order_query = mysql_query("SELECT * FROM tbl_orders WHERE orderID='$orderID'");
		while($order_row=mysql_fetch_assoc($order_query)){
			$orderID=$order_row["orderID"];
			$total=$order_row["total"];
			$payment=$order_row["payment"];
			$payment_query = mysql_query("SELECT * FROM tbl_payments WHERE orderID='$orderID'");
			$cash_involved = 0;
			while($payment_row=mysql_fetch_assoc($payment_query)){
				$paymentID = $payment_row["paymentID"];
				$type_payment = $payment_row["type_payment"];
				
				if(preg_match("/\bcash\b/i",$type_payment)){
					$cash_involved = 1;
				}
			}
			if($cash_involved==1){
				$change = $payment-$total;
				if($change<0){
					$change = 0;
				}
				mysql_query("UPDATE tbl_payments SET cash_change='$change' WHERE orderID='$orderID'");
			}
		}
		echo number_format($total_payment,2);
}
?>