<?php
ob_start();
session_start();
$accountID=@$_SESSION['accountID'];
$orderID=@$_SESSION['orderID'];

$page=@$_GET['page'];

$keyword=@$_GET['keyword'];
$search=@$_GET['search'];
			$by=@$_GET['by'];
			$order=@$_GET['order'];

#$connect = mysql_connect("localhost","qfcdavao_admin","_39a11nwpm");
#mysql_select_db("qfcdavao_dbinventory");

include 'db.php';

$cart_query = mysql_query("SELECT * FROM tbl_cart WHERE accountID='$accountID'");
if(mysql_num_rows($cart_query)==0){
	$typeprice=@$_GET['type'];
}else{
	while($cart_row=mysql_fetch_assoc($cart_query)){
		$typeprice=$cart_row["type_price"];
	}
	
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title><?php echo $app_name; ?> - Sales</title>
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
  <script src="js/bootstrap-multiselect.js"></script>
  <link rel="stylesheet" href="css/bootstrap-multiselect.css">
  <style>
  .item:hover{
	  cursor:pointer;
  }

  </style>
  <link rel="stylesheet" href="css/theme.default.min.css">
  <script type="text/javascript" src="js/jquery.tablesorter.js"></script>
  <script type="text/javascript" src="js/shortcut.js"></script>
  
  <link rel="stylesheet" href="themes/smoothness/jquery-ui.css">
   
  <script src="jquery-ui.js"></script>
  <script type="text/javascript" src="js/shortcut.js"></script>
  
  <script>
  $(document).ready(function(){
	  

	  
	  $("#add_customer").click(function(){
		  window.location= "customer-add";
	  });
	  
	  $("#myTable").tablesorter();
	  $("#myTable").tablesorter( {sortList: [[1,0], [0,0]]} );
	  
	  $("#reset").click(function(){
		  var dataString = 'reset=1';
		  $.ajax({
			type: 'POST',
			url: 'sales-cart-add',
			data: dataString,
			cache: false,
			success: function(html){
				window.location = 'sales';
			}
		  });
	  });
	  
	  $(".quantity").on("change keyup",function(e){
			var dataString = 'id='+ e.target.id + '&value='+e.target.value;
			$.ajax({
				type: 'POST',
				url: 'sales-cart-add',
				data: dataString,
				cache: false,
				success: function(html){
					$(".total_of_"+e.target.id).html(html);
					$(".quantity_of_"+e.target.id).val(e.target.value);
					var dataString = 'total=sales';
					$.ajax({
						type: 'POST',
						url: 'total',
						data: dataString,
						cache: false,
						success: function(html){
							$("#total").html(html);
							$("#total_top").html(html);
						}
					});
				}
			});
	  });
	  
	  $(".price").on("change keyup",function(e){
			var dataString = 'id='+ e.target.id + '&price='+e.target.value;
			$.ajax({
				type: 'POST',
				url: 'sales-cart-add',
				data: dataString,
				cache: false,
				success: function(html){
					$(".total_of_"+e.target.id).html(html);
					$(".price_of_"+e.target.id).val(e.target.value);
					var dataString = 'total=sales';
					$.ajax({
						type: 'POST',
						url: 'total',
						data: dataString,
						cache: false,
						success: function(html){
							$("#total").html(html);
							$("#total_top").html(html);
						}
					});
				}
			});
	  });
	  
	  $("#type").change(function(){
		  var n = $(this).val();
		  window.location="sales-type?type="+n;
	  });
	  $(".delete").click(function(){
		  if(conf = confirm("Are you sure you want to delete selected item?")){
		  var id = $(this).attr("href");
		  window.location=id;
		  }		  
	  });
	  $("#type_payment_cart").change(function(){
		var type_payment = $(this).val();
		var dataString = 'search='+ type_payment;
		  $.ajax({
				type: 'POST',
				url: 'sales-cart-add',
				data: dataString,
				cache: false,
				success: function(html){
					
				}
		  });
	  });
	  

	$(document).on("keyup change","#searchid",function(e){
		var customer = $(this).val();
		var dataString = 'customer='+encodeURIComponent(customer);
		// alert(dataString);
		  $.ajax({
				type: 'POST',
				url: 'sales-add-customer-02',
				data: dataString,
				cache: false,
				success: function(html){
					// console.log(html);
				}
		  });
	});

	$( "#searchid" ).autocomplete({
      source: 'search-customer-auto',
	  select: function(event, ui){
		  window.location='sales-add-customer?id='+ui.item.data;
	  }
    });
		
		
	$( "#itemsearch" ).autocomplete({
      source: 'search-item-sales',
	  select: function(event, ui){
	  	// alert(ui.item.data);
		  window.location='sales-add?id='+ui.item.data;
	  }
    });

 	$( "#itemsearch_cat" ).autocomplete({
      source: 'search-item-category-sales',
	  select: function(event, ui){
		  window.location='sales-add?id='+ui.item.data;
	  }
    });
    

	$("#terms").change(function(){
		var terms = $(this).val();
		var dataString = 'terms='+terms;
		  $.ajax({
				type: 'POST',
				url: 'sales-cart-add',
				data: dataString,
				cache: false,
				success: function(html){
					
				}
		  });
	});
	
	
	$( "#search" ).autocomplete({
      source: 'search-item-all',
	  select: function(event, ui){
		  window.location='item?s='+ui.item.data;
	  }
    });
	

	$( "#itemsearch_serial_number" ).autocomplete({
      source: 'search-item-serial-number',
	  select: function(event, ui){
		  window.location='sales-add?id='+ui.item.data;
	  }
    });

	$("#delall").click(function(){
		var dataString = 'delall=1';
		  $.ajax({
				type: 'POST',
				url: 'sales-cart-add',
				data: dataString,
				cache: false,
				success: function(html){
					window.location='sales';
				}
		  });
	});
		
		$('#type_payment').multiselect();
		$('#type_payment').change(function(){
			var type_payment = $(this).val();
			var dataString = "type_payment="+type_payment;
			$.ajax({
				type: 'POST',
				url: 'sales-cart-add',
				data: dataString,
				cache: false,
				success: function(){
					// window.location = 'sales'
				}
			});
		});
		
		$("#del_customer").click(function(){
			window.location='sales-add-customer?id=0';
		});

		$("#sales-form").submit(function(e) {
			e.preventDefault();
			// alert($("#sales-form").serialize()+"&savecontinue=1");
			$("#savecontinue").attr("disabled","disabled");
			$.ajax({
				type: "POST",
				data: $("#sales-form").serialize()+"&savecontinue=1",
				url: $("#sales-form").attr("action"),
				success: function(){
					window.location = "sales-complete";
				}
			});
		});
		$("#total_top").html($("#total").html());

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
	#result,#itemresult, #item_results
	{
		width:100%;
		max-height:200px;
		padding:10px;
		display:none;
		margin-top:-1px;
		border-top:0px;
		overflow:auto;
		border:1px #CCC solid;
		background-color: white;
	}
	#item_results{
		position:absolute;
		width:250px;
		z-index:3;
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
		  <a class = "navbar-brand" href = "index" tabindex='-1'><?php echo $app_name; ?></a>
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
		  if($logged==0){ ?>
		  	<ul class='nav navbar-nav navbar-right'>
				<li><a href='login'><span class='glyphicon glyphicon-log-in'></span> Login</a></li>
			</ul>
		  <?php }else{ ?>
		  	<ul class='nav navbar-nav navbar-right'>
				
				
				<li>
					<a tabindex='-1' href='#' role='button' 
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
		if($sales=='1'){
	?>
	<div class="row">
	<?php

	$inthecartquery = mysql_query("SELECT * FROM tbl_cart WHERE accountID='$accountID'");
	if(mysql_num_rows($inthecartquery)!=0){
	?>
		<div class='col-md-8'>
		<label>Customer:</label>
		<div class="input-group">
		<?php
		$customername = mysql_query("SELECT DISTINCT customer,type_payment,comments,customerID,terms FROM tbl_cart WHERE accountID='$accountID' ORDER BY customer DESC");
		if(mysql_num_rows($customername)!=0){
			while($cusrow=mysql_fetch_assoc($customername)){
				$customer = $cusrow["customer"];
				$comments = $cusrow["comments"];
				$terms = $cusrow["terms"];
				$customerID = $cusrow["customerID"];
				$type_payment_db = $cusrow["type_payment"];
				if($terms==0){
					$terms=30;
				}
			}
			if($customerID!=0){
			echo '<input tabindex="-1" type="text" form="sales-form" class="form-control search" id="searchid" name="customer" placeholder="Type for Customer Name" value="'.htmlspecialchars_decode($customer).'" autocomplete="off" readonly>';
			echo "<a class='btn btn-danger input-group-addon' id='del_customer' tabindex='-1'><span class='glyphicon glyphicon-trash'></span> Remove Customer</a>";
			}else{
			echo '<input tabindex="-1" type="text" form="sales-form" class="form-control search" id="searchid" name="customer" placeholder="Type for Customer Name" value="'.$customer.'" autocomplete="off" required="required">';
			echo "<a class='btn btn-info  input-group-addon' href='customer-add' target='_blank' tabindex='-1'><span class='glyphicon glyphicon-user'></span> Add Customer</a>";
			}
		}else{
			echo '<input tabindex="-1" type="text" form="sales-form" class="form-control search" id="searchid" name="customer" placeholder="Type for Customer Name" autocomplete="off" required="required">';
		}
		?>
		</div>


		
		</div>
		<div class='col-md-4' style="border: 1px solid grey">
			<label>Total:</label>
			<p style='font-size:300%'><span>₱<span id='total_top'></span></p>
		</div>

		<?php } ?>
	</div>
	<br>
	<div class='col-md-2'>
	
		
	<form action='sales-form' method='post' id="sales-form">
	
	<?php

	
	
	// if empty cart
	$inthecartquery = mysql_query("SELECT * FROM tbl_cart WHERE accountID='$accountID'");
	if(mysql_num_rows($inthecartquery)!=0){
	?>
	<span><b>Type of Price:</b></span>
	<select id='type' class='form-control' tabindex='-1'>
		<option value='srp' 
		<?php if(isset($typeprice)&&strtolower($typeprice)=="srp"){
		echo "selected='selected'";	
		}
		?> >Suggested Retail Price</option>
		<option value='dp'
		<?php if(isset($typeprice)&&strtolower($typeprice)=="dp"){
		echo "selected='selected'";	
		}
		?>
		>Dealer Price</option>
	</select>
	<a class='btn btn-info btn-block' id='reset'><span class='glyphicon glyphicon-refresh'></span> Reset All Prices</a>
	<br>
	<!--
	<span><b>Customer:</b></span>
	<?php
	$customername = mysql_query("SELECT DISTINCT customer,type_payment,comments,customerID,terms FROM tbl_cart WHERE accountID='$accountID' ORDER BY customer DESC");
	if(mysql_num_rows($customername)!=0){
		while($cusrow=mysql_fetch_assoc($customername)){
			$customer = $cusrow["customer"];
			$comments = $cusrow["comments"];
			$terms = $cusrow["terms"];
			$customerID = $cusrow["customerID"];
			$type_payment_db = $cusrow["type_payment"];
			if($terms==0){
				$terms=30;
			}
		}
		if($customerID!=0){
		echo "<input tabindex='-1' type='text' class='form-control search' id='searchid' name='customer' placeholder='Type for Customer Name' value='$customer' autocomplete='off' readonly>";
		}else{
		echo "<input tabindex='-1' type='text' class='form-control search' id='searchid' name='customer' placeholder='Type for Customer Name' value='$customer' autocomplete='off' required='required'>";
		}
	}else{
		echo "<input tabindex='-1' type='text' class='form-control search' id='searchid' name='customer' placeholder='Type for Customer Name' autocomplete='off' required='required'>";
	}
	?>
	<a class='btn btn-info btn-block' href='customer-add' target='_blank' tabindex='-1'><span class='glyphicon glyphicon-user'></span> Add Customer</a>
	<?php
	if($customerID!=0){
	echo "<a class='btn btn-danger btn-block' id='del_customer' tabindex='-1'><span class='glyphicon glyphicon-trash'></span> Remove Customer</a>";
	}
	?>
	<br>
	-->
	<span><b>Type of Payment:</b></span>
	<select id='type_payment' multiple='multiple' name='type_payment[]' class='form-control' required='required' tabindex='-1'>
	<?php
	$type_payment_array = explode(",",$type_payment);
	$type_payment_db_array = explode(",",$type_payment_db);

	foreach($type_payment_array as $type_payment_each){
		if($customerID==0&&preg_match("/\bcredit\b/ii", $type_payment_each)){

		}else{
			echo "<option value='$type_payment_each'";
			if(isset($type_payment_db)){
			  if(in_array($type_payment_each,$type_payment_db_array)){
				echo "selected='selected'";
			  }
			}
			echo ">$type_payment_each</option>";
		}

	}

	?>
	</select>
	<br>
	<br>
	<label>Terms:</label>
	<input type='number' min='1' name='terms' class='form-control' id='terms' tabindex='-1' placeholder='Number of Days' value='<?php echo $terms; ?>'>
	<small><i>* For Credits Only.</i></small>
	<br>
	<br>
	<span><b>Comments:</b></span>
	<textarea name='comments' class='form-control' tabindex='-1'><?php echo $comments; ?></textarea>
	
	<br>
	<span><b>Sales Register:</b></span>
	<!--<button class='btn btn-primary btn-block' name='save'><span class='glyphicon glyphicon-floppy-disk'></span> Save</button>-->
	<button class='btn btn-primary btn-block' id="savecontinue" name='savecontinue'  tabindex='-1'><span class='glyphicon glyphicon-floppy-disk'></span> Save & Continue</button>
	<a class='btn btn-danger btn-block' name='delete' id='delall' tabindex='-1'><span class='glyphicon glyphicon-trash'></span> Cancel Sale</a>
	<br>
	<?php }
	echo "
	
	<label>Utilities:</label><a tabindex='-1' class='btn btn-info btn-block' name='delete' href='sales-re'><span class='glyphicon glyphicon-shopping-cart'></span> Sales Search</a>";


	if($orderID!=Null){
		echo "<a tabindex='-1' href='sales-complete' class='btn btn-block btn-info'>View Last Transaction</a>";
	}
	?>
	

	
	
	</div>
	<div class='table-responsive col-md-10'>
	<div class="row">
		<div class='col-md-6'>
		<label>Search for Item Name for items w/out S/N</label>
		<div class = "form-group">
		   <input type = "text" class = "form-control itemsearch" name='itemname' id='itemsearch' autocomplete='off' placeholder='Type for Item Name'>
		</div>		
		</div>		

		<div class='col-md-6'>
		<label>Search for Category of Items for items w/out S/N</label>
		<div class = "form-group">
		   <input type = "text" class = "form-control" name='itemname' id='itemsearch_cat' autocomplete='off' placeholder='Type for Category'>
		</div>		
		</div>
	</div>
	<div class="row">
	
	<div class='col-md-6'>
	<label>Search for S/N to add in Cart:</label>
	<div class = "form-group">
	   <input type = "text" class = "form-control" id='itemsearch_serial_number' autocomplete='off' placeholder='Type for Serial Number'>
	</div>		
	</div>

	</div>

	<table class='table table-hover tablesorter tablesorter-default'>
	<thead>
		<tr>
			<th>Item Name</th>
			<th>Serial Number</th>
			<th>Remaining</th>
			<th>Quantity</th>
			<?php
			if(isset($typeprice)&&strtolower($typeprice)=='dp'){
				echo "<th>Dealer Price</th>";
				echo "<input type='hidden' name='type' value='$typeprice'>";
				
			}else{
				echo "<th>Suggested Retail Price</th>";
				echo "<input type='hidden' name='type' value='srp'>";
			}
			?>
			
			<th style='text-align:right'>Line Total</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
	<?php
	$total = 0;
		$cartquery = mysql_query("SELECT * FROM tbl_cart WHERE accountID='$accountID'");
		if(mysql_num_rows($cartquery)!=0){
			while($itemrow=mysql_fetch_assoc($cartquery)){
				$itemID = $itemrow["itemID"];
				$item_detail_id = $itemrow["item_detail_id"];
				$quantity = $itemrow["quantity"];
				$cartprice = $itemrow["price"];
				$customerID = $itemrow["customerID"];


				$item_detail_data = mysql_fetch_assoc(mysql_query("SELECT * FROM tbl_items_detail WHERE item_detail_id='$item_detail_id'"));
				$pricequery = mysql_query("SELECT * FROM tbl_items WHERE itemID='$itemID'");
				while($pricerow=mysql_fetch_assoc($pricequery)){
					$itemname = $pricerow["itemname"];
					$has_serial = $pricerow["has_serial"];
					$remaining = $pricerow["quantity"];
					$category = $pricerow["category"];
					if($cartprice=="0"){
						if(isset($typeprice)&&strtolower($typeprice)=='dp'){
							$price = $pricerow['dp'];
							
						}else{
							$price = $pricerow['srp'];
						}
					}else{
						$price=$cartprice;
					}
				}
				if($has_serial==1){
					$remaining_data = mysql_query("SELECT * FROM tbl_items_detail WHERE itemID='$itemID' AND deleted='0' AND quantity='1'");
					$remaining = 1;
				}else{
					$remaining_data = mysql_fetch_assoc(mysql_query("SELECT * FROM tbl_items_detail WHERE itemID='$itemID' AND deleted='0'"));
					$remaining = $remaining_data["quantity"];
				}
				$subtotal = $quantity*$price;
				$total = $total + $subtotal;
				echo "
				<tr>
					<input type='hidden' name='itemID[]' value='$item_detail_id'>
					<td><a href='item?s=$itemID' tabindex='-1'>$itemname</a><br><i style='font-size:75%;'>$category</i></td>
					<td>".$item_detail_data["serial_number"]."</td>
					<td>".$remaining."</td>
					<td><input type='number' min='1' max='".$item_detail_data["quantity"]."' value='$quantity' name='quantity[]' required='required' class='quantity quantity_of_$itemID' id='$itemID' title='$item_detail_id' ";
					if($item_detail_data["has_serial"]==1){
						echo "readonly";
					}
					echo "></td>
					<td><input type='number' min='0' max='999999999' step='0.01' value='$price' name='price[]' required='required' class='price price_of_$itemID' id='$itemID' title='$item_detail_id'></td>
					<td style='text-align:right'>₱<span class='total_of_$itemID'>".number_format($subtotal,2)."</span></td>
					<td>
					<span class='delete btn btn-danger' href='sales-form?id=$itemID'>X</span>
					</td>
					
				</tr>
				";
			}
		}else{
			echo "
			<tr>
				<td colspan='20' align='center'><b style='font-size:200%'>No Items to Show.</b></td>
			</tr>
			";
		}

	?>
	</tbody>
	<tfoot>
	<?php
	if(mysql_num_rows($cartquery)!=0){
		echo "
	<tr>
		<input type='hidden' name='customerID' value='$customerID'>
		<input type='hidden' name='total' value='$total'>
		<td colspan='5' align='right'><b>Total:</b></td>
		<th style='text-align:right'>₱<span id='total'>".number_format($total,2)."</span></th>
		<td></td>
	</tr>
	";
	}
	?>
	</tfoot>
	</table>
	</div>
	</form>



	
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