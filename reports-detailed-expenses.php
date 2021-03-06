<?php
ob_start();
session_start();
$accountID=@$_SESSION['accountID'];
$page=@$_GET['page'];
$t=@$_GET['t'];
$f=@$_GET['f'];
$keyword=@$_GET['keyword'];
$search=@$_GET['search'];
$by=@$_GET['by'];
$order=@$_GET['order'];



include 'db.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title><?php echo $app_name; ?> - Expenses Reports</title>
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
			echo $header->header($module,0,1);
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
						<a href = 'maintenance' class = 'list-group-item'><span class='glyphicon glyphicon-hdd'></span> Maintenance</a><a href = 'logout' class = 'list-group-item'><span class='glyphicon glyphicon-log-out'></span> Logout</a>
										  					  
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
		echo "<center><h3>Summary of Expenses in<br> ".date("F d, Y",strtotime($f))." - ".date("F d, Y",strtotime($t))."</h3></center>";
	?>
	<div class='table-responsive'>
	<table class='table table-responsive'>
	<thead>
		<tr>
			<th>Description</th>
			<th style='text-align:center'>Expenses</th>
		</tr>
	</thead>
	<tbody>
		<?php
		$list_of_description = array();
		$expenses_unique = mysql_query("SELECT DISTINCT comments FROM tbl_orders_expenses WHERE date_of_expense BETWEEN '".strtotime($f)."' AND '".strtotime($t)."' AND deleted='0' ORDER BY comments");
		if(mysql_num_rows($expenses_unique)!=0){
			while($expenses_unique_row = mysql_fetch_assoc($expenses_unique)){
				$list_of_description[] = $expenses_unique_row["comments"];
			}
		}

		foreach($list_of_description as $comments){
			$expenses_query = mysql_query("SELECT SUM(expenses) as total_expenses_of_description FROM tbl_orders_expenses WHERE date_of_expense BETWEEN '".strtotime($f)."' AND '".strtotime($t)."' AND deleted='0' AND comments='$comments'");
			while($expenses_row=mysql_fetch_assoc($expenses_query)){
				echo "
				<tr>
					<td>".$comments."</td>
					<td style='text-align:right'>₱".number_format($expenses_row["total_expenses_of_description"],2)."</td>
				</tr>
				";
			}
		}

/*		var_dump($description);
		exit;
		$expenses_query = mysql_query("SELECT * FROM tbl_expenses WHERE date_of_expense BETWEEN '".strtotime($f)."' AND '".strtotime($t)."' AND deleted='0'");
		$total_expenses = 0;
		if(mysql_num_rows($expenses_query)!=0){
			while($expenses_row=mysql_fetch_assoc($expenses_query)){
				$orderID=$expenses_row["orderID"];
				$date_of_expense=$expenses_row["date_of_expense"];
				$expenses=$expenses_row["expenses"];
				$description=$expenses_row["description"];
				$total_expenses+=$expenses;
				$order_data = mysql_fetch_assoc(mysql_query("SELECT * FROM tbl_orders_expenses WHERE orderID='$orderID'"));
				$comments = $order_data["comments"];
				echo "
				<tr>
					<td><a href='expenses-view?id=$orderID' target='_blank'>E".sprintf("%06d",$orderID)."</a></td>
					<td>".date("m/d/Y",$date_of_expense)."</td>
					<td>".$description."</td>
					<td style='text-align:right'>₱".number_format($expenses,2)."</td>
					<td style='text-align:center'>$comments</td>
				</tr>
				";
			}
		}*/
		?>
	</tbody>
	<tfoot>
	<?php
	if(mysql_num_rows($expenses_unique)!=0){
	$total_expenses = mysql_fetch_assoc(mysql_query("SELECT SUM(expenses) as total_expenses FROM tbl_orders_expenses WHERE date_of_expense BETWEEN '".strtotime($f)."' AND '".strtotime($t)."' AND deleted='0'"));
		echo "
		<tr>
			<th style='text-align:right'>Total Expenses:</th>
			<th style='text-align:right'>₱".number_format($total_expenses["total_expenses"],2)."</th>
		<tr>
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