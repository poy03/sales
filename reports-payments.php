<?php
ob_start();
session_start();
$accountID=@$_SESSION['accountID'];
$page=@$_GET['page'];
$d=@$_GET['d'];
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
  <script type="text/javascript" src="js/shortcut.js"></script>
  <link rel="stylesheet" href="themes/smoothness/jquery-ui.css">
   
  <script src="jquery-ui.js"></script>
  <link rel="stylesheet" href="/resources/demos/style.css">
    <link rel="stylesheet" href="css/balloon.css">
  

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
		echo "<center><h3>Payments in ".date("F d, Y",strtotime($d))."</h3></center><br>";
	?>
	<div class='table-responsive'>

	<button class="btn btn-primary prints" onclick="window.print()"><span class="glyphicon glyphicon-print"></span> Print</button>
		<table class='table table-hover'>
		<thead>
			<tr>
				<th style='text-align:center;border-bottom: 1px solid black;border-top: 1px solid black;' colspan='20'>Payments Summary</th>
			</tr>
		</thead>
		<tbody>
		<?php
			$type_payment_array = explode(",",$type_payment);
			$all_total_payments = 0;
			foreach ($type_payment_array as $type_payment_each) {
				$payment_sales = mysql_fetch_assoc(mysql_query("SELECT SUM(payment) as total_sales  FROM tbl_payments WHERE date = '".strtotime($d)."' AND deleted='0' and credit_status = '0' AND type_payment = '$type_payment_each'"));
				$payment_change = mysql_fetch_assoc(mysql_query("SELECT SUM(cash_change) as total_change  FROM tbl_payments WHERE date = '".strtotime($d)."' AND deleted='0' and credit_status = '0' AND type_payment = 'cash'"));
				$payment_credit = mysql_fetch_assoc(mysql_query("SELECT SUM(payment) as total_credit  FROM tbl_payments WHERE date = '".strtotime($d)."' AND deleted='0' and credit_status = '1' AND type_payment = '$type_payment_each'"));
				if($payment_credit['total_credit']!=""||$payment_sales['total_sales']!=""){
					if(!preg_match("/\bcash\b/i", $type_payment_each)){
						$total_change = $payment_change['total_change'];
						$total_sales = $payment_sales['total_sales'];
						$total_credit = $payment_credit['total_credit'];
						$total_payment = $total_credit+$total_sales;
						if(!preg_match("/\bcredit\b/i", $type_payment_each)){
						echo "
						<tr>
							<td style='text-align:right'>
							<span style='cursor:help' data-balloon='Total Payments Received From Credit' data-balloon-pos='down'>
							(  ₱".number_format($total_credit,2)."</span> + 
							<span style='cursor:help' data-balloon='Total Payments Received From Sales' data-balloon-pos='down'> ₱".number_format($total_sales,2)." )</span></td>
							<th style='text-align:right'>$type_payment_each:</th>
							<td style='text-align:right'> ₱".number_format($total_payment,2)."</td>
						</tr> 
						";
						}else{
						echo "
						<tr>
							<td style='text-align:right'></td>
							<th style='text-align:right'>$type_payment_each:</th>
							<td style='text-align:right'> ₱".number_format($total_payment,2)."</td>
						</tr> 
						";
						}
					}else{
						$total_change = $payment_change['total_change'];
						$total_sales = $payment_sales['total_sales'];
						$total_credit = $payment_credit['total_credit'];
						$total_payment = $total_credit+$total_sales-$total_change;
						echo "
						<tr>
							<td style='text-align:right'>
							<span style='cursor:help' data-balloon='Total Payments Received From Credit' data-balloon-pos='down'>(  ₱".number_format($total_credit,2)."</span> + 
							<span style='cursor:help' data-balloon='Total Payments Received From Sales' data-balloon-pos='down'> ₱".number_format($total_sales,2)." )</span>  - <span style='cursor:help' data-balloon='Total Change' data-balloon-pos='down'> ₱".number_format($total_change,2)."</span>
							</td>
							<th style='text-align:right'>$type_payment_each:</th>
							<td style='text-align:right'> ₱".number_format($total_payment,2)."</td>
						</tr>
						";
					}
					$all_total_payments += $total_payment;
				}
			}
		?>
		</tbody>
		<tfoot>
			<tr>
				<th colspan="20">
				</th>
			</tr>
		</tfoot>
		</table>
	</div>
	
	
	<?php
	
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