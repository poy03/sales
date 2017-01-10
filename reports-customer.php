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
<!DOCTYPE html>
<html lang="en">
<head>
  <title><?php echo $app_name; ?> - Customers Report</title>
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

  <script>
		$(document).ready(function(){
		$("#date_from,#date_to,#date_cost,#date_sales,#date_from_1,#date_to_1,#date_customer").datepicker();
		//$("#date_to").datepicker();
			
			
	$( "#search" ).autocomplete({
      source: 'search-item-all',
	  select: function(event, ui){
		  window.location='item?s='+ui.item.data;
	  }
    });
  
  });
  </script>
    <style>
	  .item:hover{
	  cursor:pointer;
  }

	

	
	
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
  	<div class='col-md-12 prints'>
	
	
	<?php
	if($logged==1||$logged==2){
	if($reports=='1'){
		
		if(isset($_GET["submit"])){
			$f = $_GET["f"];
			$t = $_GET["t"];
			$customer = $_GET["customer"];
			$f_int = (int)date("Y",strtotime($f));
			$t_int = (int)date("Y",strtotime($t));
			echo "
			<h3 style='text-align:center'>Customer's Purchases in</h3><h3 style='text-align:center'>".date("F d, Y",strtotime($f))." - ".date("F d, Y",strtotime($t))."</h3> 
			Customer: <label>$customer</label>
			<table class='table table-hover'>
			<thead>
				<tr>
					<th>Sales ID</th>
					<th>Date</th>
					<th>Time</th>
					<th>Payment Type</th>
					<th></th>
					<th style='text-align:right'>Purchases</th>
				</tr>
			</thead>
			<tbody>
			
			";
			$all_total_purchase = 0;
			$contactperson = "";
			$total_purchase = 0;
			if(strtotime($f)<=strtotime($t)){
				do{
					if($customer!=''){
						$customer_query = mysql_query("SELECT * FROM tbl_customer WHERE companyname='$customer'");
						if(mysql_num_rows($customer_query)!=0){
							while($customer_row=mysql_fetch_assoc($customer_query)){
								$contactperson=$customer_row['contactperson'];
								$customerID=$customer_row['customerID'];
							}
							$order_query = mysql_query("SELECT * FROM tbl_orders WHERE customerID = '$customerID' AND date_ordered='$f' AND deleted='0'");
							if(mysql_num_rows($order_query)!=0){
								while($order_row=mysql_fetch_assoc($order_query)){
									$orderID= $order_row["orderID"];
									$time_ordered= $order_row["time_ordered"];
									$date_ordered= $order_row["date_ordered"];
									$total= $order_row["total"];
									$costprice= $order_row["costprice"];
									$total_payment= $order_row["payment"];
								}
								
								$payment_query = mysql_query("SELECT * FROM tbl_payments WHERE orderID='$orderID' AND deleted='0' AND date='$date_ordered'");
								$type_payment_db = array();
								$type_payment_db_comments = array();
								if(mysql_num_rows($payment_query)!=0){
									while($payment_row=mysql_fetch_assoc($payment_query)){
										$type_payment_db[]=$payment_row["type_payment"];
										$type_payment_db_comments[]=$payment_row["comments"];
										$payment=$payment_row["payment"];
									}
								}
								$type_payment_db = implode("<br>",$type_payment_db);
								$type_payment_db_comments = implode("<br>",$type_payment_db_comments);
								echo "
								<tr>
									<td><a href='sales-re?id=$orderID' target='_blank'>S".sprintf("%06d",$orderID)."</a></td>
									<td>$date_ordered</td>
									<td>$time_ordered</td>
									<td>$type_payment_db</td>
									<td>$type_payment_db_comments</td>
									<th style='text-align:right'>₱".number_format($total,2)."</th>
								</tr>
								";
								$total_purchase+=$total;
							}
														
						}else{
							$customer='';
						}

					}else{
						// $customer_query = mysql_query("SELECT * FROM tbl_customer");
						// while($customer_row=mysql_fetch_assoc($customer_query)){
							// $companyname=$customer_row["companyname"];
							// $total_query = mysql_query("SELECT SUM(total) as total_purchase FROM tbl_orders WHERE customer='$companyname' AND date_ordered='$f'");
							// if(mysql_num_rows($total_query)!=0){
								// while($row=mysql_fetch_assoc($total_query)){
									// $total_purchase = $row["total_purchase"];
								// }
								// echo "$total_purchase";
							// }
						// }
					}

					$f = date('m/d/Y', strtotime($f. ' + 1 days'));
				}while(strtotime($f)<=strtotime($t));
			}
			echo "
			</tbody>
			<tfoot>
				<tr>
					<th colspan='5' style='text-align:right'>Total:</th>
					<th style='text-align:right'>₱".number_format($total_purchase,2)."</th>
				</tr>
			</tfoot>
			</table>
			";
		}else{ 
		header("location:reports");
		}
	}else{
		echo "<strong><center>You do not have the authority to access this module.</center></strong>";
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
<?php mysql_close($connect);?>
  <script>
$("[data-toggle=popover]")
.popover({html:true})
</script>