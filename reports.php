<?php
ob_start();
session_start();
$accountID=@$_SESSION['accountID'];
$page=@$_GET['page'];
$tab=@$_GET['tab'];
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
  <title><?php echo $app_name; ?> - Reports</title>
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
    <link rel="stylesheet" href="css/balloon.css">

  <script>
		$(document).ready(function(){
		$("#date_expense,#date_from,#date_to,#date_cost,#date_sales,#date_from_1,#date_to_1,#date_customer_f,#date_customer_f_1,#date_customer_t_1,#date_customer_t,#date_deleted_sales,#date_now").datepicker();
		$("#date_now").change(function(){
			var date_now = $(this).val();
			window.location = "reports?tab=6&d="+date_now;
		});
			
			
	$( "#search" ).autocomplete({
      source: 'search-item-all',
	  select: function(event, ui){
		  window.location='item?s='+ui.item.data;
	  }
    });
		
		
    $( "#customer,#customer_01" ).autocomplete({
      source: 'search-new-customer'
    });

		
	
		$( "#serial_number-search" ).autocomplete({
	      source: 'search-item-serial-number-all',
		  select: function(event, ui){
			  $("#serial_number").val(ui.item.data);
		  }
	    });

		$( "#item_name-search" ).autocomplete({
	      source: 'search-item-history',
		  select: function(event, ui){
			  $("#item_name").val(ui.item.data);
		  }
	    });
		
		$( "#category-search" ).autocomplete({
	      source: 'search-item-category-history',
		  select: function(event, ui){
			  $("#category").val(ui.item.data);
		  }
	    });
		
		$( "#reference_number" ).autocomplete({
	      source: 'search-item-history-ref-number'
	    });

	    $("#item-history-form").submit(function(e) {
	    	e.preventDefault();
	    	show_history();
			  $("#serial_number-search").attr("readonly","readonly");

			  $("#item_name-search").attr("readonly","readonly");

			  $("#category-search").attr("readonly","readonly");


	    });
	  

	    $("#history-clear").click(function(e){
	    	$("#serial_number-search").removeAttr("readonly");
	    	$("#serial_number-search").val("");

	    	$("#item_name-search").removeAttr("readonly");
	    	$("#item_name-search").val("");

	    	$("#category-search").removeAttr("readonly");
	    	$("#category-search").val("");



			  $("#serial_number").val("");

			  $("#item_name").val("");

			  $("#category").val("");


	    	show_history();	

	    });

		show_history();	
  
  });

		$(document).on("click",".paging",function(e) {
			show_history(e.target.id);
		});



		function show_history(page=1) {
			// alert($("#item-history-form :input").serialize());
			$.ajax({
				type: "POST",
				url: $("#item-history-form").attr("action"),
				data: $("#item-history-form :input").serialize()+"&page="+page,
				cache: false,
				success: function(data){
					$("#item-history-table tbody").html(data);
				}
			});

		}

  </script>
    <style>
	  .item:hover{
	  cursor:pointer;
  }

	
	
		#item_results,#cus_results
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
	
	@media screen{


.hide{
		display: none;
	}

	}
@media print{
	.page{
		display:inline !important;


	}

	*{
		line-height: 11px !important;
	}
	.hide{
		display:inline-block !important;
	  vertical-align: middle;	
	}
  .prints{
	  display:none;
	  overflow-y:hidden;
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
						<?php if($type=='admin'){?><?php } ?>
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
		
		if(isset($_GET["submit"])){
			$f = $_GET["f"];
			$t = $_GET["t"];
			$f_int = (int)date("Y",strtotime($f));
			$t_int = (int)date("Y",strtotime($t));
			//by date
			echo '<button onclick="window.print()" class="btn btn-primary prints"><span class="glyphicon glyphicon-print"></span>&nbsp;&nbsp; Print </button>';
						echo "
						<center><h2>All Reports</h2></center>
						<center><h3>".date("F d, Y",strtotime($f))." - ".date("F d, Y",strtotime($t))."</h3></center>
						<div class='table-responsive'>
						<table class='table table-hover'>
						<thead>
							<tr>
							  <th style='border-right-style:solid;border-right-width:5px;'>Date</th>
							  <th style='text-align:right;'>Expenses</th>
							  <th style='text-align:right;'>Purchases</th>
							  <th style='text-align:right;border-right-style:solid;border-right-width:5px;'>Sales</th>
							  <th style='text-align:right;border-right-style:solid;border-right-width:5px;'>Payments Received</th>
							  <th style='text-align:right;'>Items Cost</th>
							  <!--
							  <th style='text-align:right;'>Sales Loss</th>
							  <th style='text-align:right;'>Sales Gain</th>
							  -->
							  <th style='text-align:right;'>Balance</th>
							  <th style='text-align:right;'>Deleted Sales</th>
							</tr>
						</thead>
						<tbody>
						";



						$str_t = strtotime($t);
						$str_f = strtotime($f);
						$dates_for_sales_query = mysql_query("SELECT * FROM tbl_orders WHERE date_ordered BETWEEN '$str_f' AND '$str_t'");
						$dates_for_expenses_query = mysql_query("SELECT * FROM tbl_orders_expenses WHERE date_of_expense BETWEEN '$str_f' AND '$str_t'");
						$dates_for_receiving_query = mysql_query("SELECT * FROM tbl_orders_receiving WHERE date_received BETWEEN '$str_f' AND '$str_t'");
						$dates_for_payment_query = mysql_query("SELECT * FROM tbl_payments WHERE date BETWEEN '$str_f' AND '$str_f'");

						$dates_for_reports = array();
						while($dates_for_sales_row = mysql_fetch_assoc($dates_for_sales_query)){
							//if there is a transaction then add the dates to list of dates to display
							$dates_for_reports[] = $dates_for_sales_row["date_ordered"];
						}
						
						while($dates_for_expenses_row = mysql_fetch_assoc($dates_for_expenses_query)){
							//if there is a transaction then add the dates to list of dates to display
							$dates_for_reports[] = $dates_for_expenses_row["date_of_expense"];
						}

						while($dates_for_receiving_row = mysql_fetch_assoc($dates_for_receiving_query)){
							//if there is a transaction then add the dates to list of dates to display
							$dates_for_reports[] = $dates_for_receiving_row["date_received"];
						}
						while($dates_for_payment_row = mysql_fetch_assoc($dates_for_payment_query)){
							//if there is a transaction then add the dates to list of dates to display
							$dates_for_reports[] = $dates_for_payment_row["date"];
						}

						$dates_for_reports = array_unique($dates_for_reports);
						sort($dates_for_reports);

						// var_dump($dates_for_reports);

						foreach ($dates_for_reports as $dates_for_reports_each_day) {

							//sum
							$total_expenses = mysql_fetch_assoc(mysql_query("SELECT SUM(expenses) as total_expenses FROM tbl_orders_expenses WHERE date_of_expense = '$dates_for_reports_each_day' AND deleted='0'"));
							$total_purchases = mysql_fetch_assoc(mysql_query("SELECT SUM(total_cost) as total_purchases FROM tbl_orders_receiving WHERE date_received = '$dates_for_reports_each_day' AND deleted='0'"));
							$total_sales = mysql_fetch_assoc(mysql_query("SELECT SUM(total) as total_sales FROM tbl_orders WHERE date_ordered = '$dates_for_reports_each_day' AND deleted='0'"));
							$total_payments = mysql_fetch_assoc(mysql_query("SELECT SUM(payment) as total_payments FROM tbl_payments WHERE date = '$dates_for_reports_each_day' AND deleted='0'"));
							$total_item_cost = mysql_fetch_assoc(mysql_query("SELECT SUM(costprice) as total_item_cost FROM tbl_orders WHERE date_ordered = '$dates_for_reports_each_day' AND deleted='0'"));
							$total_balance = mysql_fetch_assoc(mysql_query("SELECT SUM(balance) as total_balance FROM tbl_orders WHERE date_ordered = '$dates_for_reports_each_day' AND deleted='0'"));
							$total_deleted_sales = mysql_num_rows(mysql_query("SELECT * FROM tbl_orders WHERE date_ordered = '$dates_for_reports_each_day' AND deleted='1'"));

							echo "
							<tr>
								<td style='border-right-style:solid;border-right-width:5px;'>".date("m/d/Y",$dates_for_reports_each_day)."</td>
								<td style='text-align:right'>".number_format($total_expenses["total_expenses"],2)."</td>
								<td style='text-align:right'>".number_format($total_purchases["total_purchases"],2)."</td>
								<td style='text-align:right;border-right-style:solid;border-right-width:5px;''>".number_format($total_sales["total_sales"],2)."</td>
								<td style='text-align:right;border-right-style:solid;border-right-width:5px;''>".number_format($total_payments["total_payments"],2)."</td>
								<td style='text-align:right'>".number_format($total_item_cost["total_item_cost"],2)."</td>
								<td style='text-align:right'>".number_format($total_balance["total_balance"],2)."</td>
								<td style='text-align:right'>".$total_deleted_sales."</td>
							</tr>
							";
							# code...
						}
						//sum of all from selected dates by the user.
						$total_expenses = mysql_fetch_assoc(mysql_query("SELECT SUM(expenses) as total_expenses FROM tbl_orders_expenses WHERE date_of_expense BETWEEN '$str_f' AND '$str_t' AND deleted='0'"));
						$total_purchases = mysql_fetch_assoc(mysql_query("SELECT SUM(total_cost) as total_purchases FROM tbl_orders_receiving WHERE date_received BETWEEN '$str_f' AND '$str_t' AND deleted='0'"));
						$total_sales = mysql_fetch_assoc(mysql_query("SELECT SUM(total) as total_sales FROM tbl_orders WHERE date_ordered BETWEEN '$str_f' AND '$str_t' AND deleted='0'"));
						$total_payments = mysql_fetch_assoc(mysql_query("SELECT SUM(payment) as total_payments FROM tbl_payments WHERE date BETWEEN '$str_f' AND '$str_t' AND deleted='0'"));
						$total_item_cost = mysql_fetch_assoc(mysql_query("SELECT SUM(costprice) as total_item_cost FROM tbl_orders WHERE date_ordered BETWEEN '$str_f' AND '$str_t' AND deleted='0'"));
						$total_balance = mysql_fetch_assoc(mysql_query("SELECT SUM(balance) as total_balance FROM tbl_orders WHERE date_ordered BETWEEN '$str_f' AND '$str_t' AND deleted='0'"));

						//to avoid glitches from getting data from the database
						($total_item_cost==NULL?$total_item_cost=0:false);


						$sales_gain = $total_sales["total_sales"]-$total_item_cost["total_item_cost"];
						$sales_loss = $total_item_cost["total_item_cost"]-$total_sales["total_sales"];

						//to avoid negative figures
						($sales_gain<0?$sales_gain=0:false);
						($sales_loss<0?$sales_loss=0:false);


						$loss = $total_purchases["total_purchases"]+$total_expenses["total_expenses"]-$total_sales["total_sales"];
						$gain = $total_sales["total_sales"]-$total_purchases["total_purchases"]-$total_expenses["total_expenses"];

						//to avoid negative figures
						($loss<0?$loss=0:false);
						($gain<0?$gain=0:false);


						echo "
						</tbody>
						<tfoot>
							<tr>
								<th style='text-align:center;border-bottom: 1px solid black;border-top: 1px solid black;' colspan='20'>Sales Summary</th>
							</tr>
							<tr>
								<th style='border:0px;text-align:right;' colspan='7'>Total of Items Cost:</th>
								<td style='border:0px;text-align:right;'>₱".number_format($total_item_cost["total_item_cost"],2)."</td>
							</tr>
							<tr>
								<th style='border:0px;text-align:right;' colspan='7'>Total Sales:</th>
								<td style='border:0px;text-align:right;border-bottom-width:1px;border-bottom-style:solid'>₱".number_format($total_sales["total_sales"],2)."</td>
							</tr>
							
							<tr>
								<th style='border:0px;text-align:right;' colspan='7'>Sales Gain:</th>
								<td style='border:0px;text-align:right;'>₱".number_format($sales_gain,2)."</td>
							</tr>					
							<tr>
								<th style='border:0px;text-align:right;' colspan='7'>Sales Loss:</th>
								<td style='border:0px;text-align:right;'>₱".number_format($sales_loss,2)."</td>
							</tr>
							
							<tr>
								<th style='text-align:center;border-bottom: 1px solid black;border-top: 1px solid black;' colspan='20'>Flow Summary</th>
							</tr>		
							<tr>
								<th style='border:0px;text-align:right;' colspan='7'>Total Expenses:</th>
								<td style='border:0px;text-align:right;'>₱".number_format($total_expenses["total_expenses"],2)."</td>
							</tr>
							<tr>
								<th style='border:0px;text-align:right;' colspan='7'>Total Purchases:</th>
								<td style='border:0px;text-align:right;border-bottom-width:1px;border-bottom-style:solid'>₱".number_format($total_purchases["total_purchases"],2)."</td>
							</tr>
							<tr>
								<th style='border:0px;text-align:right;' colspan='7'>Total Cost:</th>
								<td style='border:0px;text-align:right;'>₱".number_format($total_purchases["total_purchases"]+$total_expenses["total_expenses"],2)."</td>
							</tr>					
							<tr>
								<th style='border:0px;text-align:right;' colspan='7'>Total Sales:</th>
								<td style='border:0px;text-align:right;border-bottom-width:1px;border-bottom-style:solid'>₱".number_format($total_sales["total_sales"],2)."</td>
							</tr>						
							<tr>
								<th style='border:0px;text-align:right;' colspan='7'>Gain:</th>
								<td style='border:0px;text-align:right;'>₱".number_format($gain,2)."</td>
							</tr>
							<tr>
								<th style='border:0px;text-align:right;' colspan='7'>Loss:</th>
								<td style='border:0px;text-align:right;'>₱".number_format($loss,2)."</td>
							</tr>					
							<tr>
								<th style='text-align:center;border-bottom: 1px solid black;border-top: 1px solid black;' colspan='20'>Payments Summary</th>
							</tr>
						";

						$type_payment_array = explode(",", $type_payment);
						$total_of_payments_received = 0;
						foreach ($type_payment_array as $type_payment_each) {

							if(preg_match("/\bcash\b/i", $type_payment_each)){
								//total for all payments received per types
								$total_payments_per_type = mysql_fetch_assoc(mysql_query("SELECT SUM(payment) as total_payments_per_type FROM tbl_payments WHERE type_payment = '$type_payment_each' AND date BETWEEN '$str_f' AND '$str_t' AND deleted='0' AND credit_status='0'"));

								//query for change
								$total_cash_change = mysql_fetch_assoc(mysql_query("SELECT SUM(cash_change) as total_cash_change FROM tbl_payments WHERE type_payment = '$type_payment_each' AND date BETWEEN '$str_f' AND '$str_t' AND deleted='0'"));
								$total_payments_of_cash = ($total_payments_per_type["total_payments_per_type"]-$total_expenses["total_expenses"]-$total_cash_change["total_cash_change"]);
								echo "
								<tr>
									<td colspan='2'></td>
									<th>CASH</th>
									<th>- Expenses</th>
									<th>- Change</th>
									<th>Total CASH</th>
								</tr>
								<tr>
									<td colspan='2'></td>
									<td>₱".number_format($total_payments_per_type["total_payments_per_type"],2)."</td>
									<td>₱".number_format($total_expenses["total_expenses"],2)."</td>
									<td>₱".number_format($total_cash_change["total_cash_change"],2)."</td>
									<td>₱".number_format($total_payments_of_cash,2)."</td>
									<th>$type_payment_each</th>
									<td style='text-align:right;'>₱".number_format($total_payments_of_cash,2)."</td>
								</tr>
								";

								$total_of_payments_received += $total_payments_of_cash;

							}elseif (preg_match("/\bcredit\b/i", $type_payment_each)) {
								//total for all payments received per types
								$total_payments_per_type = mysql_fetch_assoc(mysql_query("SELECT SUM(payment) as total_payments_per_type FROM tbl_payments WHERE type_payment = '$type_payment_each' AND date BETWEEN '$str_f' AND '$str_t' AND deleted='0' AND credit_status='1'"));

								//total for all payments received per types
								$total_payments_from_credits = mysql_fetch_assoc(mysql_query("SELECT SUM(payment) as total_payments_from_credits FROM tbl_payments WHERE NOT type_payment = '$type_payment_each' AND date BETWEEN '$str_f' AND '$str_t' AND deleted='0' AND credit_status='1'"));
								echo "
								<tr>
									<td colspan='5'></td>
									<th>Credits</th>
									<td></td>
									<td></td>
								</tr>
								<tr>
									<td colspan='5'></td>
									<td>₱".number_format($total_payments_per_type["total_payments_per_type"],2)."</td>
									<th>Payments From Credits</th>
									<td style='text-align:right;'>₱".number_format($total_payments_from_credits["total_payments_from_credits"],2)."</td>
								</tr>
								";

								$total_of_payments_received += $total_payments_from_credits["total_payments_from_credits"];


								# code...
							}else{
								//total for all payments received per types
								$total_payments_per_type = mysql_fetch_assoc(mysql_query("SELECT SUM(payment) as total_payments_per_type FROM tbl_payments WHERE type_payment = '$type_payment_each' AND date BETWEEN '$str_f' AND '$str_t' AND deleted='0' AND credit_status='0'"));
								echo "
								<tr>
									<td colspan='6'></td>
									<th>$type_payment_each</th>
									<td style='text-align:right;'>₱".number_format($total_payments_per_type["total_payments_per_type"],2)."</td>
								</tr>
								";

								$total_of_payments_received += $total_payments_per_type["total_payments_per_type"];
							}

						}
						echo "
							<tr>
								<td colspan='6'></td>
								<th style='border-top-width:1px;border-top-color:black;border-top-style:solid;'>TOTAL:</th>
								<td style='border-top-width:1px;border-top-color:black;border-top-style:solid;text-align:right'>₱".number_format($total_of_payments_received,2)."</td>
							<tr>
						</tfoot>
						</table>
						</div>	
						";
					
			
		}else{ ?>
		

		<div class='col-md-2 prints'>
		
		<?php
			echo "<label>Navigation:</label><a href = '?tab=1' class = 'list-group-item"; if(isset($tab)&&$tab=='1'){echo " active"; } echo "'>Sales Reports</a>";
			echo "<a href = '?tab=2' class = 'list-group-item"; if(isset($tab)&&$tab=='2'){echo " active"; } echo "'>Deleted Sales</a>";
			echo "<a href = '?tab=10' class = 'list-group-item"; if(isset($tab)&&$tab=='10'){echo " active"; } echo "'>Per Items Sales</a>";
			echo "<a href = '?tab=11' class = 'list-group-item"; if(isset($tab)&&$tab=='11'){echo " active"; } echo "'>Per Category Sales</a>";
			echo "<a href = '?tab=3' class = 'list-group-item"; if(isset($tab)&&$tab=='3'){echo " active"; } echo "'>Receiving Reports</a>";
			echo "<a href = '?tab=4' class = 'list-group-item"; if(isset($tab)&&$tab=='4'){echo " active"; } echo "'>Expenses</a>";
			// echo "<a href = '?tab=5' class = 'list-group-item"; if(isset($tab)&&$tab=='5'){echo " active"; } echo "'>Customer&#39;s Purchases</a>";
			echo "<a href = '?tab=6' class = 'list-group-item"; if(isset($tab)&&$tab=='6'){echo " active"; } echo "'>Payments</a>";
			// echo "<a href = '?tab=7' class = 'list-group-item"; if(isset($tab)&&$tab=='7'){echo " active"; } echo "'>Credits</a>";
			// echo "<a href = '?tab=8' class = 'list-group-item"; if(isset($tab)&&$tab=='8'){echo " active"; } echo "'>Sales Search</a>";
			echo "<a href = '?tab=9' class = 'list-group-item"; if(isset($tab)&&$tab=='9'){echo " active"; } echo "'>All Reports</a>";
			echo "<a href = '?tab=12' class = 'list-group-item"; if(isset($tab)&&$tab=='12'){echo " active"; } echo "'>Items History</a>";

		?>
		
		</div>
		<?php
		if($tab=='1'){// Sales Report
			echo "
			<div class='col-md-6'>
			<form action='reports-sales' method='get' class='form-horizontal'>
			<h3 style='text-align:center;'>View Sales</h3>
			
			<label>Date From:</label>
			<input type='text' class='form-control' id='date_from' name='f' placeholder='Pick a Date' value='".date("m/d/Y")."' required='required'>
			<label>Date To:</label>
			<input type='text' class='form-control' id='date_to' name='t' placeholder='Pick a Date' value='".date("m/d/Y")."' required='required'>
			<br>
			<button class='btn btn-primary btn-block' type='submit' name='submit'><span class='glyphicon glyphicon-shopping-cart'></span> View Sales</button>
			</form>
			</div>
				";
		}elseif($tab=='2'){ // deleted sales
			if(!isset($page)){$page=1;}elseif($page<=0){$page=1;}
			$maxitem = $maximum_items_displayed; // maximum items
			$limit = ($page*$maxitem)-$maxitem;
			echo "
			<div class='col-md-10'>";
			echo '<button class="btn btn-primary prints" onclick="window.print()"><span class="glyphicon glyphicon-print"></span> Print</button>';
			echo "<div class='table-responsive'>
				<table class='table-hover table'>
					<thead>
						<tr>
							<th>Sales ID</th>
							<th>Time</th>
							<th>Customer</th>
							<th>Employee</th>
							<th>Reason for deleting</th>
						</tr>
					</thead>
					<tbody>";
					$query = "SELECT * FROM tbl_orders WHERE deleted!='0' ORDER BY orderID DESC";
					$numitemquery = mysql_query($query);
					$numitem = mysql_num_rows($numitemquery);
					$query.=" LIMIT $limit, $maxitem";
					// echo $query;

			 		if(($numitem%$maxitem)==0){
						$lastpage=($numitem/$maxitem);
					}else{
						$lastpage=($numitem/$maxitem)-(($numitem%$maxitem)/$maxitem)+1;
					}
					$maxpage = 3;

					$item_query = mysql_query($query);
					if(mysql_num_rows($item_query)!=0){
						while($item_row=mysql_fetch_assoc($item_query)){
							$orderID=$item_row["orderID"];
							$time_ordered=$item_row["time_ordered"];
							$dbaccountID=$item_row["accountID"];
							$customer=$item_row["customer"];
							$comments=$item_row["comments"];
							$account_query = mysql_query("SELECT * FROM tbl_users WHERE accountID='$dbaccountID'");
							while($account_row=mysql_fetch_assoc($account_query)){
								$dbemployee_name = $account_row["employee_name"];
							}
							echo "
							<tr>
								<td><a href='sales-re?id=$orderID'>S".sprintf("%06d",$orderID)."</a></td>
								<td>$time_ordered</td>
								<td>$customer</td>
								<td>$dbemployee_name</td>
								<td>$comments</td>
							</tr>
							";
						}
					}
					echo "</tbody>
				</table>";
				echo "<div class='text-center'><ul class='pagination prints'>
				
				";
				$url="?tab=$tab&";
				$cnt=0;
				if($page>1){
					$back=$page-1;
					echo "<li><a href = '".$url."page=1'>&laquo;&laquo;</a></li>";	
					echo "<li><a href = '".$url."page=$back'>&laquo;</a></li>";	
					for($i=($page-$maxpage);$i<$page;$i++){
						if($i>0){
							echo "<li><a href = '".$url."page=$i'>$i</a></li>";	
						}
						$cnt++;
						if($cnt==$maxpage){
							break;
						}
					}
				}
				
				$cnt=0;
				for($i=$page;$i<=$lastpage;$i++){
					$cnt++;
					if($i==$page){
						echo "<li class='active'><a href = '#'>$i</a></li>";	
					}else{
						echo "<li><a href = '".$url."page=$i'>$i</a></li>";	
					}
					if($cnt==$maxpage){
						break;
					}
				}
				
				$cnt=0;
				for($i=($page+$maxpage);$i<=$lastpage;$i++){
					$cnt++;
					echo "<li><a href = '".$url."page=$i'>$i</a></li>";	
					if($cnt==$maxpage){
						break;
					}
				}
				if($page!=$lastpage&&$numitem>0){
					$next=$page+1;
					echo "<li><a href = '".$url."page=$next'>&raquo;</a></li>";
					echo "<li><a href = '".$url."page=$lastpage'>&raquo;&raquo;</a></li>";
				}
				echo "</ul><span class='page' >Page $page</span></div>";
				
				echo "
			</div>
			</div>
			";
		}elseif($tab=='3'){ // receiving reports
			echo "
			<div class='col-md-6'>
			<form action='reports-receiving' method='get' class='form-horizontal'>
			<h3 style='text-align:center;'>View Receiving Cost</h3>
			
			<label>Date From:</label>
			<input type='text' class='form-control' id='date_from' name='f' placeholder='Pick a Date' value='".date("m/d/Y")."' required='required'>
			<label>Date To:</label>
			<input type='text' class='form-control' id='date_to' name='t' placeholder='Pick a Date' value='".date("m/d/Y")."' required='required'>
			<br>
			<button class='btn btn-primary btn-block' type='submit' name='submit'><span class='glyphicon glyphicon-download-alt'></span> View Receiving Cost</button>
			</form>
			</div>	

			";
		}elseif($tab=='4'){ // expenses reports
			echo "
			<div class='col-md-6'>
			<form action='reports-expenses' method='get' class='form-horizontal'>
			<h3 style='text-align:center;'>View Expenses</h3>		
			<label>From:</label>
			<input type='text' class='form-control' id='date_customer_f_1' name='f' placeholder='Pick a Date' value='".date("m/d/Y")."' required='required'>
			<label>To:</label>
			<input type='text' class='form-control' id='date_customer_t_1' name='t' placeholder='Pick a Date' value='".date("m/d/Y")."' required='required'>
			<input type='checkbox' name='all'>
			<label>View All Dates:</label>
			<br>
			<button class='btn btn-primary btn-block' type='submit' name='submit'><span class='glyphicon glyphicon-usd'></span> View Expenses</button>
			
			</form>	
			</div>
			";
		}elseif($tab=='5'){ // customers purchase
			echo "
			<!--
			<h3 style='text-align:center;'>Customer's Purchases</h3>
			<div class='col-md-5'>
			<form action='reports-customer' method='get' class='form-horizontal'>
			<label>Customer Name:</label>
			<input type='text' class='form-control' id='customer' name='customer' placeholder='Type for Company Name' autocomplete='off' required='required'>
			<label>From:</label>
			<input type='text' class='form-control' id='date_customer_f_1' name='f' placeholder='Pick a Date' value='".date("m/d/Y")."' required='required'>
			<label>To:</label>
			<input type='text' class='form-control' id='date_customer_t_1' name='t' placeholder='Pick a Date' value='".date("m/d/Y")."' required='required'>
			<br>
			<button class='btn btn-primary btn-block' type='submit' name='submit'><span class='glyphicon glyphicon-user'></span> View Customer's Purchases</button>
			</form>
			</div>
			";
			echo "
			<div class='col-md-5'>
			<form action='reports-customer-item' method='get' class='form-horizontal'>
			<label>Customer Name:</label>
			<select name='c' class='form-control'>";
			$customer_query = mysql_query("SELECT * FROM tbl_customer");
			if(mysql_num_rows($customer_query)!=0){
				while($customer_row=mysql_fetch_assoc($customer_query)){
					$customerID=$customer_row["customerID"];
					$companyname=$customer_row["companyname"];
					echo "
					<option value='$customerID'>$companyname</option>
					";
				}
			}
			echo "
			</select>
			";
			echo "
			<label>Item Name:</label>
			<select name='i' class='form-control'>";
			$item_query = mysql_query("SELECT * FROM tbl_items");
			if(mysql_num_rows($item_query)!=0){
				while($item_row=mysql_fetch_assoc($item_query)){
					$itemID = $item_row["itemID"];
					$itemname = $item_row["itemname"];
					echo "<option value='$itemID'>$itemname</option>";
				}
			}
			echo "
			</select>
			<label>From:</label>
			<input type='text' class='form-control' id='date_customer_f' name='f' placeholder='Pick a Date' value='".date("m/d/Y")."' required='required'>
			<label>To:</label>
			<input type='text' class='form-control' id='date_customer_t' name='t' placeholder='Pick a Date' value='".date("m/d/Y")."' required='required'>
			<br>
			<button class='btn btn-primary btn-block' type='submit' name='submit'><span class='glyphicon glyphicon-user'></span> View Customer's Purchases</button>
			</form>
			</div>
			-->
			";
			
		}elseif($tab=='6'){ // credits
			echo "
			<div class='col-md-6'>
			<form action='reports-payments' method='get' class='form-horizontal'>
			<h3 style='text-align:center;'>View Payments</h3>
			
			<label>Date:</label>
			<input type='text' class='form-control' id='date_sales' name='d' placeholder='Pick a Date' value='".date("m/d/Y")."' required='required'>
			<br>
			<button class='btn btn-primary btn-block' type='submit' name='submit'><span class='glyphicon glyphicon-shopping-cart'></span> View Sales</button>
			</form>
			</div>
				";
		}elseif($tab=='7'){ // detailed reports
		echo "
		<div class='table-responsive'>
		<table class='table table-hover'>
		<thead>
		<tr>
			<th>Sale ID</th>
			<th>Date</th>
			<th>Time</th>
			<th>Customer</th>
			<th>Date Due</th>
			<th>Amount</th>
			<th>Invoice #</th>
		</tr>
		</thead>
		";
		$credit_search_query = mysql_query("SELECT * FROM tbl_payments WHERE type_payment LIKE 'CREDIT' AND deleted='0'");
		if(mysql_num_rows($credit_search_query)!=0){
			while($credit_search_row=mysql_fetch_assoc($credit_search_query)){
				$orderID = $credit_search_row["orderID"];
				$invoice = $credit_search_row["comments"];
				$amount = $credit_search_row["payment"];
				$date_due = $credit_search_row["date_due"];
				$order_query=mysql_query("SELECT * FROM tbl_orders WHERE orderID='$orderID' AND deleted='0'");
				while($order_row=mysql_fetch_assoc($order_query)){
					$date_ordered = $order_row["date_ordered"];
					$time_ordered = $order_row["time_ordered"];
					$customer = $order_row["customer"];
					$customerID = $order_row["customerID"];
				}
					echo "
					<tr>
						<td><a href='sales-re?id=$orderID'>S".sprintf("%06d",$orderID)."</a></td>
						<td>$date_ordered</td>
						<td>$time_ordered</td>
						<td><a href='credits?tab=2&id=$customerID'>$customer</a></td>
						<td>".date("m/d/Y",$date_due)."</td>
						<td>$amount</td>
						<td>$invoice</td>
					</tr>
					";
			}
		}

		echo "
		</table>
		</div>
		";
		}elseif($tab==8){
			echo "
			<div class='col-md-10'>
			<h3 style='text-align:center' class='col-md-7'>Sales Search</h3>
					<form action='sales-re' method='get' class='form-horizontal'>
					
					<div class='col-md-7 input-group'>
					<label>Sale ID:</label>
					<span class='input-group-addon'>S</span>
					<input type='number' min='1' name='id' placeholder='Sale Number' class='form-control'><br>
					</div>
					<br>
					<button class='col-md-7 btn btn-primary'><span class='glyphicon glyphicon-search'></span> Search</button>
				</form>
			
			<br>
			<br>

			";
			$type_payment_array	 = explode(",",$type_payment);
			echo "
			<h3 style='text-align:center' class='col-md-7'>Search by Reference Number</h3>
			<form action='sales-search-adv' method='get' class='form-horizontal'>
			
			<div class='col-md-7'>
			
			<div class='form-group'>
			
			<label>Payment Type:</label>
			<select class='form-control' name='t'>";
			foreach($type_payment_array as $type_payment_each){
				echo "<option value='$type_payment_each'>$type_payment_each</option>";
			}
			echo "
			</select>
			<label>Reference Number:</label>
			<input type='text' name='v' placeholder='Reference Number' class='form-control'  style='text-align:center'>
			
			<div class='checkbox col-md-12'>
			  <label><input type='checkbox' name='d' value='1'>Inlude deleted Sales?</label>
			</div>
			<br>
			<br>
			<button class='btn btn-primary btn-block'><span class='glyphicon glyphicon-search'></span> Search</button>
			</div>			
			</div>


			
			</form>
			</div>
			</div>
			";
			
		}elseif($tab==9){
		echo "
			<div class='col-md-6'>
			<form action='reports' method='get' class='form-horizontal'>

		<h3 style='text-align:center;'>All Reports</h3>
		
		<input type='hidden' value='d' class='form-control' name='by'>
		
		<label>From:</label>
		<input type='text' class='form-control' id='date_from' name='f' placeholder='Pick a Date' value='".date("m/d/Y")."' required='required'>

		<label>To:</label>
		<input type='text' class='form-control' id='date_to' name='t' placeholder='Pick a Date' value='".date("m/d/Y")."' required='required'>
		<br>
		<button class='btn btn-primary btn-block' type='submit' name='submit'><span class='glyphicon glyphicon-stats'></span> Make Report</button>
		
		</form>
		</div>
			";
		}elseif ($tab==10) {
			
			echo "
			<div class='col-md-6'>
			<form action='reports-per-items' method='get' class='form-horizontal'>

			<h3 style='text-align:center;'>Per Items Sales</h3>
			
			<label>Select Item:</label>
			<select class='form-control' name='by' >";

				$items_query = mysql_query("SELECT * FROM tbl_items");
				if(mysql_num_rows($items_query)!=0){
					while($itemrow=mysql_fetch_assoc($items_query)){
						echo "
						<option value='".$itemrow["itemID"]."'>".$itemrow["itemname"]."</option>
						";
					}
				}
			echo "
			</select>
			
			<label>From:</label>
			<input type='text' class='form-control' id='date_from' name='f' placeholder='Pick a Date' value='".date("m/d/Y")."' required='required'>

			<label>To:</label>
			<input type='text' class='form-control' id='date_to' name='t' placeholder='Pick a Date' value='".date("m/d/Y")."' required='required'>
			<input type='checkbox' name='all'>
			<label>View All Dates</label>
			<br>
			<button class='btn btn-primary btn-block' type='submit' name='submit'><span class='glyphicon glyphicon-stats'></span> Make Report</button>
			
			</form>
			</div>
				";
			
		}elseif ($tab==11) {
			
			echo "
			<div class='col-md-6'>
			<form action='reports-per-category' method='get' class='form-horizontal'>

			<h3 style='text-align:center;'>Per Category Sales</h3>
			
			<label>Select Category:</label>
			<select class='form-control' name='by' >";

				$items_query = mysql_query("SELECT DISTINCT category FROM tbl_items WHERE deleted='0'");
				if(mysql_num_rows($items_query)!=0){
					while($itemrow=mysql_fetch_assoc($items_query)){
						echo '
						<option value="'.$itemrow["category"].'">'.$itemrow["category"].'</option>
						';
					}
				}
			echo "
			</select>
			
			<label>From:</label>
			<input type='text' class='form-control' id='date_from' name='f' placeholder='Pick a Date' value='".date("m/d/Y")."' required='required'>

			<label>To:</label>
			<input type='text' class='form-control' id='date_to' name='t' placeholder='Pick a Date' value='".date("m/d/Y")."' required='required'>
			<input type='checkbox' name='all'>
			<label>View All Dates</label>
			<br>
			<button class='btn btn-primary btn-block' type='submit' name='submit'><span class='glyphicon glyphicon-stats'></span> Make Report</button>
			
			</form>
			</div>
				";
			
		}elseif ($tab==12) {

			 ?>

				<div class="table-responsive">
					<form action="reports-item-history-ajax" method="post" id="item-history-form" class="form-inline">
					<div class="form-group prints">
						<label>Serial Number:</label><br>
						<input type="text" id="serial_number-search" class="form-control" placeholder="Serial Number">
					</div>
					<div class="form-group prints">
						<label>Item Name:</label><br>
						<input type="text" id="item_name-search" class="form-control" placeholder="Item Name">
					</div>

					<div class="form-group prints">
						<label>Category:</label><br>
						<input type="text" id="category-search" class="form-control" placeholder="Category">
					</div>
					<div class="form-group prints">
						<label>Reference #:</label><br>
						<input type="text" id="reference_number" name="reference_number" class="form-control" placeholder="Ref #">
					</div>
					<br>
					<br>
					<div class="form-group prints">
						<label>Date From:</label><br>
						<input type="text" id="date_from" name="date_from" placeholder="Date From" class="form-control">
					</div>

					<div class="form-group prints">
						<label>Date To:</label><br>
						<input type="text" id="date_to" name="date_to" placeholder="Date To" class="form-control">
					</div>

					<div class="form-group prints">
						<label>Type:</label><br>
						<select name="type" class="form-control">
							<option value="all">All</option>
							<option value="sales">Sales</option>
							<option value="stock in">Stock In</option>
							<option value="purchase">Purchase</option>
							<option value="sales delete">Deleted Sales</option>
							<option value="purchase delete">Deleted Purchases</option>
						</select>
					</div>
					<input type="hidden" id="serial_number" name="serial_number" placeholder="Serial Number">
					<input type="hidden" id="item_name" name="item_name" placeholder="Item Name">
					<input type="hidden" id="category" name="category" placeholder="Item Name">
					
					<div class="form-group prints">
						<label>&nbsp;</label><br>
					<button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-search"></span> Search</button>
					<a type="button" id='history-clear' class="btn btn-danger"><span class="glyphicon glyphicon-remove"></span> Clear</a>
					<a onclick="myFunction()" type="button" class="btn btn-primary" data-balloon="The Page Layout must be in landscape view." data-balloon-pos="down">

					<span class="glyphicon glyphicon-print"></span> Print</a>
					</div>
					</form>

					<h3 style="text-align: center;">
						Items History
					</h3>
					<table class="table table-responsive" id="item-history-table">
						<thead>
							<tr>
								<th>Date</th>
								<th>Type</th>
								<th>Category</th>
								<th>Item Name</th>
								<th>Serial Number</th>
								<th>Description</th>
								<th>Quantity</th>
								<th>Ref #</th>
								<th>ID</th>
								<th>User</th>
							</tr>
						</thead>
						<tbody>

						</tbody>
					</table>



				</div>
				<?php
				

		}
		?>

		</div>
		<?php }
	}else{
		echo "<strong><center>You do not have the authority to access this module.</center></strong>";
	}
	}else{
	?>
	header("location:index");
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



<script>
function myFunction() {
    window.print();
}
</script>
