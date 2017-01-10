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
<!DOCTYPE html>
<html lang="en">
<head>
  <title><?php echo $app_name; ?></title>
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
  <style>
  .item:hover{
	  cursor:pointer;
  }
  </style>
  <script>
  </script>
    <style>
	.page{
		display:none;
	}
	
@media print{
	.page{
		display:inline !important;
	}
  .prints{
	  display:none;
	  }
	  .content{
		  border-color:white;
	  }
	  
    a[href]:after {
    content: none !important;
  }
}
  </style>
</head>
<body>

<div class="container-fluid">
  <div class='row'>
  	<div class='col-md-12 prints'>
	
	
	<?php
	if($logged==1||$logged==2){

	?>
	
	
	
	
	
	
	
	<?php
if($logged!=0){
echo $_POST["customer"];
if(isset($_POST["savecontinue"])){
	$itemIDarray = $_POST["itemID"];
	$quantity = $_POST["quantity"];
	$customer = mysql_real_escape_string(htmlspecialchars(htmlspecialchars_decode(trim($_POST["customer"]))));
	$typeprice = $_POST["type"];
	$terms = $_POST["terms"];
	$customerID = $_POST["customerID"];
	$comments = mysql_real_escape_string(htmlspecialchars(trim($_POST["comments"])));
	$price = $_POST["price"];
	$carttotal = $_POST["total"];
	$payment = @$_POST["payment"];
	$type_payment_post = $_POST["type_payment"];
	$subtotal = array();
	$i =0;
	$total = 0;
	
	
	

	
	if(isset($_POST["savecontinue"])){
		$timezone  = 0; //(GMT -5:00) EST (U.S. & Canada) 
		  $datenow=date("m/d/Y", time() + 3600*($timezone+date("I")));
		  $timenow=date("h:i:s A", time() + 3600*($timezone+date("I")));
		//if($payment>$carttotal){
			$i=0;
			$totalprofits = 0;
			$totalloss = 0;
			$all_total_cost_price = 0;
			$cart_query = mysql_query("SELECT * FROM tbl_cart WHERE accountID='$accountID'");
			while($cart_row=mysql_fetch_assoc($cart_query)){
				$itemID = $cart_row["item_detail_id"];

				$item_detail_data = mysql_fetch_assoc(mysql_query("SELECT* FROM tbl_items_detail WHERE item_detail_id='$itemID' "));

				$itemquery = mysql_query("SELECT * FROM tbl_items WHERE itemID='".$item_detail_data["itemID"]."'");
				while($itemrow=mysql_fetch_assoc($itemquery)){
					$costprice = $itemrow['costprice'];
				}
				$total_cost_price = $costprice*$quantity[$i];
				$profits = ($price[$i]*$quantity[$i])-($costprice*$quantity[$i]);
				$loss = ($costprice*$quantity[$i])-($price[$i]*$quantity[$i]);
				
				if($profits<0){
					$profits=0;
				}
				if($loss<0){
					$loss=0;
				}				
				$subtotal[$i] = $price[$i] * $quantity[$i];
				$total = $total + $subtotal[$i];


				$invquery = mysql_query("SELECT* FROM tbl_items_detail WHERE item_detail_id='$itemID'");
				while($invrow=mysql_fetch_assoc($invquery)){
					$remain = $invrow["quantity"];
					$item_detail_id = $invrow["item_detail_id"];
					$itemID = $invrow["itemID"];
					$serial_number = $invrow["serial_number"];
				$remain=$remain-$quantity[$i];
				}

				$cart_data = mysql_fetch_assoc(mysql_query("SELECT * FROM tbl_cart WHERE itemID = '$itemID' AND accountID='$accountID'"));
				mysql_query("INSERT INTO tbl_purchases (itemID,quantity,price,subtotal,accountID,profit,loss,state,costprice,customerID,date_ordered,serial_number,date_ordered_int,item_detail_id) VALUES ('$itemID','$quantity[$i]','$price[$i]','$subtotal[$i]','$accountID','$profits','$loss','0','$total_cost_price','$customerID','$datenow','".$serial_number."','".strtotime($datenow)."','$item_detail_id')");

				mysql_query("UPDATE tbl_items_detail SET quantity='$remain' WHERE item_detail_id='$item_detail_id'");



				$orderquery = mysql_query("SELECT * FROM tbl_orders ORDER BY orderID DESC LIMIT 0,1");
				if(mysql_num_rows($orderquery)!=0){
					while($orderrow=mysql_fetch_assoc($orderquery)){
						$orderID = $orderrow["orderID"]+1;
					}
				}else{
					$orderID="1";
				}

				$description = mysql_real_escape_string($_POST["comments"]);
				// var_dump($description);

				mysql_query("INSERT INTO tbl_items_history (item_detail_id,itemID,description,date_time,quantity,accountID,serial_number,type,referenceID) VALUES ('$item_detail_id','$itemID','".$description."','".strtotime(date("m/d/Y"))."','$quantity[$i]','$accountID','$serial_number','Sales','$orderID')");


				$totalprofits = $totalprofits + $profits;
				$totalloss = $totalloss + $loss;
				$all_total_cost_price+=$total_cost_price;
				$i++;
				
			}
		
		
		

		
		$date_due=strtotime($datenow. ' + '.$terms.' day');

		mysql_query("INSERT INTO tbl_orders (date_ordered,time_ordered,accountID,total,customer,profits,loss,balance,costprice,comments,date_due,customerID) VALUES('".strtotime($datenow)."','$timenow','$accountID','$total','$customer','$totalprofits','$totalloss','$total','$all_total_cost_price','$comments','$date_due','$customerID')");

		
		$orderquery = mysql_query("SELECT * FROM tbl_orders ORDER BY orderID DESC LIMIT 0,1");
		if(mysql_num_rows($orderquery)!=0){
			while($orderrow=mysql_fetch_assoc($orderquery)){
				$orderID = $orderrow["orderID"];
			}
		}else{
			$orderID="1";
		}
		$i=0;
		$cart_query = mysql_query("SELECT * FROM tbl_cart WHERE accountID='$accountID'");
		while($cart_row=mysql_fetch_assoc($cart_query)){
			$item_detail_id = $cart_row["item_detail_id"];
			mysql_query("UPDATE tbl_purchases SET orderID='$orderID' , state='1' WHERE accountID='$accountID' AND state='0' AND item_detail_id='$item_detail_id'");
			$i++;
		}
		mysql_query("DELETE FROM tbl_cart WHERE accountID='$accountID'");
		foreach($type_payment_post as $type_payment_post_each){
			if(preg_match("/\bcredit\b/i", $type_payment_post_each)){
				$credit_status = 1;
				$payment = $total;
				mysql_query("UPDATE tbl_orders SET balance = '0' WHERE orderID='$orderID'");
			}else{
				$credit_status = 0;
				$payment = 0;
			}
			mysql_query("INSERT INTO tbl_payments VALUES('','$type_payment_post_each','$payment','$accountID','$orderID','','','".strtotime(date("m/d/Y"))."','$timenow','$date_due','$customerID','','$credit_status')");
		}
	
		
		$_SESSION["orderID"]=$orderID;
		echo ($typeprice);
		header("location:sales-complete");

	}else{
		if(isset($typeprice)&&strtolower($typeprice)=='dp'){
			$typeprice = "dp";
			header("location:sales?type=dp");
		}else{
			$typeprice = 'srp';
			header("location:sales");
		}
	}
}



if(isset($_POST["reset"])){
	$itemIDarray = $_POST["itemID"];
	$quantity = $_POST["quantity"];
	$price = $_POST["price"];
	$typeprice = @$_POST["type"];
	$i =0;
	foreach($itemIDarray as $itemID){
		mysql_query("UPDATE tbl_cart SET price='0' WHERE accountID='$accountID'");
		if(isset($typeprice)&&strtolower($typeprice)=='dp'){
			$typeprice = "dp";
			header("location:sales?type=dp");
		}else{
			$typeprice = 'srp';
			header("location:sales");
		}
	}

}
if(isset($_POST["delete"])){
	$itemIDarray = $_POST["itemID"];
	foreach($itemIDarray as $itemID){
		mysql_query("DELETE FROM tbl_cart WHERE accountID='$accountID' AND itemID='$itemID'");
		if(isset($typeprice)&&strtolower($typeprice)=='dp'){
			$typeprice = "dp";
			header("location:sales?type=dp");
		}else{
			$typeprice = 'srp';
			header("location:sales");
		}
	}
}
$deleteid = @$_GET["id"];
if(isset($deleteid)){
	mysql_query("DELETE FROM tbl_cart WHERE accountID='$accountID' AND itemID='$deleteid'");
	if(isset($typeprice)&&strtolower($typeprice)=='dp'){
		$typeprice = "dp";
		header("location:sales?type=dp");
	}else{
		$typeprice = 'srp';
		header("location:sales");
	}
}

	

}else{
	header("location:index");
}
?>
	
	<script>
		// window.location='sales';
	</script>
	
	
	
	

	
	<?php
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
<?php mysql_close($connect);?>
  <script>
$("[data-toggle=popover]")
.popover({html:true})
</script>
