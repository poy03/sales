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
  <title><?php echo $app_name; ?> - Add Customers</title>
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
		  if($logged==0){
		  	header("location:index");

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
	if($customers=='1'){
if(isset($_POST["submit"])){
		$companyname = mysql_real_escape_string(htmlspecialchars(trim($_POST["companyname"])));
		$address = mysql_real_escape_string(htmlspecialchars(trim($_POST["address"])));
		$email = mysql_real_escape_string(htmlspecialchars(trim($_POST["email"])));
		$phone = mysql_real_escape_string(htmlspecialchars(trim($_POST["phone"])));
		$contactperson = mysql_real_escape_string(htmlspecialchars(trim($_POST["contactperson"])));
		$searchquery = mysql_query("SELECT * FROM tbl_customer WHERE companyname='$companyname' AND contactperson='$contactperson' AND deleted = '0'");
		if(mysql_num_rows($searchquery)<1){
		mysql_query("INSERT INTO tbl_customer VALUES ('','$companyname','$address','$email','$phone','$contactperson','','','')");
		
		echo "
				<div class = 'alert alert-success alert-dismissable'>
				   <button type = 'button' class = 'close' data-dismiss = 'alert' aria-hidden = 'true'>
					  &times;
				   </button>
					
				   <strong>$companyname</strong> is successfuly saved.
				</div>
		
		";
		}else{
		echo "
				<div class = 'alert alert-danger alert-dismissable'>
				   <button type = 'button' class = 'close' data-dismiss = 'alert' aria-hidden = 'true'>
					  &times;
				   </button>
					
				   <strong>$companyname</strong> is already added.
				</div>
		
		";	
		}
		
	}
	?>
	<form action="customer-add" method='post' class='form-horizontal'>	
	<div class='col-md-2'>
	<span><b>Controls:</b></span>	
	<button class='btn btn-primary btn-block' type='submit' name='submit'><span class='glyphicon glyphicon-floppy-disk'></span> Save
	</button>
	</div>
	<div class='col-md-10'>

	<div class='form-group'>
		<label for="companyname" class='col-md-2'>Company Name:</label>
	<div class='col-md-10'>
		<input type='text' class='form-control' name='companyname' placeholder='Company Name' required='required'>
	</div>
	</div>
	
	<div class='form-group'>
		<label for="address" class='col-md-2'>Address:</label>
	<div class='col-md-10'>
		<input type='text' class='form-control' name='address' placeholder='Address'>
	</div>
	</div>
	
	<div class='form-group'>
		<label for="email" class='col-md-2'>Email:</label>
	<div class='col-md-10'>
		<input type='text' class='form-control' name='email' placeholder='Email'>
	</div>
	</div>

	<div class='form-group'>
		<label for="phone" class='col-md-2'>Contact Number:</label>
	<div class='col-md-10'>
		<input type='text' class='form-control' name='phone' placeholder='Contact Number'>
	</div>
	</div>

	<div class='form-group'>
		<label for="contactperson" class='col-md-2'>Contact Person:</label>
	<div class='col-md-10'>
		<input type='text' class='form-control' name='contactperson' placeholder='Contact Person'>
	</div>
	</div>


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