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
  <title><?php echo $app_name; ?> - Add Users</title>
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
  <link rel="stylesheet" href="css/theme.default.min.css">
  <script type="text/javascript" src="js/jquery.tablesorter.js"></script> 
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
	   $("#myTable").tablesorter();
	   $("#myTable").tablesorter( {sortList: [[1,0], [0,0]]} );
$("#add").click(function(){
	window.location = "users-add";
});

$("#type").change(function(){
	var type=$(this).val();
	if(type=='user'){
		$(":checkbox").each(function(){
			this.checked = false;
			$(".module").removeAttr("disabled");			
		});
	}else{
		$(":checkbox").each(function(){
			this.checked = true;    
			$(".module").attr("disabled","disabled");			
		});
	}
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
			$accquery
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
	if($users=='1'){
	if(isset($_POST["edit"])){
		$x = array();
		$x = $_POST["select"];
		$_SESSION["selectusers"]=$x;
		header("location:users-edit");
	}


	if(isset($_POST["delete"])){
		$x = array();
		$x = @$_POST["select"];
		$_SESSION["selectusers"]=$x;
		if($x!=NULL){ 
		?>
		<script type="text/javascript">
			$(document).ready(function() {
				 var conf = confirm("Are you sure you want to delete selected items?");
				 if(conf==true){
					 window.location="users-delete";
				 }
			});
		</script>
		<?php

		}
	}
	if(isset($_POST["save"])){
		$type = $_POST["type"];
		$username = mysql_real_escape_string(htmlspecialchars(trim($_POST["username"])));
		$password = mysql_real_escape_string(htmlspecialchars(trim($_POST["password"])));
		$name = mysql_real_escape_string(htmlspecialchars(trim($_POST["name"])));
		$items = @$_POST["items"];
		$customers = @$_POST["customers"];
		$expenses = @$_POST["expenses"];
		$sales = @$_POST["sales"];
		$receiving = @$_POST["receiving"];
		$users = @$_POST["users"];
		$reports = @$_POST["reports"];
		$suppliers = @$_POST["suppliers"];
		$credits = @$_POST["credits"];
		if($type=='admin'){
			$items = $customers = $sales = $receiving = $users = $reports = $suppliers = $credits = $expenses =  '1';
		}
		$searchquery = mysql_query("SELECT * FROM tbl_users WHERE username='$username' AND deleted='0'");
		if(mysql_num_rows($searchquery)==0){
			mysql_query("INSERT INTO tbl_users VALUES('','$username','".md5($password)."','$type','$name','','','$items','$customers','$sales','$receiving','$users','$reports','$suppliers','$credits','$expenses')");
			header("location:users");
		}else{
					echo "
				<div class = 'alert alert-danger alert-dismissable'>
				   <button type = 'button' class = 'close' data-dismiss = 'alert' aria-hidden = 'true'>
					  &times;
				   </button>
				   <center><strong>$username</strong> is already exist.<center>
				</div>
				";
		}
	}
	?>
	
	
	<form action='users-add' method='post' class='form-horizontal'>
	<div class='col-md-2'>
		<span><b>Controls:</b></span>	
	<button class='btn btn-primary btn-block' name='save'><span class='glyphicon glyphicon-floppy-disk'></span> Save</button>
	</div>
	<div class='col-md-10' >
	
	<div class='form-group'>
	<label for='type' class='col-md-2'>Previlages:</label>
	<div class='col-md-10'>
	<select class='form-control' name='type' id='type'>
		<option value='user'>User</option>
		<option value='admin'>Admin</option>
	</select>
	</div>
	</div>
	
	
	<div class='form-group'>
	<label for='username' class='col-md-2'>Username:</label>
	<div class='col-md-10'>
	<input type='text' name='username' placeholder='Username' class='form-control' autocomplete="off" required='required'>
	</div>
	</div>
	
	<div class='form-group'>
	<label for='password' class='col-md-2'>Password:</label>
	<div class='col-md-10'>
	<input type='password' name='password' placeholder='Password' class='form-control' autocomplete="off" required='required'>
	</div>
	</div>
	
	<div class='form-group'>
	<label for='name' class='col-md-2'>Full Name:</label>
	<div class='col-md-10'>
	<input type='text' name='name' placeholder='Full Name' class='form-control' autocomplete="off" required='required'>
	</div>
	</div>	
	
	<div id='admin'>
	<span><b>Access to modules:</b></span>
	<div class='col-md-12'>
	<div class="checkbox col-md-2">
      <label><input type='checkbox' name='items' value='1' class='module'>Items</label>
    </div>
	<div class="checkbox col-md-2">
      <label><input type='checkbox' name='customers' value='1' class='module'>Customers</label>
    </div>
	<div class="checkbox col-md-2">
      <label><input type='checkbox' name='sales' value='1' class='module'>Sales</label>
    </div>
	<div class="checkbox col-md-2">
      <label><input type='checkbox' name='receiving' value='1' class='module'>Receiving</label>
    </div>
	<div class="checkbox col-md-2">
      <label><input type='checkbox' name='suppliers' value='1' class='module'>Suppliers</label>
    </div>	
	<div class="checkbox col-md-2">
      <label><input type='checkbox' name='users' value='1' class='module'>Users</label>
    </div>
	<div class="checkbox col-md-2">
      <label>	<input type='checkbox' name='reports' value='1' class='module'>Reports</label>
    </div>
	<div class="checkbox col-md-2">
      <label>	<input type='checkbox' name='credits' value='1' class='module'>Credits</label>
    </div>
	<div class="checkbox col-md-2">
      <label>	<input type='checkbox' name='expenses' value='1' class='module'>Expenses</label>
    </div>	
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