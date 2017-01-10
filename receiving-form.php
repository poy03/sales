<?php
ob_start();
session_start();
$accountID=@$_SESSION['accountID'];
$page=@$_GET['page'];
$cat=@$_GET['cat'];
$id=@$_GET['id'];
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
  <title><?php echo $app_name; ?></title>
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
		  if(conf = confirm("Are you sure you want to delete selected item?")){
		  var id = $(this).attr("href");
		  window.location=id;
		  }		  
	  });
			
			
	$( "#search" ).autocomplete({
      source: 'search-item-all',
	  select: function(event, ui){
		  window.location='item?s='+ui.item.data;
	  }
    });
		
		
				//item ajax search
		$("#receiving").keyup(function() 
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
			$("#results").html(html).show();
			}
			});
		}else{
			$("#results").hide();
		}
		return false;    
		});
		
		$("#results").click(function(){
			$(".show").click(function(){
				var n = $(this).attr("href");
				var id = $(this).attr("my");
				$("#receiving").val(n);
				$("#results").hide();
				window.location='receiving-add?id='+id;
			});
			$(".cusclose").click(function(){
				$("#results").hide();
			});
		});
		
  
  });
  </script>
    <style>
		#item_results,#results
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
	#results{
		width:100%;
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
	if($receiving=='1'){
		#if($sales=='1'){
$deleteid = @$_GET["id"];
if(isset($deleteid)){
	mysql_query("DELETE FROM tbl_cart_receiving WHERE accountID='$accountID' AND cartID='$deleteid'");
		header("location:receiving");
}


if(isset($_POST["save"])){
	$itemIDarray = $_POST["itemID"];
	$quantity = $_POST["quantity"];
	$costprice = $_POST["costprice"];
	$comments = $_POST["comments"];
	$comments = mysql_real_escape_string($comments);
	$i=0;
		
	foreach($itemIDarray as $itemID){
		if($mode=='restock'){
			$subtotal[$i]=0;
		}
			$subtotal = $quantity[$i]*$costprice[$i];
			mysql_query("UPDATE tbl_cart_receiving SET quantity='$quantity[$i]', costprice = '$costprice[$i]', subtotal='$subtotal',comments='$comments' WHERE itemID = '$itemID' AND accountID='$accountID'");
			$i++;
	}
	header("location:receiving");
}


if(isset($_POST["delete"])){
	mysql_query("DELETE FROM tbl_cart_receiving WHERE accountID='$accountID'");
	header("location:receiving");
}
	
if(isset($_POST["savecontinue"])){
	$comments = $_POST["comments"];
	$purchase_order = $_POST["purchase_order"];
	$comments = mysql_real_escape_string($comments);
	$itemIDarray = $_POST["itemID"];
	$quantity = $_POST["quantity"];
	$costprice = $_POST["costprice"];
	$serial_number = $_POST["serial_number"];
	$receiving_costprice = $_POST["costprice"];
	$supplierID = $_POST["supplierID"];
	$supplier_name_post = $_POST["supplier_name_post"];
	$mode = $_POST["m"];
	$total_cost = array();
	$timezone  = 0; //(GMT -5:00) EST (U.S. & Canada) 
	$date_received=gmdate("m/d/Y", time() + 3600*($timezone+date("I")));
	$time_received=gmdate("h:i:s A", time() + 3600*($timezone+date("I")));
	$i=0;
	$overall= 0;



	$receiving_query = mysql_query("SELECT * FROM tbl_cart_receiving WHERE accountID='$accountID'");
	while($receiving_row=mysql_fetch_assoc($receiving_query)){
		$itemID = $receiving_row["itemID"];
		// echo $serial_number[$i];

		$item_data = mysql_fetch_assoc(mysql_query("SELECT * FROM tbl_items WHERE itemID='$itemID'"));
		if($mode=='restock'){
			$total_cost=0;
			$receiving_costprice[$i] = 0;
		}else{
			$total_cost[$i] = $quantity[$i] * $costprice[$i];
		}
		


		if($item_data["has_serial"]==1){
			mysql_query("INSERT INTO tbl_items_detail (itemID,serial_number,has_serial) VALUES ('$itemID','$serial_number[$i]','1')");
			mysql_query("UPDATE tbl_items SET costprice='$costprice[$i]' WHERE itemID='$itemID'");

			$new_item_data = mysql_fetch_array(mysql_query("SELECT * FROM tbl_items_detail WHERE itemID='$itemID' ORDER BY item_detail_id DESC LIMIT 1"));
			$item_detail_id = $new_item_data["item_detail_id"];
			mysql_query("INSERT INTO tbl_receiving VALUES ('','$item_detail_id','$quantity[$i]','$receiving_costprice[$i]','$total_cost[$i]','$accountID','0','".strtotime(date("m/d/Y"))."','$serial_number[$i]')");

		}else{
			mysql_query("UPDATE tbl_items SET costprice='$costprice[$i]' WHERE itemID='$itemID'");
			$new_item_detail_data = mysql_fetch_assoc(mysql_query("SELECT * FROM tbl_items_detail WHERE itemID='$itemID'")); 
			$item_detail_id = $new_item_detail_data["item_detail_id"];
			mysql_query("INSERT INTO tbl_receiving VALUES ('','$item_detail_id','$quantity[$i]','$receiving_costprice[$i]','$total_cost[$i]','$accountID','0','".strtotime($date_received)."','$serial_number[$i]')");
		}


		$searchquery = mysql_query("SELECT * FROM tbl_orders_receiving ORDER BY orderID DESC LIMIT 0,1");
		if(mysql_num_rows($searchquery)==0){
				$orderID = 1;
		}else{
			while($searchrow = mysql_fetch_assoc($searchquery)){
				$orderID = $searchrow["orderID"]+1;
			}
		}

		$description = 'ReceiveID: <a href="receiving-re?id='.$orderID.'">R'.sprintf("%06d",$orderID).'</a>';
		if($mode=='restock'){
			$receiving_type = "Stock In";
		}else{
			$receiving_type = "Purchase";
		}
		$description = mysql_real_escape_string($_POST["comments"]);
		// var_dump($description);
		echo("INSERT INTO tbl_items_history (item_detail_id,itemID,description,date_time,quantity,accountID,serial_number,type,referenceID,reference_number) VALUES ('$item_detail_id','$itemID','".$description."','".strtotime(date("m/d/Y"))."','$quantity[$i]','$accountID','$serial_number[$i]','$receiving_type','$orderID','$purchase_order')");
		exit;


		$overall = $overall + $total_cost[$i];
		$updatequery = mysql_query("SELECT * FROM tbl_items_detail WHERE item_detail_id = '$item_detail_id'");
		while($updaterow=mysql_fetch_assoc($updatequery)){
			$remain = $updaterow["quantity"];
		}
		$remain = $remain + $quantity[$i];
		mysql_query("UPDATE tbl_items_detail SET quantity='$remain' WHERE item_detail_id='$item_detail_id'");
		if($item_data["has_serial"]==1){
			mysql_query("UPDATE tbl_items SET costprice='$costprice[$i]' WHERE itemID='$itemID'");
		}else{
			mysql_query("UPDATE tbl_items SET costprice='$costprice[$i]' WHERE itemID='$itemID'");
		}

		$i++;

	}
	




		if($mode=='restock'){
			$overall=0;
		}
	mysql_query("INSERT INTO tbl_orders_receiving (total_cost,date_received,date_received_int,time_received,accountID,comments,supplierID,mode) VALUES ('$overall','$date_received','".strtotime($date_received)."','$time_received','$accountID','$comments','$supplierID','$mode')");
	$searchquery = mysql_query("SELECT * FROM tbl_orders_receiving ORDER BY orderID DESC LIMIT 0,1");
	while($searchrow = mysql_fetch_assoc($searchquery)){
		$orderID = $searchrow["orderID"];
	}
	mysql_query("UPDATE tbl_receiving SET orderID = '$orderID' WHERE accountID='$accountID' AND orderID ='0'");
	mysql_query("DELETE FROM tbl_cart_receiving WHERE accountID='$accountID'");
	header("location:receiving-re?id=".$orderID);
	
	?>

	
	
	<?php
	
}
	?>
	
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