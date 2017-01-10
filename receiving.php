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
  <title><?php echo $app_name; ?> - Receivings</title>
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

  <style>
  .item:hover{
	  cursor:pointer;
  }
  
  </style>
  <script>
		$(document).ready(function(){
		$("#remove_supplier").click(function(){
		var dataString = 'supplierID=0';
		  $.ajax({
				type: 'POST',
				url: 'receiving-cart',
				data: dataString,
				cache: false,
				success: function(html){
					location.reload();
				}
		  });
		});
		$(".serial_number").on("change keyup",function(e){
			var dataString = "serial_number="+e.target.value+"&id="+e.target.id;
			$.ajax({
				type: 'POST',
				url: 'receiving-cart-add',
				data: dataString,
				cache: false,
				success: function(html){

				}
			});
		});


		 	$( "#itemsearch_cat" ).autocomplete({
		      source: 'search-item-category-auto',
			  select: function(event, ui){
				  window.location='receiving-add?id='+ui.item.data;
			  }
		    });

		$(".quantity").on("change keyup",function(e){
			var dataString = 'id='+ e.target.id + '&value='+e.target.value;
			// alert(dataString);
			$.ajax({
				type: 'POST',
				url: 'receiving-cart-add',
				data: dataString,
				cache: false,
				success: function(html){
					$(".total_of_"+e.target.id).html(html);
					var dataString = 'total=receiving';
					// alert(dataString);
					$.ajax({
						type: 'POST',
						url: 'total',
						data: dataString,
						cache: false,
						success: function(html){
							$("#total_cost").html(html);
						}
					});
				}
			});
	  });
	  
	  $(".price").on("change keyup",function(e){
			var dataString = 'id='+ e.target.id + '&price='+e.target.value;
			$(".priceof_"+e.target.id).val(e.target.value);
			$.ajax({
				type: 'POST',
				url: 'receiving-cart-add',
				data: dataString,
				cache: false,
				success: function(html){
					$(".total_of_"+e.target.id).html(html);
					var dataString = 'total=receiving';
					// alert(dataString);
					$.ajax({
						type: 'POST',
						url: 'total',
						data: dataString,
						cache: false,
						success: function(html){
							$("#total_cost").html(html);
						}
					});
				}
			});
	  });
			
			
			$("#mode").change(function(){
				var mode = $(this).val();
				window.location='receiving-add?mode='+mode;
			});
			
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
	
	$( "#receiving" ).autocomplete({
      source: 'search-item-all',
	  select: function(event, ui){
		  window.location='receiving-add?id='+ui.item.data;
	  }
    });
	
	$( "#suppliers" ).autocomplete({
      source: 'search-supplier',
	  select: function(event, ui){
		  //window.location='receiving-add?id='+ui.item.data;
		  
		var dataString = 'supplierID='+ ui.item.data;
		  $.ajax({
				type: 'POST',
				url: 'receiving-cart',
				data: dataString,
				cache: false,
				success: function(html){
					location.reload();
				}
		  });
	  }
    });
	
	$("#comments").on("change keyup",function(){
		var comments = $(this).val();
		var dataString = 'comments='+ comments;
		  $.ajax({
				type: 'POST',
				url: 'receiving-cart',
				data: dataString,
				cache: false,
				success: function(html){
					
				}
		  });
	});
	
  	
	$("#purchase_order").on("change keyup",function(){
		var purchase_order = $(this).val();
		var dataString = 'purchase_order='+ purchase_order;
		  $.ajax({
				type: 'POST',
				url: 'receiving-cart',
				data: dataString,
				cache: false,
				success: function(html){
					
				}
		  });
	});
	

	$("#cancel").click(function(){
		$.ajax({
			type: "POST",
			data: "delete=1",
			url: "receiving-form",
			cache: false,
			success: function(data){
				location.reload();
			}
		});
	});


	$('#receiving-form').submit(function(e){
		e.preventDefault();
		$("#savecontinue").attr("disabled","disabled");
		// alert($('#receiving-form :input').serialize()+"&savecontinue=1");
		$.ajax({
			type: "POST",
			data: $('#receiving-form :input').serialize()+"&savecontinue=1",
			url: "receiving-form-new",
			cache: false,
			dataType: "json",
			success: function(data){
				// alert(data);
				window.location = "receiving-re?id="+data.orderID;
			}
			
		});
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
				$cartquery = mysql_query("SELECT * FROM tbl_cart_receiving WHERE accountID='$accountID'");
	?>
	<form action='receiving-form' class='form-horizontal' method='post' id="receiving-form">
	<div class='col-md-2'>
	<!-- <input type='hidden' name='itemID' value='<?php echo $itemID; ?>'> -->

	<?php if(mysql_num_rows($cartquery)!=0){
		$supplierIDquery = mysql_query("SELECT * FROM tbl_cart_receiving WHERE accountID='$accountID'");
		$supplier_company = "";
		if(mysql_num_rows($supplierIDquery)!=0){
		while($row=mysql_fetch_assoc($supplierIDquery)){
			$supplierID = $row["supplierID"];
			$purchase_order = $row["purchase_order"];
			$comments = $row["comments"];
			$mode = $row["mode"];
			
			echo "<input type='hidden' name='supplierID' value='$supplierID'>";
			$suppliernamequery = mysql_query("SELECT * FROM tbl_suppliers WHERE supplierID='$supplierID'");
			while($suprow=mysql_fetch_assoc($suppliernamequery)){
				$supplier_company = $suprow["supplier_company"];
			}
		}
		}else{
			$supplier_company = "";
		}

		
	?>
	<label>Supplier Name:</label>
	<?php 
		if($supplier_company==""){
			echo "	<input type='text' id='suppliers' class='form-control' placeholder='Type of Supplier Name' value='' name='supplier_name_post'>
			<a href='suppliers-add' class='btn btn-info btn-block' target='_blank'><span class='glyphicon glyphicon-phone'></span> Add Supplier</a>";
		}else{
			echo "	<input type='text' id='suppliers' class='form-control' placeholder='Type of Supplier Name' value='$supplier_company' name='supplier_name_post' readonly>
			<a href='suppliers-add' class='btn btn-info btn-block' target='_blank'><span class='glyphicon glyphicon-phone'></span> Add Supplier</a>
			<a href='#' id='remove_supplier' class='btn btn-block btn-danger'><span class='glyphicon glyphicon-trash'></span> Remove Supplier</a>
			";
		}
	?>
	<br>
	<label>P.O. Number:</label>
	<input type='text' id='purchase_order' class='form-control' placeholder='Purchase Order Number' value='<?php echo $purchase_order; ?>' name='purchase_order'>
	<br>
	<!--
	<label>Type:</label>
	<select name='m' class='form-control' id='mode'>
		<option value='receive' <?php if($mode=='receive'){echo "selected='selected'"; }?>>Purchased</option>
		<option value='restock' <?php if($mode=='restock'){echo "selected='selected'"; }?>>Stock In</option>
	</select>
	<br>
	-->
	<span><b>Comments:</b></span>
	<textarea name='comments' class='form-control' id='comments'><?php echo $comments;?></textarea>
	<br>
	
	<span><b>Controls:</b></span>
	<!--<button class='btn btn-primary btn-block' name='save'><span class='glyphicon glyphicon-floppy-disk'></span> Save</button>-->
	<button class='btn btn-primary btn-block' name='savecontinue' id="savecontinue"><span class='glyphicon glyphicon-floppy-disk'></span> Save & Continue</button>
	<span class='btn btn-danger btn-block' name='delete' id="cancel"><span class='glyphicon glyphicon-trash'></span> Cancel</span>
	<br>
	
	
	<?php } ?>
	</div>
	<div class='table-responsive col-md-10'>

	<div class="row">

		<div class="col-md-6">
		<label>Search Item Name to add Items to Receving Cart:</label>
			<input type='text' name='itemname' placeholder='Type for Item Name' class='form-control' id='receiving' autocomplete='off'>
		</div>

		<div class="col-md-6">
		<label>Search Category to add Items to Receving Cart:</label>
			<input type='text' name='itemname' placeholder='Type for Category' class='form-control' id='itemsearch_cat' autocomplete='off'>
		</div>

	</div>

	<table class='table table-hover tablesorter tablesorter-default' id='myTable'>
	<thead>
		<tr>
			<th>Item Name</th>
			<th>Serial Number</th>
			<th>Remaining</th>
			<th>Quantity</th>
			<th>Cost Price</th>
			<th>SubTotal</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
	<?php
	$total = 0;
		if(mysql_num_rows($cartquery)!=0){
			while($itemrow=mysql_fetch_assoc($cartquery)){
				$cartID = $itemrow["cartID"];
				$itemID = $itemrow["itemID"];
				$quantity = $itemrow["quantity"];
				$serial_number = $itemrow["serial_number"];
				$costprice = $itemrow["costprice"];
				$subtotal = $quantity*$costprice;
				$total = $total + $subtotal;
				if($mode=='restock'){
					$subtotal=0;
					$total=0;
				}
				
				$pricequery = mysql_query("SELECT * FROM tbl_items WHERE itemID='$itemID'");
				while($pricerow=mysql_fetch_assoc($pricequery)){
					$itemname = $pricerow["itemname"];
					$has_serial = $pricerow["has_serial"];
					$remaining = $pricerow["quantity"];
					$category = $pricerow["category"];
				}
				if($has_serial==1){
					$remaining_data = mysql_query("SELECT * FROM tbl_items_detail WHERE itemID='$itemID' AND deleted='0' AND quantity='1'");
					$remaining = mysql_num_rows($remaining_data);
				}else{
					$remaining_data = mysql_fetch_assoc(mysql_query("SELECT * FROM tbl_items_detail WHERE itemID='$itemID' AND deleted='0'"));
					$remaining = $remaining_data["quantity"];
				}
				echo "
				<tr>
					<input type='hidden' name='itemID[]' value='$itemID'>
					<input type='hidden' name='mode[]' value='$mode'>
					<td><a href='item?s=$itemID'>$itemname</a><br><i style='font-size:75%;'>$category</i></td>
					<td>";

					if($has_serial==1){
						echo "<input type='text' value='$serial_number' name='serial_number[]' required='required' class='serial_number' id='$cartID'>";
					}else{
						echo "<input type='hidden' value='' name='serial_number[]'>";
					}
					echo "</td>
					<td>$remaining</td>
					<td><input type='number' min='1' max='99999999' value='$quantity' name='quantity[]' required='required' class='quantity' id='$itemID'";
					if($has_serial==1){
						echo "readonly";
					}

					echo "></td>
					<td><input type='number' min='1' max='9999999999' step='0.01' value='$costprice' name='costprice[]' required='required' class='price priceof_$itemID' id='$itemID'></td>
					<td><b class='cost'>₱<span class='total_of_$itemID'>".number_format($subtotal,2)."</span></b></td>
					<td>
					<span class='delete btn btn-danger' href='receiving-form?id=$cartID'>X</span>
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
<tr>
<?php if(mysql_num_rows($cartquery)!=0){?>
<td colspan='5' align='right'><b>Total Cost:</b></td>
<td><b class='cost'>₱<span id='total_cost'><?php echo number_format($total,2); ?></span></b></td>
<?php }else{
	
	echo "<td></td>";
} ?>
</tr>
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