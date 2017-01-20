<?php
ob_start();
session_start();
$accountID=@$_SESSION['accountID'];
$page=@$_GET['page'];
$cat=@$_GET['cat'];
$keyword=@$_GET['s'];
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
  <title><?php echo $app_name; ?> - Suppliers</title>
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
  <style>
  .item:hover{
	  cursor:pointer;
  }
  </style>
  <link rel="stylesheet" href="css/theme.default.min.css">
  <script type="text/javascript" src="js/jquery.tablesorter.js"></script> 
  <script>
  $(document).ready(function(){
	  $("#myTable").tablesorter();
	   $("#myTable").tablesorter( {sortList: [[1,0], [0,0]]} ); 
	  $("#add").click(function(){
		  window.location="suppliers-add";
	  });
	  
	  $('#select-all').click(function() {   
		if(this.checked) {
			// Iterate each checkbox
			$(':checkbox').each(function() {
				this.checked = true;                        
			});
			}else{
			// Iterate each checkbox
			$(':checkbox').each(function() {
				this.checked = false;                        
			});
			}
	  });

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
	  
	  $(".select").change(function(){
		  if(this.checked){
			  
		  }else{
			  $("#select-all").prop( "checked", false );
		  }
	  });
	  
	 $('.selected').click(function(event) {
        if (event.target.type !== 'checkbox') {
            $(':checkbox', this).trigger('click');
        }
    });

	   	$("#item").change(function(){
	   		var date_from = $("#date_from").val();
	   		var date_to = $("#date_to").val();
	   		var id = $("#id").val();
	   		var itemID = $("#item").val();
	 		window.location = "?id=" +id+ "&f=" +date_from + "&t=" +date_to+"&item="+itemID;
	   	});
	
	$("#cat").change(function(){
		var cat = $(this).val();
		window.location = "item?cat="+cat;
	});

	
	$( "#search" ).autocomplete({
      source: 'search-item-all',
	  select: function(event, ui){
		  window.location='item?s='+ui.item.data;
	  }
    });
	
	$( "#suppliers" ).autocomplete({
      source: 'search-supplier',
	  select: function(event, ui){
		  window.location='suppliers?id='+ui.item.data;
	  }
    });
	
  });
  
  </script>
  
    <style>
	#item_results
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
	
	.tablesorter-default{
		font:20px/20px;
		font-weight:1200;
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

		if($suppliers==1){
			$supplierID = $_GET["id"];
			$supplier_query = mysql_query("SELECT * FROM tbl_suppliers WHERE supplierID='$supplierID'");
			if(mysql_num_rows($supplier_query)==0){
				// header("location:success");
			}
			$supplier_data = mysql_fetch_assoc($supplier_query);
			echo "

			<input type='hidden' id='id' value='$supplierID'>
			Company Name: <b>".$supplier_data["supplier_name"]."</b><br>
			Address: <b>".$supplier_data["supplier_address"]."</b><br>
			Contact Number: <b>".$supplier_data["supplier_number"]."</b><br>
			<div class='table-responsive'>
				<table class='table table-hover'>
					<thead>
						<tr>
							<th>Receive ID</th>
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
					$query = "SELECT * FROM tbl_receiving WHERE supplierID='$supplierID' AND deleted='0'";
					$item = @$_GET["item"];
					if(isset($item)&&$item!=0){
						$query.=" AND itemID='$item'";
					}
					$date_from = @$_GET["f"];
					$date_to = @$_GET["t"];

					if((isset($date_from)&&isset($date_to))&&($date_from!=""&&$date_to!="")){
						$query.=" AND date_received BETWEEN '".strtotime($date_from)."' AND '".strtotime($date_to)."'";
					}

					if((isset($date_from)&&isset($date_to))&&($date_from!=""&&$date_to!="")){
						$query.= " ORDER BY date_received ASC";
					}else{
						$query.= " ORDER BY date_received DESC";
						
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
					$supplier_purchases_query = mysql_query($query);
					$list_of_items_purchased = array();
					if(mysql_num_rows($supplier_purchases_query)!=0){
						while($supplier_purchases_row=mysql_fetch_assoc($supplier_purchases_query)){
							$orderID = $supplier_purchases_row["orderID"];
							$date_ordered = $supplier_purchases_row["date_received"];
							$itemID = $supplier_purchases_row["itemID"];
							$item_detail_id = $supplier_purchases_row["item_detail_id"];
							$item_detail_data = mysql_fetch_assoc(mysql_query("SELECT * FROM tbl_items_detail WHERE item_detail_id='$itemID'"));
							$price = $supplier_purchases_row["costprice"];
							$item_data = mysql_fetch_assoc(mysql_query("SELECT * FROM tbl_items WHERE itemID='".$item_detail_data["itemID"]."'"));
							$list_of_items_purchased[] = $itemID;
							echo "
							<tr>
								<td><a href='receiving-re?id=$orderID'>R".sprintf("%06d",$orderID)."</a></td>
								<td>".date("m/d/Y",$date_ordered)."</td>
								<td><a href='item?s=$itemID'>".$item_data["itemname"]."</a></td>
								<td>".$supplier_purchases_row["serial_number"]."</td>
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
					echo "<a href='?id=$supplierID'>Reset Filter</a>";

				echo "
				</table>";

				echo "<div class='text-center'><ul class='pagination prints'>
				
				";
				$url="?id=$supplierID&f=$date_from&t=$date_to&";
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