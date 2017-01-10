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
  <title><?php echo $app_name; ?> - View Expenses</title>
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
  
  </style>
  <script>
	$(document).ready(function(){
		$(".delete").click(function(){
			if(confirm("Are you sure you want to delete selected item?")){
				window.location = $(this).attr("my");
			}
		});
		
	$( "#search" ).autocomplete({
      source: 'search-item-all',
	  select: function(event, ui){
		  window.location='item?s='+ui.item.data;
	  }
    });
  
	$("#confirm").click(function(e){
		var id = $("#id").val();
		var comments = $("#comments").val();
		if(comments!=""){
			var dataString = "delete=expenses&id="+id+"&comments="+comments;
			$.ajax({
				type: 'POST',
				data: dataString,
				url: 'delete',
				cache: false,
				success: function(html){
					location.reload();
				}
			});
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
		
		if($type=='admin'){
			$orderID = @$_GET["id"];
			if(isset($orderID)){
				$total_expenses = 0;
				$order_query = mysql_query("SELECT * FROM tbl_orders_expenses WHERE orderID='$orderID'");
				if(mysql_num_rows($order_query)!=0){
					while($order_row=mysql_fetch_assoc($order_query)){
						$date_of_expense = $order_row["date_of_expense"];
						$time_of_expense = $order_row["time_of_expense"];
						$comments = $order_row["comments"];
						$deleted = $order_row["deleted"];
						$delete_comments = $order_row["delete_comments"];
						$deleted_by = $order_row["deleted_by"];
						$expenses = $order_row["expenses"];
						$db_accountID = $order_row["accountID"];
					}
					$account_query = mysql_query("SELECT * FROM tbl_users WHERE accountID='$db_accountID'");
					while($account_row=mysql_fetch_assoc($account_query)){
						$db_employee_name = $account_row["employee_name"];
					}
					echo "
					Expenses ID: <b>E".sprintf("%06d",$orderID)."</b><br>
					Date Time: <b>".date("m/d/Y",$date_of_expense)." ".date("h:i:s A",$time_of_expense)."</b><br>
					By: <b>$db_employee_name</b><br>
					<div class='table-responsive'>
					<table class='table table-hover'>
					<thead>
						<tr>
							<th>Description</th>
							<th style='text-align:right'>Expenses</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>$comments</td>
							<td style='text-align:right'>₱".number_format($expenses,2)."</td>
						</tr>
					</tbody>
					</table>
					</div>
					";
						echo "
							<div class='prints col-md-12'>
							<div class='col-md-6'>
							<button onclick='window.print()' class='btn btn-primary btn-block'><span class='glyphicon glyphicon-print'></span> Print</button>
							</div>
							
					";
						if($deleted=='0'){
							echo "
							<div class='col-md-6'>
							<button class='btn btn-danger btn-block'  data-toggle = 'modal' data-target = '#myModal'><span class='glyphicon glyphicon-trash'></span> Delete</button>
							</div>
							";
						}else{
							$deleted_by = mysql_fetch_assoc(mysql_query("SELECT * FROM tbl_users WHERE accountID='$deleted_by'"));
							echo "
							<br>
							<br>
							<br>
							<b>DELETED BY:</b> ".$deleted_by["employee_name"]." <br>
							<b>Date Deleted:</b> ".date("m/d/Y",$deleted)." <br>
							<b>Comments:</b> $delete_comments <br>
							";
						}
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
					            
					            <button type = "button" class = "btn btn-danger" id='confirm'>
					               Confirm
					            </button>
					         </div>
					         </form>
					      </div><!-- /.modal-content -->
					   </div><!-- /.modal-dialog -->
					  
					</div><!-- /.modal -->
					<?php
				}else{
					header("location:index");
				}
			}
				


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