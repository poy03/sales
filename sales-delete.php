<?php
ob_start();
session_start();
$accountID=@$_SESSION['accountID'];
$orderID=@$_GET['id'];
$session_orderID=@$_SESSION['orderID'];
$timezone  = 0; //(GMT -5:00) EST (U.S. & Canada) 
$datenow=gmdate("m/d/Y", time() + 3600*($timezone+date("I")));
$timenow=gmdate("h:i:s A", time() + 3600*($timezone+date("I")));

include 'db.php';
$comments=@$_GET['comments'];


$keyword=@$_GET['keyword'];
$search=@$_GET['search'];
			$by=@$_GET['by'];
			$order=@$_GET['order'];

#$connect = mysql_connect("localhost","qfcdavao_admin","_39a11nwpm");
#mysql_select_db("qfcdavao_dbinventory");



?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title><?php echo $app_name; ?> - Home</title>
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
		//item ajax search
		$("#search").keyup(function() 
		{ 
		var keyword = $(this).val();
		var dataString = 'search='+ keyword;
		if(keyword!='')
		{
			$.ajax({
			type: "POST",
			url: "search-item-all",
			data: dataString,
			cache: false,
			success: function(html)
			{
			$("#item_results").html(html).show();
			}
			});
		}else{
			$("#item_results").hide();
		}
		return false;    
		});
		
		$("#item_results").click(function(){
			$(".show").click(function(){
				var n = $(this).attr("href");
				var id = $(this).attr("my");
				$("#search").val(n);
				$("#item_results").hide();
				window.location='item?s='+id;
			});
			$(".cusclose").click(function(){
				$("#item_results").hide();
			});
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
  	<div class='col-md-12 prints'>
	
	
	<?php
	if($logged==1||$logged==2){
		
		if(isset($orderID)){
			$comments = "Deleted by: $employee_name ($datenow - $timenow)<br>Reason: $comments";
			$comments = mysql_real_escape_string($comments);
			mysql_query("UPDATE tbl_orders SET deleted='1',comments='$comments' WHERE orderID='$orderID'");
			mysql_query("UPDATE tbl_payments SET deleted='1' WHERE orderID='$orderID'");
			$update_query = mysql_query("SELECT * FROM tbl_purchases WHERE orderID='$orderID'");
			while($update_row=mysql_fetch_assoc($update_query)){
				$item_detail_id = $update_row["item_detail_id"];
				$itemID = $update_row["itemID"];
				$purchase_quantity = $update_row["quantity"];
				$serial_number = $update_row["serial_number"];
				
				$item_query = mysql_query("SELECT * FROM tbl_items_detail WHERE item_detail_id='$item_detail_id'");
				while($item_row=mysql_fetch_assoc($item_query)){
					$item_quantity = $item_row["quantity"];
				}
				
				$total_quantity = $item_quantity + $purchase_quantity;
				mysql_query("UPDATE tbl_items_detail SET quantity='$total_quantity' WHERE item_detail_id='$item_detail_id'");

				mysql_query("INSERT INTO tbl_items_history (type,item_detail_id,itemID,serial_number,description,date_time,quantity,referenceID,accountID) VALUES ('Sales Delete','$item_detail_id','$itemID','$serial_number','".mysql_escape_string($_GET['comments'])."','".strtotime(date("m/d/Y"))."','$purchase_quantity','$orderID','$accountID')");
				unset($_SESSION['orderID']);
				header("location:sales");
			}
		}
	?>

	
	<?php
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