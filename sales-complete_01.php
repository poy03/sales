<?php
ob_start();
session_start();
$accountID=@$_SESSION['accountID'];
$orderID=@$_SESSION['orderID'];
$page=@$_GET['page'];
$cat=@$_GET['cat'];
$keyword=@$_GET['keyword'];
$search=@$_GET['search'];
			$by=@$_GET['by'];
			$order=@$_GET['order'];

#$connect = mysql_connect("localhost","qfcdavao_admin","_39a11nwpm");
#mysql_select_db("qfcdavao_dbinventory");

include 'db.php';
echo "<input type='hidden' id='orderID' value='$orderID'>";
if($sales=='1'){
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title><?php echo $app_name; ?> - Sales Receipt</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php
  if(isset($themes)&&$themes!=''){
	  echo "<link rel='stylesheet' href='css/$themes'>";
  }else{
	  echo "<link rel='stylesheet' href='css/bootstrap.min.css'>";
  }
  
  ?>
  <link rel="stylesheet" href="style.css">
  <script src="jquery.min.js"></script>
  <script src="main.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="themes/smoothness/jquery-ui.css">
  <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  <script src="jquery-ui.js"></script>
  <script type="text/javascript" src="js/shortcut.js"></script>
  
</head>
<body>

<div class="container-fluid">
  <div class='row'>

	<!-- final print -->
	
	
	
	<div class='col-md-12'>
	<?php
	if($logged==1||$logged==2){
		
		
		?>

	
		
		<?php
		
		if(isset($orderID)){
		 $orderquery = mysql_query("SELECT * FROM tbl_orders WHERE orderID='$orderID'");
		 while($row=mysql_fetch_assoc($orderquery)){
			 $balance = $row["balance"];
			 $date_ordered = $row["date_ordered"];
			 $time_ordered = $row["time_ordered"];
			 $dbaccountID = $row["accountID"];
			 $accountquery = mysql_query("SELECT * FROM tbl_users WHERE accountID='$dbaccountID'");
			 while($accountrow=mysql_fetch_assoc($accountquery)){
				 $account_name = $accountrow["employee_name"];
			 }
			 $total = $row["total"];
			 $customer = $row["customer"];
		 }
		 
		 echo "
		 <input type='hidden' id='total' value='$total'>
		 	<center>	
			<span style='font-size:125%;'><b>$app_company_name</b></span><br>
			<span>$address</span><br>
			<span>$contact_number</span><br>
			</center>			
		 <br>
		 Sale ID S".sprintf("%06d", $orderID)."<br>
		 Customer Name: <b>$customer</b><br>
		 Employee: <b>$account_name</b><br>
		 Date/Time: <b>$date_ordered $time_ordered</b><br><br>
		 ";
		 
	?>
	
	<table class='table table-hover'>
	<thead>
	<tr>
		<th style='text-overflow: ellipsis; width:40%'>Item</th>
		<th>Qty</th>
		<th style='text-align:right' colspan='2'>Price</th>
		<th style='text-align:right'>SubTotal</th>
	</tr>
	</thead>
	<tbody>
	<?php
	$purchasequery = mysql_query("SELECT * FROM tbl_purchases WHERE orderID='$orderID'");
	while($purchaserow=mysql_fetch_assoc($purchasequery)){
		$itemID = $purchaserow["itemID"];
		$quantity = $purchaserow["quantity"];
		$price = $purchaserow["price"];
		$subtotal = $purchaserow["subtotal"];
		$itemquery = mysql_query("SELECT * FROM tbl_items WHERE itemID='$itemID'");
		while($itemrow=mysql_fetch_assoc($itemquery)){
			$itemname = $itemrow["itemname"];
		}
		echo "
		<tr>
			<td  style='padding-top:0px;padding-bottom:0px'>
			<div class='table-row-pox'>
			$itemname
			</div>
			</td>
			<td style='padding-top:0px;padding-bottom:0px'>$quantity</td>
			<td align='right' colspan='2'  style='padding-top:0px;padding-bottom:0px'>₱".number_format($price,2)."</td>

			<td align='right' style='padding:0px'><b>₱".number_format($subtotal,2)."</b></td>
		</tr>
		";
	}
	
	?>
	</tbody>
	<tfoot id='f'>
	<tr  style='text-align:right;'>
		<td colspan='4' align='right' style='padding:0px;border:0px'><b>Total:</b></td>
		<td style='padding:0px'><b>₱<?php echo number_format($total,2); ?></b></td>
	</tr>
	<?php
		$total_payment = 0;
		$change = 0;
		$payment_query = mysql_query("SELECT * FROM tbl_payments WHERE orderID='$orderID'");
		if(mysql_num_rows($payment_query)!=0){
			while($payment_row=mysql_fetch_assoc($payment_query)){
				$type_payment_db = $payment_row["type_payment"];
				$payment = $payment_row["payment"];
				$comments = $payment_row["comments"];
				$total_payment+=$payment;
				//<input style='width:100%' type='text' id='$type_payment_db' class='comments' title='$type_payment_db' style='width:7em;' value='$comments'>	
				echo "
					<tr style='text-align:right;'>
						<td colspan='3' align='right' style='border:0px;padding:0px'><span id='$type_payment' class='comments'>$comments</span></td>
						<td style='border:0px;padding:0px'><b>$type_payment_db </b></td>
						<td style='border:0px;padding:0px'><b>₱".number_format($payment,2)."<b></td>
					</tr>
				";
			}
		}
		echo "
		<tr style='text-align:right;'>
			<td colspan='4' align='right' style='border:0px;padding:0px'><b>Total Payment:</b></td>
			<td style='border:0px;padding:0px'><b><span id='change'>₱".number_format($total_payment,2)."<b></td>
		</tr>
		";
		$change = $total_payment - $total;
		if($change<0){
			$change=0;
		}
		echo "
		<tr style='text-align:right;'>
			<td colspan='4' align='right' style='border:0px;padding:0px'><b>Change Due:</b></td>
			<td style='border:0px;padding:0px'><b><span id='change'>₱".number_format($change,2)."<b></td>
		</tr>
		";

	?>

	<tr style='text-align:right;'>
		<td colspan='4' align='right' style='border:0px;padding:0px'><b>Balance:</b></td>
		<td style='border:0px;padding:0px'><b><span id='balance'>₱<?php echo number_format($balance,2); ?><b></td>
	</tr>
	</tfoot>
	</table>
	<hr>
	<center>
	Thank You For Your Business!
	</center>
	<br>
	Received by:________________
	<?php

	
		}else{
			header("location:sales");
		}
	}else{
	?>
	Login
	<form action='login' role='form' method='post'>
	<input type='text' name='username' placeholder='Login Name' class='form-control'>
	<input type='Password' name='password' placeholder='Password' class='form-control'>
	<button class='btn btn-primary' name='login' type='submit'>Login
	</button>
	</form>

	<?php 
	} ?>
	</div>
	
  </div>
</div>
</body>
</html>
<?php mysql_close($connect);
}else{
		echo "<strong><center>You do not have the authority to access this module.</center></strong>";
	}
?>
  <script>
$("[data-toggle=popover]")
.popover({html:true})
</script>