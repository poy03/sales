<?php
ob_start();
session_start();
$accountID=@$_SESSION['accountID'];
$page=@$_GET['page'];
$f=@$_GET['f'];
$t=@$_GET['t'];
$keyword=@$_GET['keyword'];
$search=@$_GET['search'];
			$by=@$_GET['by'];
			$order=@$_GET['order'];

#$connect = mysql_connect("localhost","qfcdavao_admin","_39a11nwpm");
#mysql_select_db("qfcdavao_dbinventory");

include 'db.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title><?php echo $app_name; ?> - Sales Reports</title>
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
  <link rel="stylesheet" href="/resources/demos/style.css">
  

  <style>
  .item:hover{
	  cursor:pointer;
  }
  
  </style>
  <script>
		$(document).ready(function(){
			$("#date_from").datepicker();
			$("#date_to").datepicker();
			$("#by").change(function(){
				var date_from = $("#date_from").val();
				var date_to = $("#date_to").val();
				var by = $(this).val();
				window.location= "reports?by="+by+"&f="+date_from+"&t="+date_to;
			});
			
		$( "#search" ).autocomplete({
      source: 'search-item-all',
	  select: function(event, ui){
		  window.location='item?s='+ui.item.data;
	  }
    });
		

  
  });
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
	.page{
		display:inline !important;
	}
	*{
		line-height: 1 !important;
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
  	<div class='col-md-12'>
	
	
	<?php
	if($logged==1||$logged==2){
	if($reports=='1'){
		echo "<center><h3>Sales in <br>".date("F d, Y",strtotime($f))." - ".date("F d, Y",strtotime($t))."</h3></center>";
	?>
	<div class='table-responsive'>
		<button class="btn btn-primary prints" onclick="window.print()"><span class="glyphicon glyphicon-print"></span> Print</button>
		<table class='table table-hover'>
		<thead>
			<tr>
				<th>Sale ID</th>
				<th>Time</th>
				<th>Customer</th>
				<th>Payment Type</th>
				<th></th>
				<th style='text-align:right'>Cost Price of Items</th>
				<th style='text-align:right'>Sold Price of Items</th>
				<th style='text-align:right'>Balance</th>
				<th style='text-align:right'>Change</th>
				<!--
				<th style='text-align:right'>Payment</th>
				<th style='text-align:right'>Balance</th>
				<th style='text-align:right'>Loss</th>
				<th style='text-align:right'>Gain</th>
				-->
			</tr>
		</thead>
		<tbody>
		<?php
		if(!isset($page)){$page=1;}elseif($page<=0){$page=1;}
		$maxitem = $maximum_items_displayed; // maximum items
		$limit = ($page*$maxitem)-$maxitem;
		
		
		$query = "SELECT * FROM tbl_orders WHERE date_ordered BETWEEN '".strtotime($f)."' AND '".strtotime($t)."' AND deleted='0'";
		$numitemquery = mysql_query($query);
		$numitem = mysql_num_rows($numitemquery);
		// $query.=" LIMIT $limit, $maxitem";
		// echo "$query";
		$salesquery = mysql_query($query);
		
			if(($numitem%$maxitem)==0){
				$lastpage=($numitem/$maxitem);
			}else{
				$lastpage=($numitem/$maxitem)-(($numitem%$maxitem)/$maxitem)+1;
			}
			$maxpage = 3;
		
		if(mysql_num_rows($salesquery)!=0){
			$total_change = 0;
			$total_cash_change=0;
			while($row=mysql_fetch_assoc($salesquery)){
				$orderID=$row["orderID"];
				$costprice=$row["costprice"];
				$time_ordered=$row["time_ordered"];
				$comments=$row["comments"];
				$dbaccountID=$row["accountID"];
				$total=$row["total"];
				$balance=$row["balance"];
				
				// payments
				


				$type_payment_query = mysql_query("SELECT * FROM tbl_payments WHERE orderID='$orderID' AND deleted='0'");
				$change_query = mysql_query("SELECT DISTINCT cash_change FROM tbl_payments WHERE orderID='$orderID' AND deleted='0'");

				if(mysql_num_rows($change_query)!=0){
					while($change_row=mysql_fetch_assoc($change_query)){
						$cash_change = $change_row["cash_change"];
						$total_cash_change+=$cash_change;
					}
				}
				$dbtype_payment=array();
				$note=array();
				while($type_payment_row=mysql_fetch_assoc($type_payment_query)){
					$dbtype_payment[]=$type_payment_row["type_payment"]." <b>(₱".number_format($type_payment_row["payment"],2).")</b></b>";
					$note[]=$type_payment_row["comments"];
				}

				$note=implode("<br>",$note);
				$dbtype_payment=implode("<br>",$dbtype_payment);

				$customer=$row["customer"];
				$payment=$row["payment"];
				$profits=$row["profits"];
				$loss=$row["loss"];
				$balance=$row["balance"];
				
				if(preg_match("/\bcash\b/i", $dbtype_payment)) {
					$change = $payment-$total;
					if($change<=0){
						$change=0;
					}
				}else{
					$change = 0;
				}
				
				$total_change+=$change;
				
				$cash_on_hand = 0;
				$total_cash = 0;
				
				$cash_on_hand_query=mysql_query("SELECT SUM(payment) as total_cash FROM tbl_payments WHERE type_payment = 'cash' AND deleted='0' AND date BETWEEN '".strtotime($f)."' AND '".strtotime($t)."'");
				if(mysql_num_rows($cash_on_hand_query)!=0){
					while($cash_on_hand_row=mysql_fetch_assoc($cash_on_hand_query)){
						$total_cash = $cash_on_hand_row["total_cash"];
					}
				}
				
				
				// <td align='right'>₱".number_format($balance,2)."</td>
				// <td align='right'>₱".number_format($loss,2)."</td>
				// <td align='right'>₱".number_format($profits,2)."</td>
				
				echo "
				<tr title='$comments'>
					<td><a href='sales-re?id=$orderID' target='_blank'>S".sprintf("%06d", $orderID)."</a></td>
					<td>$time_ordered</td>
					<td>$customer</td>
					<td>$dbtype_payment</td>
					<td>$note</td>
					<td align='right'>₱".number_format($costprice,2)."</td>
					<td align='right'>₱".number_format($total,2)."</td>
					<td align='right'>₱".number_format($balance,2)."</td>
					<td align='right'>₱".number_format($cash_change,2)."</td>
				</tr>
				";
			}
			//echo "change $total_change";
			
		}
		
		?>
		</tbody>
		<tfoot>
		
		
		
			<?php
			if(mysql_num_rows($salesquery)!=0){
				$incomequery = mysql_query("SELECT SUM(costprice) as total_cost_price, SUM(profits) as total_profits, SUM(loss) as total_loss,SUM(total) as total_purchase, SUM(payment) as total_payment, SUM(balance) as total_balance FROM tbl_orders WHERE date_ordered BETWEEN '".strtotime($f)."' AND '".strtotime($t)."' AND deleted = '0'");
				while($incomerow=mysql_fetch_assoc($incomequery)){
					$total_cost_price = $incomerow["total_cost_price"];
					$total_profits = $incomerow["total_profits"];
					$total_loss = $incomerow["total_loss"];
					$total_balance = $incomerow["total_balance"];
					$total_purchase = $incomerow["total_purchase"];
					$total_payment = $incomerow["total_payment"];
				}

				$gain = $total_profits-$total_loss;
				if($gain<0){$gain=0;}
				$loss = $total_loss-$total_profits;
				if($loss<0){$loss=0;}			
				
				echo "

				<tr>
					<th style='text-align:center;border-bottom: 1px solid black;border-top: 1px solid black;' colspan='20'id='sales'>Sales Summary</th>
				</tr>
				<tr>
					<th style='text-align:right;border:0px' colspan='8'>Total Change:</th>
					<th style='text-align:right;border:0px;border-bottom-width:1px;border-bottom-style:solid'>₱".number_format($total_cash_change,2)."</th>
				</tr>	
				<tr>
					<th style='text-align:right;border:0px' colspan='8'>Total Amount of Cost of Items:</th>
					<th style='text-align:right;border:0px'>₱".number_format($total_cost_price,2)."</th>
				</tr>				
				<tr>
					<th style='text-align:right;border:0px;' colspan='8'>Total Amount of Sold Items:</th>
					<th style='text-align:right;border:0px;border-bottom-width:1px;border-bottom-style:solid'>₱".number_format($total_purchase,2)."</th>
				</tr>				
			
				
				<tr>
					<th style='text-align:right;border:0px' colspan='8'>Sales Gain:</th>
					<th style='text-align:right;border:0px'>₱".number_format($gain,2)."</th>
				</tr>
				<tr>
					<th style='text-align:right;border:0px' colspan='8'>Sales Loss:</th>
					<th style='text-align:right;border:0px'>₱".number_format($loss,2)."</th>
				</tr>
				";
			}
			?>
		</tfoot>
		</table>

	</div>
	
	
	<?php
	
	}else{
		echo "<strong><center>You do not have the authority to access this module.</center></strong>";
	}
	}else{

		header("location:index");

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