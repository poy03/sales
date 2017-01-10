<?php
ob_start();
session_start();
unset($_SESSION['selectcredit']);
$accountID=@$_SESSION['accountID'];
$page=@$_GET['page'];
$tab=@$_GET['tab'];
$id=@$_GET['id'];
if(!isset($tab)){
	$tab=1;
}
$keyword=@$_GET['keyword'];
$search=@$_GET['search'];
$by=@$_GET['by'];
$order=@$_GET['order'];

include 'db.php';


?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title><?php echo $app_name; ?> - Credits</title>
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
  .popover{
    width:100%;   
}
  </style>
  <script>
		$(document).ready(function(){
	$( "#search" ).autocomplete({
      source: 'search-item-all',
	  select: function(event, ui){
		  window.location='item?s='+ui.item.data;
	  }
    });
	$( "#customer" ).autocomplete({
      source: 'search-customer-auto',
	  select: function(event, ui){
		  window.location='credits?tab=2&id='+ui.item.data;
	  }
    });
	
	$("#date_now").datepicker();
	$("#date_now").change(function(){
		var date_now = $(this).val();
		window.location = 'credits?d='+date_now;
	});
	
		 $('.selected').click(function(event) {
        if (event.target.type !== 'checkbox') {
            $(':checkbox', this).trigger('click');
        }
    });
	
  });
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
		  if($logged==0){
		  	header("location:index");
			
		  ?>
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
	<form action='credits?tab=2' method='post'>
  	<div class='col-md-12'>	
	<?php
	if($logged==1||$logged==2){
		if($credits=='1'){
			if(isset($id)){
				$credits_query = mysql_query("SELECT * FROM tbl_credits WHERE creditID='$id'");
				if(mysql_num_rows($credits_query)!=0){
					while($credits_row=mysql_fetch_assoc($credits_query)){
						$customerID=$credits_row["customerID"];
						$total_amount=$credits_row["amount"];
						$payment=$credits_row["payment"];
						$paymentID=$credits_row["paymentID"];
						$type_payment=$credits_row["type_payment"];
						$date=$credits_row["date"];
						$paymentID_array = explode(",",$paymentID);
					}
					foreach($paymentID_array as $paymentID){
						$payment_data=mysql_fetch_assoc(mysql_query("SELECT * FROM tbl_payments WHERE paymentID='$paymentID'"));
						$order_data=mysql_fetch_assoc(mysql_query("SELECT * FROM tbl_orders WHERE orderID='".$payment_data["orderID"]."'"));
						//echo $order_data["customer"]." ";
						$customer_array[] = $order_data["customer"];
					}
					$customer_array = array_unique($customer_array);
					$customer_query = mysql_query("SELECT * FROM tbl_customer WHERE customerID='$customerID'");
					while($customer_row=mysql_fetch_assoc($customer_query)){
						$companyname = $customer_row["companyname"];
					}
					echo "
					<center>
					<img src='$logo' align='middle' style='margin-bottom:0px' class='img-responsive' alt='LOGO'></img>
					$address<br>
					$contact_number<br>
					</center>
					<br>
					<table style='width:100%'>
					<tr>
						<td style='width:70%'></td>
						<td style='width:30%'>RECEIPT #: ".sprintf("%06d",$id)."</td>
					</tr>";
					foreach ($customer_array as $companyname) {
						echo "<tr>
						<td>To: <b>$companyname</b></td>
						<td>Date: <b>".date("F d, Y",strtotime($date))."</b></td>
					</tr>";
						# code...
					}
					echo "</table>
					<h4 style='text-align:center'>ACKNOWLEDGEMENT RECEIPT
					</h4>
					<table border='1' style='width:100%'>
					<tbody>
					<tr>
						<th style='width:10%;text-align:center'>DATE</th>
						<th style='width:20%;text-align:center'>REF NUMBER</th>
						<th style='width:50%;text-align:center'>PARTICULARS</th>
						<th style='width:20%;text-align:center'>AMOUNT</th>
					</tr>";
					foreach($paymentID_array as $paymentID){
						$payment_query = mysql_query("SELECT * FROM tbl_payments WHERE paymentID='$paymentID'");
						while($payment_row=mysql_fetch_assoc($payment_query)){
							$amount = $payment_row["payment"];
							$orderID = $payment_row["orderID"];
							$invoice = $payment_row["comments"];
							$order_query = mysql_query("SELECT * FROM tbl_orders WHERE orderID='$orderID'");
							while($order_row=mysql_fetch_assoc($order_query)){
								$date_ordered=$order_row["date_ordered"];
							}
						}
						echo "
						<tr>
							<td style='text-align:center;padding-left:5px;padding-right:5px'>$date_ordered</td>
							<td style='text-align:center;padding-left:5px;padding-right:5px'>S".sprintf("%06d",$orderID)."<br>$invoice</td>
							<td style='text-align:right'></td>
							<td style='text-align:right;padding-left:5px;padding-right:5px'>₱".number_format($amount,2)."</td>
						</tr>
						";
					}
					echo "
					</tbody>
					<tfoot>
					<tr>
						<th colspan='3' style='text-align:right'>TOTAL:&nbsp;</th>
						<th style='text-align:right;padding-left:5px;padding-right:5px''>₱".number_format($total_amount,2)."</th>
					</tr>
					<tr>
						<th colspan='3' style='text-align:right'>PAYMENTS:&nbsp;</th>
						<th style='text-align:right;padding-left:5px;padding-right:5px''>$type_payment ₱".number_format($payment,2)."</th>
					</tr>
					</tfoot>

					</table>
					Thank You Very Much!
					<br>
					<br>
					<table style='width:100%'>
					<tr>
						<td style='width:10%;text-align:center'></u></td>
						<td style='width:20%;text-align:center;border-bottom-width:1px;border-bottom-style:solid'>Jane C. Bitac</td>
						<td style='width:10%;text-align:center'></u></td>
						<td style='width:10%'></td>
						<td style='width:30%;text-align:center'>Received By:____________________</td>
					</tr>
					<tr>
						<td style='width:10%;text-align:center'></u></td>
						<td style='width:20%;text-align:center;'><b>Manager</b></td>
						<td style='width:10%;text-align:center'></u></td>
						<td style='width:10%'></td>
						<td style='width:30%;text-align:center'></td>
					</tr>
					</table>
					<br>
					<p>Thank your for your continued patronage. We truly wish to continually provide you with excellent service.</p>
					<p>For suggestions and concern, please send your email to qfcdavao_dealer1@yahoo.com</p>
					";
				}
			}else{
				
			}
			
		}else{
			echo "<strong><center>You do not have the authority to access this module.</center></strong>";
		}
	} ?>
	</div>
	</form>
  </div>
</div>
</body>
</html>
<?php mysql_close($connect);


?>
  <script>
$("[data-toggle=popover]")
.popover({html:true})
</script>