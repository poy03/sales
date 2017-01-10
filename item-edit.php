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

#$connect = mysql_connect("localhost","qfcdavao_admin","_39a11nwpm");
#mysql_select_db("qfcdavao_dbinventory");

include 'db.php';


if($items=='1'){
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title><?php echo $app_name; ?> - Edit Items</title>
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
input[type="text"] {
    width: 150px;
    display: block;
    margin-bottom: 10px;
}
  </style>
 <script>
		$(document).ready(function(){
	$( "#search" ).autocomplete({
      source: 'search-item-all',
	  select: function(event, ui){
		  window.location='item?s='+ui.item.data;
	  }
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

	if(isset($_POST["save"])&&isset($_SESSION["selectitem"])){
		$x = array();
		$x = $_SESSION["selectitem"];
		$itemname = $_POST["itemname"];
		$reorder = $_POST["reorder"];
		$category = $_POST["category"];
		$costprice = $_POST["costprice"];
		$dp = $_POST["dp"];
		$srp = $_POST["srp"];
		// $quantity = $_POST["quantity"];
		$i = 0;
		$error = 0;
		foreach($x as $itemID){

			$itemname[$i] = mysql_real_escape_string(htmlspecialchars(trim($itemname[$i])));
			$category[$i] = mysql_real_escape_string(htmlspecialchars(trim($category[$i])));
			$costprice[$i] = mysql_real_escape_string(htmlspecialchars(trim($costprice[$i])));
			$reorder[$i] = mysql_real_escape_string(htmlspecialchars(trim($reorder[$i])));
			$dp[$i] = mysql_real_escape_string(htmlspecialchars(trim($dp[$i])));
			$srp[$i] = mysql_real_escape_string(htmlspecialchars(trim($srp[$i])));


			$item_query = mysql_query("SELECT * FROM tbl_items WHERE itemname='$itemname[$i]' AND category='$category[$i]' AND itemID!='$itemID'");

			// echo mysql_num_rows($item_query);
			if(mysql_num_rows($item_query)==0){
				mysql_query("UPDATE tbl_items SET
				itemname = '$itemname[$i]',
				category = '$category[$i]',
				costprice = '$costprice[$i]',
				reorder = '$reorder[$i]',
				dp = '$dp[$i]',
				srp = '$srp[$i]'
				WHERE itemID = '$itemID'");
			}else{
				$error = 1;
			}
				$i++;
		}
		if($error==1){
			echo "
					<div class = 'alert alert-danger alert-dismissable' style='text-align:center'>
					   <button type = 'button' class = 'close' data-dismiss = 'alert' aria-hidden = 'true'>
						  &times;
					   </button>
						
					   <strong>Saved! </strong>Some Items are updated but some items have errors, it has the same category and itemname <a href='item'>Back to Items.</a>
					</div>
					";
		}else{
			echo "
					<div class = 'alert alert-success alert-dismissable' style='text-align:center'>
					   <button type = 'button' class = 'close' data-dismiss = 'alert' aria-hidden = 'true'>
						  &times;
					   </button>
						
					   <strong>Saved! </strong>Selected Items are updated. <a href='item'>Back to Items.</a>
					</div>
					";
		}
				unset($_SESSION["selectitem"]);
		exit;
	}
	?>
	<form action="item-edit" method='post'>


	<div class='table-responsive col-md-12'>
	<table class='table'>
	 <thead>
	  <tr>
	   <th>Category</th>
	   <th>Item Name</th>
	   <th>Cost Price</th>
	   <th>SRP</th>
	   <th>Dealer Price</th>
	   <th>Reorder Level</th>
	  </tr>
	 </thead>
	 <tbody>
	 <?php
		if(!isset($page)){$page=1;}elseif($page<=0){$page=1;}
		$maxitem = 50; // maximum items
		$limit = ($page*$maxitem)-$maxitem;
	 $query = "SELECT * FROM tbl_items";
	 $x = $_SESSION["selectitem"];
	 if($x!=NULL){
	 $cnt = 0;
	 foreach($x as $itemID){
		 if($cnt==0){
			 $query.=" WHERE itemID = $itemID";
		 }else{
			 $query.=" OR itemID = $itemID";
		 }
		 $cnt++;
	 }
	 }else{
		 header("location:item");
	 }
	 $query.=" ORDER BY itemname";
	 $numitemquery = mysql_query($query);
	 $numitem = mysql_num_rows($numitemquery);
	 //echo $query;
	 $itemquery = mysql_query($query);
	 
	 
	 		if(($numitem%$maxitem)==0){
				$lastpage=($numitem/$maxitem);
			}else{
				$lastpage=($numitem/$maxitem)-(($numitem%$maxitem)/$maxitem)+1;
			}
			$maxpage = 3;
			
	 
	 
	 if(mysql_num_rows($itemquery)!=0){
		 while($itemrow=mysql_fetch_assoc($itemquery)){
			 $dbitemID = $itemrow["itemID"];
			 $dbitemname = $itemrow["itemname"];
			 $dbcategory = $itemrow["category"];
			 $dbcostprice = $itemrow["costprice"];
			 $dbsrp = $itemrow["srp"];
			 $dbdp = $itemrow["dp"];
			 $dbreorder = $itemrow["reorder"];
			 $dbquantity = $itemrow["quantity"];
			 $dbcomment = $itemrow["comment"];
			 echo '
			 <tr>
			  <td><input type="text" name="category[]" value="'.$dbcategory.'" required="required"></td>
			  <td><input type="text" name="itemname[]" value="'.$dbitemname.'" required="required"></td>
			  <td><input type="number" step="0.01" min="0" max="99999999" name="costprice[]" value="'.$dbcostprice.'" required="required"></td>
			  <td><input type="number" step="0.01" min="0" max="99999999" name="srp[]" value="'.$dbsrp.'" required="required"></td>
			  <td><input type="number" step="0.01" min="0" max="99999999" name="dp[]" value="'.$dbdp.'" required="required"></td>
			  <!--<td><input type="number" min="0" max="99999999" name="quantity[]" value="'.$dbquantity.'" required="required"></td>-->
			  <td><input type="number" min="0" max="99999999" name="reorder[]" value="'.$dbreorder.'" required="required"></td>
			 </tr>
			 ';
		 }
	 }
	 ?>
	 </tbody>
	</table>	
	</div>
	<div class="span7 text-center"><button class='btn btn-primary' type='save' name='save'><span class='glyphicon glyphicon-floppy-disk'></span> Save</button></div>
	
	</div>

	</form>

	
	<?php
	
	}else{

		header("location:index");

		} ?>
	</div>
  </div>
</div>
</body>
</html>
<?php mysql_close($connect);}else{
		echo "<strong><center>You do not have the authority to access this module.</center></strong>";
	}?>
  <script>
$("[data-toggle=popover]")
.popover({html:true})
</script>