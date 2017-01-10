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
  <title><?php echo $app_name; ?> - Add Items</title>
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
  <link rel="stylesheet" href="themes/smoothness/jquery-ui.css">
   
  <script src="jquery-ui.js"></script>
  <script type="text/javascript" src="js/shortcut.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <style>
  .item:hover{
	  cursor:pointer;
  }
  </style>
  <script>
  $(document).ready(function(){
//customer ajax search


    $( "#category" ).autocomplete({
      source: 'search'
    });

		
		
	$( "#search" ).autocomplete({
      source: 'search-item-all',
	  select: function(event, ui){
		  window.location='item?s='+ui.item.data;
	  }
    });
		

  		$("#quantity").attr("readonly","readonly");

		
  });

  $(document).on("change","#has_serial", function(e){
  	if(e.target.value==0){
  		$("#quantity").removeAttr("readonly");
  	}else{
  		$("#quantity").attr("readonly","readonly");
  	}
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
	#result,#itemresult,#item_results
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
		z-index:5;
		position:absolute;
	}
	#item_results{
		width:250px;

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
	if($items=='1'){
if(isset($_POST["submit"])){
	// var_dump($_POST["has_serial"]);
		$category = trim(htmlspecialchars(mysql_escape_string($_POST["category"])));
		$itemname = trim(htmlspecialchars(mysql_escape_string($_POST["itemname"])));
		$costprice = trim(htmlspecialchars(mysql_escape_string($_POST["costprice"])));
		$has_serial = trim(htmlspecialchars(mysql_escape_string($_POST["has_serial"])));
		$reorder = trim(htmlspecialchars(mysql_escape_string($_POST["reorder"])));
		$srp = trim(htmlspecialchars(mysql_escape_string($_POST["srp"])));
		$dp = trim(htmlspecialchars(mysql_escape_string($_POST["dp"])));
		// exit;
		
		// $comment = $_POST["comment"];
		$searchquery = mysql_query("SELECT * FROM tbl_items WHERE category='$category' AND itemname='$itemname' AND deleted='0'");
		if(mysql_num_rows($searchquery)<1){
		mysql_query("INSERT INTO tbl_items (itemname,category,costprice,srp,dp,reorder,has_serial) VALUES ('$itemname','$category','$costprice','$srp','$dp','$reorder','$has_serial')");

		$item_data = mysql_fetch_assoc(mysql_query("SELECT * FROM tbl_items ORDER BY itemID DESC LIMIT 0,1"));
		if($has_serial==0){
			$itemID = $item_data["itemID"];
			mysql_query("INSERT INTO tbl_items_detail (itemID,has_serial) VALUES ('$itemID','$has_serial')");
		}
		
		echo "
				<div class = 'alert alert-success alert-dismissable'>
				   <button type = 'button' class = 'close' data-dismiss = 'alert' aria-hidden = 'true'>
					  &times;
				   </button>
					
				   <strong>$itemname</strong> is successfuly saved.
				</div>
		
		";
		}else{
					echo "
				<div class = 'alert alert-danger alert-dismissable'>
				   <button type = 'button' class = 'close' data-dismiss = 'alert' aria-hidden = 'true'>
					  &times;
				   </button>
					
				   <strong>$itemname</strong> is already added.
				</div>
		
		";
		}
		
	}
	
	?>
	<form action="item-add" method='post' class='form-horizontal'>	
	<div class='col-md-2'>
		<span><b>Controls:</b></span>	

	<button class='btn btn-primary btn-block' type='submit' name='submit'><span class='glyphicon glyphicon-floppy-disk'></span> Save
	</button>
	</div>
	<div class='col-md-10'>

	<div class='form-group ui-widget'>
		<label for="category" class='col-md-2'>Category:</label>
	<div class='col-md-10'>
		<input type='text' class='form-control search' id='category' name='category' placeholder='Category' required='required' autocomplete='off'>
	</div>
	
	</div>
	
	<div class='form-group'>
		<label for="itemname" class='col-md-2'>Item Name:</label>
	<div class='col-md-10'>
		<input type='text' class='form-control' name='itemname' placeholder='Item Name' required='required'>
	</div>
	</div>
	
	<div class='form-group'>
		<label for="costprice" class='col-md-2'>Cost Price:</label>
	<div class='col-md-10'>
		<input step='0.01' min='0' max='99999999' type='number' class='form-control' name='costprice' placeholder='Cost Price' required='required'>
	</div>
	</div>

	<div class='form-group'>
		<label for="srp" class='col-md-2'>Suggested Retail Price:</label>
	<div class='col-md-10'>
		<input step='0.01' min='0' max='99999999' type='number' class='form-control' name='srp' placeholder='Suggested Retail Price' required='required'>
	</div>
	</div>

	<div class='form-group'>
		<label for="dp" class='col-md-2'>Dealer Price:</label>
	<div class='col-md-10'>
		<input step='0.01' min='0' max='99999999' type='number' class='form-control' name='dp' placeholder='Dealer Price' required='required'>
	</div>
	</div>


	<div class='form-group'>
		<label for="quantity" class='col-md-2'>Reorder Level:</label>
	<div class='col-md-10'>
		<input min='0' max='99999999' type='number' class='form-control' name='reorder' placeholder='Reorder Level' required='required'>
	</div>
	</div>


	<div class='form-group'>
		<label class='col-md-2'>Include Serial Numbers?</label>
	<div class='col-md-10'>
		<label class="radio-inline"><input type="radio" name="has_serial" id="has_serial" value="1" required='required'>Yes</label>
		<label class="radio-inline"><input type="radio" name="has_serial" id="has_serial" value="0" required='required'>No</label>
	</div>
	</div>

<!-- 	<div class='form-group'>
		<label for="quantity" class='col-md-2'>Quantity</label>
	<div class='col-md-10'>
		<input min='0' max='99999999' type='number' class='form-control' name='quantity' id="quantity" placeholder='Quantity' required="
		required="" ">
	</div>
	</div>
 -->
	<!--
	<div class='form-group'>
		<label for="comment" class='col-md-2'>Comment:</label>
	<div class='col-md-10'>
		<textarea class='form-control' name='comment'></textarea>
	</div>
	</div>
	-->
	</div>
	</form>
	
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