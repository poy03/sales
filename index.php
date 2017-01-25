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




include 'db.php';
// echo APP_VERSION;
// echo md5("admin\'");

	$timezone  = 0; //(GMT -5:00) EST (U.S. & Canada) 
	  $datenow=date("m/d/Y", time() + 3600*($timezone+date("I")));
	  $timenow=date("h:i:s A", time() + 3600*($timezone+date("I")));
	  // echo $timenow;

// var_dump(strtotime("11/17/2016"));
// var_dump(array("asdad"=>array("asdasdad"=>array("asdasad"=>"asdasdasd"))));
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title><?php echo $app_name; ?> - Home</title>
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
  .popover{
    width:100%;   
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
		z-/:5;
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

	?>

		<div class='jumbotron' style='padding-top:5px;'>
			<h2 style='text-align:center;'><b>Welcome to <?php echo "$app_name, $employee_name!";?></b></h2>
			<div class='row text-center' style='font-size:150%;'>
			
			<?php
			function display_modules($module,$badge){
				($badge==0?$badge="":$badge=$badge);
				switch ($module) {
					case 1:
						return "
							 <div class='col-md-3'><a href='item'><br><span class = 'glyphicon glyphicon-briefcase'></span> Items <span class = 'badge'>$badge</span></a>
							 <br>
							 - View Items<br>
							 - Add Items<br>
							 - Edit Items<br>
							 - Delete Items
							 </div>
						";
						# code...
						break;
					case 2:
						return "
							 <div class='col-md-3'><a href='customer'><br><span class = 'glyphicon glyphicon-user'></span> Customers</a>
							 <br>
							 - View Customers<br>
							 - Add Customers<br>
							 - Edit Customers<br>
							 - Delete Customers
							 </div>	
						";
						# code...
						break;
					case 3:
					return "
						<div class='col-md-3'><a href='sales'><br><span class = 'glyphicon glyphicon-shopping-cart'></span> Sales <span class = 'badge'>$badge</span></a>
						<br>
						- Add Sales<br>
						- Cancel Sales<br>
						- Receive Payments
						</div> ";
						# code...
						break;
					case 4:
						return "
						 <div class='col-md-3'><a href='receiving'><br><span class = 'glyphicon glyphicon-download-alt'></span> Receiving <span class = 'badge'>$badge</span></a>
						 			 <br>
						 - Receive Items<br>
						 </div>	
						 ";					

							# code...
							break;
					case 5:
						return "
						 <div class='col-md-3'><a href='users'><br><span class = 'glyphicon glyphicon-user'></span> Users</a>
						 <br>
						 - Add Users<br>
						 - Edit Users<br>
						 - Delete Users
						 </div>	
						";
						# code...
						break;
					case 6:
						return "
						 <div class='col-md-3'><a href='reports'><br><span class = 'glyphicon glyphicon-stats'></span> Reports</a>
						 <br>
						 - View Expenses<br>
						 - View Receiving Cost<br>
						 - View Sales<br>
						 - View Reports
						 </div>	
						";					

						# code...
						break;
					case 7:
						return "
							<div class='col-md-3'><a href='suppliers'><br><span class='glyphicon glyphicon-phone'></span> Suppliers</a>
							<br>
							- View Suppliers<br>
							- Add Suppliers<br>
							- Edit Suppliers<br>
							- Delete Suppliers
							</div>
						";					

						# code...
						break;
					case 8:
						return "
						 <div class='col-md-3'><a href='credits'><br><span class = 'glyphicon glyphicon-copyright-mark'></span> Credits <span class = 'badge'>$badge</span></a>
						 			 <br>
						 - Manage Credits<br>
						 - Receive Payments
						 </div>	
						";
						# code...
						break;
					case 9:
						return "
						 <div class='col-md-3'><a href='expenses'><br><span class = 'glyphicon glyphicon-usd'></span> Expenses</a>
						 <br>
						 - Add Expenses<br>
						 - Edit Expenses<br>
						 - Delete Expenses
						 </div>
						";
						# code...
						break;
					case 10:
						return "
						 <div class='col-md-3'><a href='maintenance'><br><span class = 'glyphicon glyphicon-hdd'></span> Maintenance</a>
						 <br>
						 - Backups Data<br>
						 - Restore Data<br>
						 - Delete a Backups<br>
						 - Download a Backups<br>
						 </div>
						";
						# code...
						break;
					case 11:
						return "
						 <div class='col-md-3'><a href='settings'><br><span class = 'glyphicon glyphicon-cog'></span> Settings</a>
						 <br>
						 - Edit Preferences<br>
						 - Edit Application Name<br>
						 - Edit Company Name<br>
						 - Edit Payment Types
						 </div>
						";
						# code...
						break;
					default:
						return "";
						# code...
						break;
				}
			}
			// echo $total_modules;
			for ($i=0; $i < $total_modules; $i++) { //change to zero
				if($i%4==0){
					echo "<div class='row'>";
					// echo "row";
					// echo "<br>";
					// echo "</div>";
					// echo display_modules($i,1);
				}
				if($list_modules[$i]==1){
					$badge_arg = $badge_i;
				}elseif($list_modules[$i]==3){
					$badge_arg = $badge;
				}elseif($list_modules[$i]==4){
					$badge_arg = $badge_r;
				}elseif($list_modules[$i]==8){
					$badge_arg = $badge_credit;
				}else{
					$badge_arg = 0;
				}
				echo display_modules($list_modules[$i],$badge_arg);
				if($i%4==3){
					// echo "<div class='row'>";
					echo "</div>";
					// echo "end row";
					// echo "<br>";
				}
				if($i==$total_modules-1){
					echo "</div>";
				}
			}


			?>



		</div>
	
	<?php
	}else{

	$user_query = mysql_query("SELECT * FROM tbl_users WHERE deleted='0'");
	if(mysql_num_rows($user_query)!=0){
		echo "
		<div class='col-md-5 col-md-offset-3'>
			<label>Login:</label>
			<form action='login' role='form' method='post'>
			<input type='text' name='username' placeholder='Login Name' class='form-control'>
			<input type='Password' name='password' placeholder='Password' class='form-control'>
			<div class='checkbox'>
				<label><input type='checkbox' name='remember'> Remember Me</label>
			</div>
			<button class='btn btn-primary' name='login' type='submit'>Login
			</button>
			</form>		
		</div>
		";
		
	}else{
		header("location:setup");
	}
	?>


	<?php 
	} ?>
	</div>
  </div>
</div>
</body>
</html>
<?php mysql_close($connect);


?>
  <script>
$("[data-toggle=popover]")
.popover({html:true})
</script>