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


?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title><?php echo $app_name; ?> - Edit Suppliers</title>
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
	if(isset($_POST["save"])&&isset($_SESSION["selectsupplier"])){
		$x = array();
		$x = $_SESSION["selectsupplier"];
		$supplier_name = $_POST["supplier_name"];
		$supplier_address = $_POST["supplier_address"];
		$supplier_company = $_POST["supplier_company"];
		$supplier_number = $_POST["supplier_number"];
		$i = 0;
		foreach($x as $supplierID){
			$supplier_name[$i] = mysql_real_escape_string(htmlspecialchars(trim($supplier_name[$i])));
			$supplier_address[$i] = mysql_real_escape_string(htmlspecialchars(trim($supplier_address[$i])));
			$supplier_company[$i] = mysql_real_escape_string(htmlspecialchars(trim($supplier_company[$i])));
			$supplier_number[$i] = mysql_real_escape_string(htmlspecialchars(trim($supplier_number[$i])));
			mysql_query("UPDATE tbl_suppliers SET
			supplier_name = '$supplier_name[$i]',
			supplier_address = '$supplier_address[$i]',
			supplier_company = '$supplier_company[$i]',
			supplier_number = '$supplier_number[$i]'
			WHERE supplierID = '$supplierID'");
			$i++;
		}
		echo "
				<div class = 'alert alert-success alert-dismissable' style='text-align:center'>
				   <button type = 'button' class = 'close' data-dismiss = 'alert' aria-hidden = 'true'>
					  &times;
				   </button>
					
				   <strong>Saved! </strong>Selected Suppliers are updated. <a href='suppliers'>Back to Suppliers.</a>
				</div>
				";
				unset($_SESSION["selectsupplier"]);
		exit;
	}
	?>
	<form action="suppliers-edit" method='post'>


	<div class='table-responsive col-md-12'>
	<table class='table'>
	 <thead>
	  <tr>
	   <th>Contact Person</th>
	   <th>Company Name</th>
	   <th>Contact Number</th>
	   <th>Address</th>
	  </tr>
	 </thead>
	 <tbody>
	 <?php
		if(!isset($page)){$page=1;}elseif($page<=0){$page=1;}
		$maxitem = 50; // maximum items
		$limit = ($page*$maxitem)-$maxitem;
	 $query = "SELECT * FROM tbl_suppliers";
	 $x = $_SESSION["selectsupplier"];
	 if($x!=NULL){
	 $cnt = 0;
	 foreach($x as $supplierID){
		 if($cnt==0){
			 $query.=" WHERE supplierID = $supplierID";
		 }else{
			 $query.=" OR supplierID = $supplierID";
		 }
		 $cnt++;
	 }
	 }else{
		 header("location:suppliers");
	 }
	 $query.=" ORDER BY supplier_name";
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
			 $dbsupplier_name = $itemrow["supplier_name"];
			 $dbsupplier_company = $itemrow["supplier_company"];
			 $dbsupplier_number = $itemrow["supplier_number"];
			 $dbsupplier_address = $itemrow["supplier_address"];
			 echo '
			 <tr>
			  <td><input type="text" name="supplier_name[]" value="'.$dbsupplier_name.'"></td>
			  <td><input type="text" name="supplier_company[]" value="'.$dbsupplier_company.'" required="required"></td>
			  <td><input type="number" name="supplier_number[]" value="'.$dbsupplier_number.'"></td>
			  <td><input type="text" name="supplier_address[]" value="'.$dbsupplier_address.'"></td>
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