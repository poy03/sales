<?php
ob_start();
session_start();
$accountID=@$_SESSION['accountID'];
$orderID=@$_GET['id'];
echo "<input type='hidden' id='id' value='$orderID'>";
$page=@$_GET['page'];
$cat=@$_GET['cat'];
$keyword=@$_GET['keyword'];
$search=@$_GET['search'];
			$by=@$_GET['by'];
			$order=@$_GET['order'];

#$connect = mysql_connect("localhost","qfcdavao_admin","_39a11nwpm");
#mysql_select_db("qfcdavao_dbinventory");

include 'db.php';


if($reports=='1'){
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
   
  <script src="jquery-ui.js"></script><script type="text/javascript" src="js/shortcut.js"></script>
  <style>
  .item:hover{
	  cursor:pointer;
  }
  input {
    text-align:right;
}
  </style>
  <script>
  $(document).ready(function(){
	  	  $("#confirm").click(function(){
		  var comments = $("#comments").val();
		  var orderID = $("#id").val();
		  window.location = "sales-delete?id="+orderID+"&comments="+comments;
	  });
	  
	  
	  $("#payment").change(function(){
		  var total = parseFloat($("#total").val());
		  var payment = parseFloat($(this).val());
		  var id = $("#id").val();
		  var change = payment-total;
		  var balance = total-payment;
		  var payment_sql = 'payment='+payment+'&id='+id;
		  $.ajax({
			  type: "POST",
			  data: payment_sql,
			  cache: false,
			  url: 'sales-payments-edit',
			  success: function(){
				  
			  return false;
			  }
		  });
		  

		  if(change>0){
			  change = change.toFixed(2);
			  change = addCommas(change);
			  $("#change").html("₱"+change);
			  $("#balance").html("₱0.00");
		  }else{
			  balance = balance.toFixed(2);
			  balance = addCommas(balance);
			  $("#change").html("₱0.00");
			  $("#balance").html("₱"+balance)
		  }
		  payment = payment.toFixed(2);
		  payment = addCommas(payment);
		  $(this).val(payment);
	  });
	  
	$( "#search" ).autocomplete({
      source: 'search-item-all',
	  select: function(event, ui){
		  window.location='item?s='+ui.item.data;
	  }
    });
	  
	  
  });
  
  function addCommas(nStr)
{
	nStr += '';
	x = nStr.split('.');
	x1 = x[0];
	x2 = x.length > 1 ? '.' + x[1] : '';
	var rgx = /(\d+)(\d{3})/;
	while (rgx.test(x1)) {
		x1 = x1.replace(rgx, '$1' + ',' + '$2');
	}
	return x1 + x2;
}
  </script>
    <style>
	#item_results
	{
		position:absolute;
		width:250px;
		z-index:5;
		max-height:200px;
		padding:10px;
		display:none;
		margin-top:-1px;
		border-top:0px;
		overflow:auto;
		border:1px #CCC solid;
		background-color: white;
	}
	.show,.cusclose
	{
		padding:10px; 
		border-bottom:1px #999 dashed;
		font-size:15px; 
	}
	.cusclose:hover,.show:hover
	{
		background:#4c66a4;
		color:#FFF;
		cursor:pointer;
	}
	
	
	.page{
		display:none;
	}
	
@media print{
	input {
    border: none;
    background: transparent;
}
input[type=number]::-webkit-inner-spin-button, 
input[type=number]::-webkit-outer-spin-button { 
  -webkit-appearance: none; 
  margin: 0; 
}
	
	
	.page{
		display:inline !important;
	}
.col-md-12{
	padding-left:1.5em !important;
	padding-right:1.5em !important;
	padding-top:1em !important;
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
@media print {
    body {
        font-size: 75%;
		font-family: Arial, Helvetica, sans-serif;
    }
}




border-color: transparent;}
  </style>
</head>
<body>
	   <nav class = "navbar navbar-default" role = "navigation" id='heading'>
	   <div class = "navbar-header">
		  <button type = "button" class = "navbar-toggle" 
			 data-toggle = "collapse" data-target = "#example-navbar-collapse">
			 <span class = "sr-only">Toggle navigation</span>
			 <span class = "icon-bar"></span>
			 <span class = "icon-bar"></span>
			 <span class = "icon-bar"></span>
		  </button>
		  <a class = "navbar-brand" href = "index"><?php echo $app_name; ?></a>
	   </div>
	   
	   <div class = "collapse navbar-collapse" id = "example-navbar-collapse">
		  <ul class = "nav navbar-nav  navbar-left">
		  <?php
		  $header = new Template;
		  foreach ($list_modules as $module) {
			if($module==1){
				$badge_arg = $badge_i;
			}elseif($module==3){
				$badge_arg = $badge;
			}elseif($module==8){
				$badge_arg = $badge_credit;
			}else{
				$badge_arg = 0;
			}
			echo $header->header($module,$badge_arg,1);
		  }
		  ?>

		 <?php if($logged!=0){ ?>
		 <div class="form-group navbar-form navbar-right">
			<input type="text" class="form-control" placeholder="Search" name='search' id='search' autocomplete='off'><div id='item_results'></div>
		 </div>
		 <?php } ?>
						
		  </ul>

		  
		  
		  <?php 
		  if($logged==0){ ?>
		  	<ul class='nav navbar-nav navbar-right'>
				<li><a href='login'><span class='glyphicon glyphicon-log-in'></span> Login</a></li>
			</ul>
		  <?php }else{ ?>
		  	<ul class='nav navbar-nav navbar-right'>
				
				
				<li>
					<a href='#' role='button' 
					  data-container = 'body' data-toggle = 'popover' data-placement = 'bottom' 
					  data-content = "
						<a href='settings' class = 'list-group-item'><span class='glyphicon glyphicon-cog'></span> Settings</a>
						<a href = 'maintenance' class = 'list-group-item'><span class='glyphicon glyphicon-hdd'></span> Maintenance</a><a href = 'logout' class = 'list-group-item'><span class='glyphicon glyphicon-log-out'></span> Logout</a><a href = '#' class = 'list-group-item shutdown'><span class='glyphicon glyphicon-off'></span> Shutdown</a>
										  					  
					  ">
					Hi <?php echo $employee_name; ?></a></a>
				</li>				
				
			</ul>
		  <?php }?>

		  
		  
		  </div>

	   </nav>	
<div class="container-fluid">
  <div class='row'>
  	<div class='col-md-12'>
	<div class='col-md-12 final'>
	<?php
	if($logged==1||$logged==2){
		
		
		?>

		
		<!-- Modal -->
<div class = "modal fade" id = "myModal" tabindex = "-1" role = "dialog" 
   aria-labelledby = "myModalLabel" aria-hidden = "true">
   
   <div class = "modal-dialog">
      <div class = "modal-content">
         <form action='#' method='get'>
         <div class = "modal-header">
            <button type = "button" class = "close" data-dismiss = "modal" aria-hidden = "true">
                  &times;
            </button>
            
            <h4 class = "modal-title" id = "myModalLabel">
               Delete this Sale?
            </h4>
         </div>
         
         <div class = "modal-body">
            <label><b>Reason for deleting this sale:<br><small><i>* Note: Items will be automatically returned in the inventory.</i></small></b></label>
            <input type='hidden' id='id' value='<?php echo $orderID; ?>'>
			<textarea id='comments' class='form-control' required='required'></textarea>
			
         </div>
         
         <div class = "modal-footer">
            <button type = "button" class = "btn btn-default" data-dismiss = "modal">
               Cancel
            </button>
            
            <button type = "button" class = "btn btn-danger" id='confirm' type='submit'>
               Confirm
            </button>
         </div>
         </form>
      </div><!-- /.modal-content -->
   </div><!-- /.modal-dialog -->
  
</div><!-- /.modal -->
		
		<?php
		
		if(isset($orderID)){
		 $orderquery = mysql_query("SELECT * FROM tbl_orders WHERE orderID='$orderID'");
		 while($row=mysql_fetch_assoc($orderquery)){
			 $balance = $row["balance"];
			 $date_ordered = $row["date_ordered"];
			 $time_ordered = $row["time_ordered"];
			 $dbaccountID = $row["accountID"];
			 $comments = $row["comments"];
			 $deleted = $row["deleted"];
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
			<span style='font-size:125%;'>$app_company_name</span><br>
			<span>$address</span><br>
			<span>$contact_number</span><br>
			</center>			
		 <br>
		 Sale ID S".sprintf("%06d",$orderID)."<br>
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
			<td>
			<div class='table-row-pox'>
			$itemname
			</div>
			</td>
			<td>$quantity</td>
			<td align='right' colspan='2'>₱".number_format($price,2)."</td>

			<td align='right'><b>₱".number_format($subtotal,2)."</b></td>
		</tr>
		";
	}
	
	?>
	</tbody>
	<tfoot id='f'>
	<tr  style='text-align:right;'>
		<td colspan='4' align='right'><b>Total:</b></td>
		<td><b>₱<?php echo number_format($total,2); ?></b></td>
	</tr>
	<?php
		$total_payment = 0;
		$change = 0;
		$payment_query = mysql_query("SELECT * FROM tbl_payments WHERE orderID='$orderID'");
		if(mysql_num_rows($payment_query)!=0){
			while($payment_row=mysql_fetch_assoc($payment_query)){
				$type_payment_db = $payment_row["type_payment"];
				$payment = $payment_row["payment"];
				$ref_num = $payment_row["comments"];
				$total_payment+=$payment;
				//<input style='width:100%' type='text' id='$type_payment_db' class='comments' title='$type_payment_db' style='width:7em;' value='$comments'>	
				echo "
					<tr style='text-align:right;'>
						<td colspan='3' align='right' style='border:0px'><span id='$type_payment' class='comments'>$ref_num</span></td>
						<td style='border:0px'><b>$type_payment_db </b></td>
						<td style='border:0px'><b>₱".number_format($payment,2)."<b></td>
					</tr>
				";
			}
		}
		echo "
		<tr style='text-align:right;'>
			<td colspan='4' align='right' style='border:0px;'><b>Total Payment:</b></td>
			<td style='border:0px'><b><span id='change'>₱".number_format($total_payment,2)."<b></td>
		</tr>
		";
		$change = $total_payment - $total;
		if($change<0){
			$change=0;
		}
		echo "
		<tr style='text-align:right;'>
			<td colspan='4' align='right' style='border:0px;'><b>Change Due:</b></td>
			<td style='border:0px;'><b><span id='change'>₱".number_format($change,2)."<b></td>
		</tr>
		";

	?>

	<tr style='text-align:right;'>
		<td colspan='4' align='right' style='border:0px;'><b>Balance:</b></td>
		<td style='border:0px;'><b><span id='balance'>₱<?php echo number_format($balance,2); ?><b></td>
	</tr>
	</tfoot>
	</table>
	<hr>
	<center>
	Thank You For Your Business!
	</center>
	
	<span class='prints'><b>Comments:</b> <?php echo $comments;?>
	<br>
	<b>Status:</b><?php
	if($deleted=='1'){
		echo "Deleted";
	}else{
		echo "Processed";	
	}

echo "
		<div class='prints col-md-12'>
		<div class='col-md-6'>
		<button onclick='myFunction()' class='btn btn-primary btn-block'><span class='glyphicon glyphicon-print'></span> Print</button>
		</div>
		
";

if($deleted=='0'){
	echo "
			<div class='col-md-6'>
		<button class='btn btn-danger btn-block'  data-toggle = 'modal' data-target = '#myModal'><span class='glyphicon glyphicon-trash'></span> Delete Sales</button>
		</div>
		";
}
echo"
		
		</div>
		<script>
function myFunction() {
    window.print();
}
</script>
		
		";	
	?></span>
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