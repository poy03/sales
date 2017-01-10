<?php
ob_start();
session_start();
$accountID=@$_SESSION['accountID'];
$page=@$_GET['page'];
$date_to=@$_GET['t'];
$date_from=@$_GET['f'];
$item=@$_GET['item'];
$cat=@$_GET['cat'];
$keyword=@$_GET['keyword'];
$customerID=@$_GET['id'];



include 'db.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title><?php echo $app_name; ?> - Customers</title>
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
  <style>
  .item:hover{
	  cursor:pointer;
  }
  </style>
    
  <link rel="stylesheet" href="css/theme.default.min.css">
  <script type="text/javascript" src="js/jquery.tablesorter.js"></script> 
  <link rel="stylesheet" href="themes/smoothness/jquery-ui.css">
   
  <script src="jquery-ui.js"></script>
  <script type="text/javascript" src="js/shortcut.js"></script>
  
  <script>
  $(document).ready(function(){
  	$("#date_to,#date_from").datepicker();
  	$("#date_to,#date_from").change(function(){
  		var date_from = $("#date_from").val();
  		var date_to = $("#date_to").val();
  		var id = $("#id").val();
  		var itemID = $("#item").val();
  		if(date_from!=""&&date_to!=""){
  			window.location = "?id=" +id+ "&f=" +date_from + "&t=" +date_to+"&item="+itemID;
  		}
  	});

  	$("#item").change(function(){
  		var date_from = $("#date_from").val();
  		var date_to = $("#date_to").val();
  		var id = $("#id").val();
  		var itemID = $("#item").val();
		window.location = "?id=" +id+ "&f=" +date_from + "&t=" +date_to+"&item="+itemID;
  	});

	$( "#search" ).autocomplete({
      source: 'search-item-all',
	  select: function(event, ui){
		  window.location='item?s='+ui.item.data;
	  }
    });
	
	$( "#search_customer" ).autocomplete({
      source: 'search-customer-auto',
	  select: function(event, ui){
		  window.location='customer?id='+ui.item.data;
	  }
    });
	
	$(".redeem").click(function(e){
		do{
			var points_to_spend = prompt("How many card points to spend?", "0");
			if(isNaN(points_to_spend)||points_to_spend<0){
				alert(points_to_spend+" is not a valid number. Try again.");
			}else{
				dataStr = 'points_to_spend='+points_to_spend+'&id='+e.target.id;
				$.ajax({
					type: 'POST',
					url: 'customer-redeem',
					data: dataStr,
					cache: false,
					success: function(html){
						if(html.indexOf("error")==2){
							alert("Error! Redeem Points must not be greater than its remaining Card Points.");
						}else{
							$("#card_points_of_"+e.target.id).html(html);
							alert("Success!");
						}
					}
				});
			}
		}while(isNaN(points_to_spend)||points_to_spend<0);
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

#item_results,#result
	{
		position:absolute;
		z-index:10;
		width:250px;
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
		  if($logged==0){ ?>
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
	if($customers=='1'){
		$customer_query = mysql_query("SELECT * FROM tbl_customer WHERE customerID='$customerID'");
		if(mysql_num_rows($customer_query)==0){
			header("location:success");
		}
		$customer_data = mysql_fetch_assoc($customer_query);
		echo "

		<input type='hidden' id='id' value='$customerID'>
		Company Name: <b>".$customer_data["companyname"]."</b><br>
		Address: <b>".$customer_data["address"]."</b><br>
		Email: <b>".$customer_data["email"]."</b><br>
		Contact Number: <b>".$customer_data["phone"]."</b><br>
		Contact Person: <b>".$customer_data["contactperson"]."</b><br>
		<div class='table-responsive'>
			<table class='table table-hover'>
				<thead>
					<tr>
						<th>Sales ID</th>
						<th>Date</th>
						<th>Item Name</th>
						<th>Serial Number</th>
						<th>Selling Price</th>
					</tr>
				</thead>
				<tbody>";
				if(!isset($page)){$page=1;}elseif($page<=0){$page=1;}
				$maxitem = $maximum_items_displayed; // maximum items
				$limit = ($page*$maxitem)-$maxitem;
				$query = "SELECT * FROM tbl_purchases WHERE customerID='$customerID' AND deleted='0'";
				if(isset($item)&&$item!=0){
					$query.=" AND itemID='$item'";
				}


				if((isset($date_from)&&isset($date_to))&&($date_from!=""&&$date_to!="")){
					$query.=" AND date_ordered_int BETWEEN '".strtotime($date_from)."' AND '".strtotime($date_to)."'";
				}

				if((isset($date_from)&&isset($date_to))&&($date_from!=""&&$date_to!="")){
					$query.= " ORDER BY date_ordered ASC";
				}else{
					$query.= " ORDER BY date_ordered DESC";
					
				}


				$numitemquery = mysql_query($query);
				$numitem = mysql_num_rows($numitemquery);
				$query.=" LIMIT $limit, $maxitem";

		 		if(($numitem%$maxitem)==0){
					$lastpage=($numitem/$maxitem);
				}else{
					$lastpage=($numitem/$maxitem)-(($numitem%$maxitem)/$maxitem)+1;
				}
				$maxpage = 3;

				// echo $query;
				$customer_purchases_query = mysql_query($query);
				$list_of_items_purchased = array();
				if(mysql_num_rows($customer_purchases_query)!=0){
					while($customer_purchases_row=mysql_fetch_assoc($customer_purchases_query)){
						$orderID = $customer_purchases_row["orderID"];
						$date_ordered = $customer_purchases_row["date_ordered_int"];
						$itemID = $customer_purchases_row["itemID"];
						$item_detail_id = $customer_purchases_row["item_detail_id"];
						$item_detail_data = mysql_fetch_assoc(mysql_query("SELECT * FROM tbl_items_detail WHERE item_detail_id='$item_detail_id'"));
						$price = $customer_purchases_row["price"];
						$item_data = mysql_fetch_assoc(mysql_query("SELECT * FROM tbl_items WHERE itemID='$itemID'"));
						$list_of_items_purchased[] = $itemID;
						echo "
						<tr>
							<td><a href='sales-re?id=$orderID'>S".sprintf("%06d",$orderID)."</a></td>
							<td>".date("m/d/Y",$date_ordered)."</td>
							<td><a href='item?s=$itemID'>".$item_data["itemname"]."</a></td>
							<td>".$item_detail_data["serial_number"]."</td>
							<td>".number_format($price,2)."</td>
						</tr>
						";
					}

					$list_of_items_purchased = array_unique($list_of_items_purchased);//removes duplicate data
					sort($list_of_items_purchased);//rearrange indexes in array
				}
				
				echo "
				</tbody>";
				echo "
				<label>Date From: </label><input type='text' id='date_from' value='$date_from'>
				<label>Date to: </label><input type='text' id='date_to' value='$date_to'>
				";
				echo "<label>Item: </label><select id='item'><option value='0'>All Items</option>";
				foreach ($list_of_items_purchased as $itemID) {
					$item_data = mysql_fetch_assoc(mysql_query("SELECT * FROM tbl_items WHERE itemID='$itemID'"));
					echo "<option value='$itemID' ";
					if($itemID==$item){
						echo "selected='selected'";
					}
					echo ">".$item_data["itemname"]."</option>";
				}
				echo "</select>";
				echo "<a href='?id=$customerID'>Reset Filter</a>";

			echo "
			</table>";

			echo "<div class='text-center'><ul class='pagination prints'>
			
			";
			$url="?id=$customerID&f=$date_from&t=$date_to&";
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
		";
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