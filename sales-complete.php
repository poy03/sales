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
   
  <script src="jquery-ui.js"></script>
  <script type="text/javascript" src="js/shortcut.js"></script>
  <script>
  $(document).ready(function(){
	  $("#confirm").click(function(){
		  var comments = $("#comments").val();
		  var orderID = $("#id").val();
		  if(comments!=''){
			  window.location = "sales-delete?id="+orderID+"&comments="+comments;
		  }	
	  });
	 
	  $(".payment").on("keyup change",function(e){
		  var orderID = $("#orderID").val()
		  var datastr = 'id='+orderID+'&value='+e.target.value+'&type_payment='+e.target.id;
		  $.ajax({
			  type: 'POST',
			  url: 'sales-payments',
			  data: datastr,
			  cache: false,
			  success: function(html){
			  	$("#total_payment").html(html);
			  	var dataString = 'total=sales-complete&id='+orderID;
			  	$.ajax({
			  		type: 'POST',
			  		url: 'total',
			  		data: dataString,
			  		cache: false,
			  		success: function(html){
			  			// alert(html);
			  			$("#change").html(html);
			  			var dataString = 'total=sales-complete-balance&id='+orderID;
			  			$.ajax({
			  				type: 'POST',
			  				url: 'total',
			  				data: dataString,
			  				cache: false,
			  				success: function(html){
			  					$("#balance").html(html);
			  				}
			  			});
			  		}
			  	});
			  }
		  });
		  return false;
	  });
	  
	  $(".comments").on("keyup change",function(e){
		  
		  var orderID = $("#orderID").val()
		  // alert(e.target.value+e.target.id+orderID);
		  var datastr = 'id='+orderID+'&comments='+e.target.value+'&type_payment='+e.target.id+'&type=1';
		  $.ajax({
			  type: 'POST',
			  url: 'sales-payments-comments',
			  data: datastr,
			  cache: false,
			  success: function(html){

			  }
		  });
		  return false;
	  });
	   
   });
	  
  </script>
    <style>

      *{
    	  padding:0px;
    	  line-height: 1;
    	  padding: 0;
    	  margin: 0;
      }
      .table-row-pox{
    	v-align:center;
    	height:38px;
    	overflow:hidden;
        text-overflow: ellipsis;
    	}
      .item:hover{
    	  cursor:pointer;
      }
      input {
        text-align:right;
    }input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { 
      -webkit-appearance: none; 
      margin: 0;

    }

    .page{
		display:none;
	}
	td{
		padding: 1px !important;
		vertical-align: middle !important;
	}
@media print{
	::-webkit-input-placeholder { /* WebKit browsers */
	    color: transparent;
	}
	:-moz-placeholder { /* Mozilla Firefox 4 to 18 */
	    color: transparent;
	}
	::-moz-placeholder { /* Mozilla Firefox 19+ */
	    color: transparent;
	}
	:-ms-input-placeholder { /* Internet Explorer 10+ */
	    color: transparent;
	}

	html *{
		font-size:10pt !important;
	}
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
	body, table {
		font-size: 10pt !important;
		font-family: Arial, Helvetica, sans-serif;
	}
}
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
		<th style="text-align: center;">Serial Number</th>
		<th style="text-align: center;">Qty</th>
		<th style='text-align:right' colspan='2'>Price</th>
		<th style='text-align:right'>SubTotal</th>
	</tr>
	</thead>
	<tbody>
	<?php
	$purchasequery = mysql_query("SELECT * FROM tbl_purchases WHERE orderID='$orderID'");
	$i=0;
	while($purchaserow=mysql_fetch_assoc($purchasequery)){
		$i++;
		$item_detail_id = $purchaserow["itemID"];
		$quantity = $purchaserow["quantity"];
		$serial_number = $purchaserow["serial_number"];
		$price = $purchaserow["price"];
		$subtotal = $purchaserow["subtotal"];
		$item_detail_data = mysql_fetch_assoc(mysql_query("SELECT * FROM tbl_items_detail WHERE item_detail_id='$item_detail_id'"));
		$itemID = $item_detail_data["itemID"];
		$itemquery = mysql_query("SELECT * FROM tbl_items WHERE itemID='".$purchaserow["itemID"]."'");
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
			<td style='text-align: center;'>$serial_number</td>
			<td style='text-align: center;'>$quantity</td>
			<td align='right' colspan='2'>₱".number_format($price,2)."</td>

			<td align='right'><b>₱".number_format($subtotal,2)."</b></td>
		</tr>
		";
	}

		if($i<15){
	$cnt = 15 - $i;
	for($i=0;$i<$cnt;$i++){
	echo "
	<tr>
	<td style='padding-top:0px;padding-bottom:0px' colspan='4'>
	<div class='table-row-pox'>
	</div>
	</td>
	<td>
	</td>
	<td style='padding-top:0px;padding-bottom:0px' align='right'>
	</td>
	<tr>
	";
	}
	}
	?>
	</tbody>
	<tfoot id='f'>
	<tr  style='text-align:right;'>
		<td colspan='5' align='right'><b>Total:</b></td>
		<td><b>₱<?php echo number_format($total,2); ?></b></td>
	</tr>
	<?php
		$total_payment = 0;
		$change = 0;
		$payment_query = mysql_query("SELECT * FROM tbl_payments WHERE orderID='$orderID'");
		if(mysql_num_rows($payment_query)!=0){
			$cash_involved = 0;
			while($payment_row=mysql_fetch_assoc($payment_query)){
				$type_payment_db = $payment_row["type_payment"];
				$payment = $payment_row["payment"];
				$comments = $payment_row["comments"];
				$total_payment+=$payment;
				//<input style='width:100%' type='text' id='$type_payment_db' class='comments' title='$type_payment_db' style='width:7em;' value='$comments'>	
				echo "
					<tr style='text-align:right;'>";
						echo '<td colspan="4" align="right" style="border:0px"><input style="width:100%" type="text" id="'.$type_payment_db.'" class="comments" title="'.$type_payment_db.'" style="width:7em;" value="'.$comments.'" placeholder="Reference Number, Bank Number, Bank Name Check Number, Invoice Number"></td>';
						echo "
						<td style='border:0px'><b>$type_payment_db </b></td>
						<td style='border:0px'><b><input type='number' min='0' id='$type_payment_db' class='payment' title='$type_payment_db' style='width:7em' value='$payment'><b></td>
					</tr>
				";
			}
		}
		echo "
		<tr style='text-align:right;'>
			<td colspan='5' align='right' style='border:0px'><b>Total Payment:</b></td>
			<td style='border:0px'><b>₱ <span id='total_payment'>".number_format($total_payment,2)."<b></td>
		</tr>
		";
		$change = $total_payment - $total;
		if($change<0){
			$change=0;
		}
		
		echo "
		<tr style='text-align:right;'>
			<td colspan='5' align='right' style='border:0px'><b>Change Due:</b></td>
			<td style='border:0px'><b>₱<span id='change'>".number_format($change,2)."<b></td>
		</tr>
		";

	?>

	<tr style='text-align:right;'>
		<td colspan='5' align='right' style='border:0px'><b>Balance:</b></td>
		<td style='border:0px'><b>₱<span id='balance'><?php echo number_format($balance,2); ?><b></td>
	</tr>
	</tfoot>
	</table>
	<hr>
	<center>
	Thank You For Your Business!
	</center>
	
	<?php
	
	echo "
		<div class='prints col-md-12'>
		<div class='col-md-6'>
		<button onclick='myFunction()' class='btn btn-primary btn-block'><span class='glyphicon glyphicon-print'></span> Print</button>
		</div>
		
		<div class='col-md-6'>
		<button class='btn btn-danger btn-block'  data-toggle = 'modal' data-target = '#myModal'><span class='glyphicon glyphicon-trash'></span> Delete Sales</button>
		</div>
		
		</div>
		<script>
function myFunction() {
    window.print();
}
</script>
		
		";
	
	
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