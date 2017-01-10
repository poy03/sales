<?php
ob_start();
session_start();
$accountID=@$_SESSION['accountID'];
$page=@$_GET['page'];
$cat=@$_GET['cat'];
$d=@$_GET['d'];
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

  <script>
		$(document).ready(function(){
		$("#date_expense,#date_from,#date_to,#date_cost,#date_sales,#date_from_1,#date_to_1,#date_customer_f,#date_customer_t,#date_deleted_sales").datepicker();
		//$("#date_to").datepicker();
			
			
	$( "#search" ).autocomplete({
      source: 'search-item-all',
	  select: function(event, ui){
		  window.location='item?s='+ui.item.data;
	  }
    });
		
		//item ajax search
		$("#customer").keyup(function() 
		{ 
		var keyword = $(this).val();
		var dataString = 'search='+ keyword;
		if(keyword!='')
		{
			$.ajax({
			type: "POST",
			url: "search-customer",
			data: dataString,
			cache: false,
			success: function(html)
			{
			$("#cus_results").html(html).show();
			}
			});
		}else{
			$("#cus_results").hide();
		}
		return false;    
		});
		
		$("#cus_results").click(function(){
			$(".show").click(function(){
				var n = $(this).attr("href");
				var id = $(this).attr("my");
				$("#customer").val(n);
				$("#cus_results").hide();
				//window.location='item?s='+id;
			});
			$(".cusclose").click(function(){
				$("#cus_results").hide();
			});
		});
		
		
  
  });
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
  	<div class='col-md-12 prints'>
	
	
	<?php
	if($logged==1||$logged==2){
	if($reports=='1'){
	echo "<h3 style='text-align:center'>Deleted Sales in ".date("F d, Y",strtotime($d))." </h3>";
	?>
	<div class='table-responsive'>
	<table class='table table-hover'>
	<thead>
	<tr>
		<th>Sales ID</th>
		<th>Time</th>
		<th>Customer</th>
		<th>Employee</th>
		<th>Reason for deleting</th>
	</tr>
	</thead>
	<tbody>
		<?php
		$item_query = mysql_query("SELECT * FROM tbl_orders WHERE date_ordered='$d' AND deleted='1'");
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
		?>
	</tbody>
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