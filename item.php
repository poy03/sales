<?php
ob_start();
session_start();
$accountID=@$_SESSION['accountID'];
$page=@$_GET['page'];
$cat=@$_GET['cat'];
$sort=@$_GET['sort'];
if(!isset($sort)){
	$sort = "A-Z";
}
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
  <title><?php echo $app_name; ?> - Items</title>
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
		  window.location="item-add";
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
	
	$("#cat").change(function(){
		var cat = $(this).val();
		window.location = "item?cat="+cat;
	});
	$("#sort").change(function(){
		var sort = $(this).val();
		var cat = $("#cat").val();
		window.location = "item?cat="+cat+"&sort="+sort;
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
					Hi <?php echo $employee_name; ?></a>
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
	if($items==1){

		
	if(isset($_POST["edit"])){
		$x = array();
		$x = $_POST["select"];
		$_SESSION["selectitem"]=$x;
		header("location:item-edit");
	}

	if(isset($_POST["sales"])){
		$x = array();
		$x = $_POST["select"];
		foreach($x as $itemID){
			$cartquery = mysql_query("SELECT * FROM tbl_cart WHERE accountID='$accountID' AND itemID='$itemID'");
			if(mysql_num_rows($cartquery)==0){
				mysql_query("INSERT INTO tbl_cart VALUES ('','$itemID','1','0','$accountID','','','')");
			}else{
				$searchquery = mysql_query("SELECT * FROM tbl_cart WHERE itemID='$itemID'");
				while($searchrow=mysql_fetch_assoc($searchquery)){
					$quantity = $searchrow["quantity"];
					$quantity++;
				}
				$updatequery = mysql_query("UPDATE tbl_cart SET quantity='$quantity' WHERE itemID='$itemID'");
			}
		}
		header("location:sales");
	}
	if(isset($_POST["delete"])){
		$x = array();
		$x = @$_POST["select"];
		$_SESSION["selectitem"]=$x;
		if($x!=NULL){ 
		?>
		<script type="text/javascript">
			$(document).ready(function() {
				 var conf = confirm("Are you sure you want to delete selected items?");
				 if(conf==true){
					 window.location="item-delete";
				 }
			});
		</script>
		<?php

		}
	}
	?>
	<form action="item" method='post'>
	<div class='col-md-2'>
	<label>Controls:</label>
	<span class='btn btn-primary btn-block' name='add' id='add'><span class='glyphicon glyphicon-briefcase'></span> Add Items</span>
	<?php if($type=='admin'){ ?>	
	<button class='btn btn-primary btn-block' name='edit' type='submit'><span class='glyphicon glyphicon-edit'></span> Edit Items</button>
	<button class='btn btn-danger btn-block' name='delete' type='submit' id='delete'><span class='glyphicon glyphicon-trash'></span> Delete Items</button>
	<?php } ?>
	<br>
	<label>Category:</label>
	<select class='form-control' id='cat'>
		<option value='all'>All</option>
		<?php
			$categoryquery = mysql_query("SELECT DISTINCT category FROM tbl_items WHERE deleted='0' ORDER BY category");
			if(mysql_num_rows($categoryquery)!=0){
				while($categoryrow=mysql_fetch_assoc($categoryquery)){
					$dbcategory = $categoryrow["category"];
					$cat_items_query = mysql_query("SELECT * FROM tbl_items WHERE deleted='0' AND category='$dbcategory'");
					$cat_i = mysql_num_rows($cat_items_query);
					if($cat_i==0){
						$cat_i='';
					}else{
						$cat_i = "($cat_i)";
					}
					echo '
					<option value="'.urlencode(htmlspecialchars_decode($dbcategory)).'" ';
					if(isset($cat)&&htmlspecialchars($cat)==$dbcategory){
						echo "selected='selected'";
					}
					echo ">$dbcategory $cat_i</option>
					";
				}
			}

		?>
	</select>
	<br>
	<span><b>Sort:</b></span>
	<select class='form-control' id='sort'>
		<option value='A-Z' <?php if(strtolower($sort)=='a-z'){echo "selected";}?>>A-Z</option>
		<option value='Z-A' <?php if(strtolower($sort)=='z-a'){echo "selected";}?>>Z-A</option>
		<option value='Q-R' <?php if(strtolower($sort)=='q-r'){echo "selected";}?>>Quantity < Reorder Level</option>
		<option value='Q-D' <?php if(strtolower($sort)=='q-d'){echo "selected";}?>>Quantity DESC</option>
		<option value='Q-A' <?php if(strtolower($sort)=='q-a'){echo "selected";}?>>Quantity ASC</option>
	</select>
	</div>
	<div class='table-responsive col-md-10'>
	<table class='table table-hover tablesorter tablesorter-default' id='myTable'>
	 <thead>
	  <tr>
	   <th><input type="checkbox" id="select-all" value='all'> All</th>
	   <th>Category</th>
	   <th>Item Name</th>
	   <th>Cost Price</th>
	   <th>Suggested Retail Price</th>
	   <th>Dealer Price</th>
	   <th>Remaining Quantity</th>
	   <th>Reorder Level</th>
	   <th></th>
	  </tr>
	 </thead>
	 <tbody>
	 <?php
		if(!isset($page)){$page=1;}elseif($page<=0){$page=1;}
		$maxitem = $maximum_items_displayed; // maximum items
		$limit = ($page*$maxitem)-$maxitem;
	 $query = "SELECT * FROM tbl_items WHERE deleted = 0";
	 if(isset($keyword)){
		 $query.=" AND itemID='$keyword'";
	 }
	 if(isset($cat)&&strtolower($cat)!='all'){
		 if($cat==''){
		 }else{
		 	$cat = mysql_real_escape_string(htmlspecialchars(trim($cat)));
		 $query.=" AND category='$cat'";
		 }
	 }
	 if(strtolower($sort)=="a-z"){
		$query.=" ORDER BY itemname";
	 }elseif(strtolower($sort)=="z-a"){
		$query.=" ORDER BY itemname DESC";
	 }elseif(strtolower($sort)=="q-r"){
		$query.=" ORDER BY quantity>reorder, itemname";
	 }elseif(strtolower($sort)=="q-a"){
		$query.=" ORDER BY quantity ASC";
	 }elseif(strtolower($sort)=="q-d"){
		$query.=" ORDER BY quantity DESC";
	 }else{
		$query.=" ORDER BY itemname";
	 }
	 $numitemquery = mysql_query($query);
	 $numitem = mysql_num_rows($numitemquery);
	 $query.=" LIMIT $limit, $maxitem";
	 // echo $query;
	 $itemquery = mysql_query($query);
	 
	 
	 		if(($numitem%$maxitem)==0){
				$lastpage=($numitem/$maxitem);
			}else{
				$lastpage=($numitem/$maxitem)-(($numitem%$maxitem)/$maxitem)+1;
			}
			$maxpage = 3;
			
	 
	 
	 if(mysql_num_rows($itemquery)!=0){
		$i =0;
		$q = @$_POST['select'];
		
		 while($itemrow=mysql_fetch_assoc($itemquery)){
			 $itemID = $itemrow["itemID"];
			 $itemname = $itemrow["itemname"];
			 $category = $itemrow["category"];
			 $has_serial = $itemrow["has_serial"];
			 $costprice = $itemrow["costprice"];
			 $srp = $itemrow["srp"];
			 $dp = $itemrow["dp"];
			 $quantity = $itemrow["quantity"];
			 $comment = $itemrow["comment"];
			 $reorder_value = $itemrow["reorder"];
			 $item_detail_data = mysql_fetch_assoc(mysql_query("SELECT SUM(quantity) as stocks FROM tbl_items_detail WHERE itemID='$itemID'"));
			 if($item_detail_data["stocks"]==NULL){
			 	$item_detail_data["stocks"] = 0;
			 }

			 $remaining_data = mysql_query("SELECT * FROM tbl_items_detail WHERE itemID='$itemID' AND deleted='0' AND quantity='1'");
			 $quantity = mysql_num_rows($remaining_data);


			 if($reorder_value>=$quantity){
				 $reorder = "warning";
			 }else{
				 $reorder = "";
			 }

			 if($has_serial==1){
			 	$has_serial = '<a href="item-details?id='.$itemID.'">View S/N</a>';
			 }else{
			 	$has_serial = "";
			 }
			 echo "
			 <tr class='selected $reorder'>
			  <td><input type='checkbox' name='select[]' value='$itemID' class='select' ";
			  if(isset($_POST["select"])){
				  if(in_array($itemID,$_POST['select'])) echo 'checked';
			  }
			  echo "></td>
			  <td>$category</td>
			  <td>$itemname</td>
			  <td>₱".number_format($costprice,2)."</td>
			  <td>₱".number_format($srp,2)."</td>
			  <td>₱".number_format($dp,2)."</td>
			  <td>".$item_detail_data["stocks"]."</td>
			  <td>$reorder_value</td>
			  <td>$has_serial</td>
			 </tr>
			 ";
	     $i++;
		 }
	 }
	 ?>
	 </tbody>
	 <tfoot>
	 	<tr>
	 		<td>Total</td>
	 		<td><?php
	 		if($cat!="all"&&$cat!=""){
	 			if(isset($_GET["cat"])){
	 				$item_query = mysql_query("SELECT * FROM tbl_items WHERE category='$cat'");
	 				// echo "SELECT * FROM tbl_items WHERE category='$cat'";
	 				$sum_of_all_items = 0;
	 				while ($row=mysql_fetch_assoc($item_query)) {
	 					$itemID = $row["itemID"];
	 					// echo "SELECT SUM(quantity) as sum_per_items FROM tbl_items_detail WHERE itemID='$itemID'";
	 					$sum_per_items = mysql_fetch_assoc(mysql_query("SELECT SUM(quantity) as sum_per_items FROM tbl_items_detail WHERE itemID='$itemID' AND deleted='0'"));
	 					// echo $sum_per_items["sum_per_items"];
	 					$sum_of_all_items+=$sum_per_items["sum_per_items"];
	 				}
	 				echo $sum_of_all_items;
	 			}
	 		}else{
	 			$item_detail_data = mysql_fetch_assoc(mysql_query("SELECT SUM(quantity) as sum_per_items FROM tbl_items_detail WHERE deleted='0'"));
	 			echo $item_detail_data["sum_per_items"];
	 		}


	 		?></td>
	 	</tr>
	 </tfoot>
	</table>
<?php
			echo "
		<div class='text-center'>
			<ul class='pagination prints'>
			
			";
			$url="?cat=$cat&sort=$sort&";
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
			echo "</ul><span class='page' >Page $page</span>
			</div>
			";
			
			?>
	</div>
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