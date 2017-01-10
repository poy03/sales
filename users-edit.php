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
  <title><?php echo $app_name; ?> - Edit Users</title>
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
		//item ajax search
	$( "#search" ).autocomplete({
      source: 'search-item-all',
	  select: function(event, ui){
		  window.location='item?s='+ui.item.data;
	  }
    });
		
  });
  </script>
    <style>

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
	if($users=='1'){
	if(isset($_POST["save"])&&isset($_SESSION["selectusers"])){
		$x = array();

		$x = $_SESSION["selectusers"];

		$type = $_POST["type"];
		$employee_name = $_POST["employee_name"];
		$items = $_POST["items"];
		$customers = $_POST["customers"];
		$sales = $_POST["sales"];
		$receiving = $_POST["receiving"];
		$users = $_POST["users"];
		$reports = $_POST["reports"];
		$expenses = $_POST["expenses"];
		$suppliers = $_POST["reports"];
		$credits = $_POST["credits"];
		$dbpassword = $_POST["password"];
		$dbusername = $_POST["username"];

		$i = 0;
		
		foreach($x as $itemID){
			if($type[$i]=='admin'){
				$items[$i] = $customers[$i] = $sales[$i] = $receiving[$i] = $users[$i] = $reports[$i] = $suppliers[$i] = $credits[$i] = $expenses[$i] ='1';
			}


			$dbusername[$i] = mysql_real_escape_string(htmlspecialchars(trim($dbusername[$i])));
			$dbpassword[$i] = mysql_real_escape_string(htmlspecialchars($dbpassword[$i]));
			$employee_name[$i] = mysql_real_escape_string(htmlspecialchars(trim($employee_name[$i])));

			$searchquery = mysql_query("SELECT * FROM tbl_users WHERE username='$dbusername[$i]' AND deleted='0' AND accountID!='$itemID'");
			if(mysql_num_rows($searchquery)<1){
				$accout_query = mysql_query("SELECT * FROM tbl_users WHERE accountID='$itemID'");
				$accout_data = mysql_fetch_assoc($accout_query);
				// echo $accout_data["password"];
				// echo "<br>".$dbpassword[$i]."<br>";
				if($accout_data["password"]!=$dbpassword[$i]){
				mysql_query("UPDATE tbl_users SET
				password='".md5($dbpassword[$i])."',
				username='$dbusername[$i]',
				type='$type[$i]',
				employee_name='$employee_name[$i]',
				items='$items[$i]',
				customers='$customers[$i]',
				sales='$sales[$i]',
				receiving='$receiving[$i]',
				users='$users[$i]',
				suppliers='$suppliers[$i]',
				credits='$credits[$i]',
				reports='$reports[$i]',
				expenses='$expenses[$i]'
				WHERE accountID = '$itemID'");						
				}else{
				mysql_query("UPDATE tbl_users SET
				username='$dbusername[$i]',
				type='$type[$i]',
				employee_name='$employee_name[$i]',
				items='$items[$i]',
				customers='$customers[$i]',
				sales='$sales[$i]',
				receiving='$receiving[$i]',
				users='$users[$i]',
				suppliers='$suppliers[$i]',
				credits='$credits[$i]',
				reports='$reports[$i]',
				expenses='$expenses[$i]'
				WHERE accountID = '$itemID'");
								}

			}

			$i++;
		}
			echo "
				<div class = 'alert alert-success alert-dismissable'>
				   <button type = 'button' class = 'close' data-dismiss = 'alert' aria-hidden = 'true'>
					  &times;
				   </button>
					
				   <center><strong>Saved!</strong> Selected Users are updated.<a href='users'>Back to Users.</a></center>
				</div>
				";
				unset($_SESSION["selectusers"]);
				exit;
	}
	?>
	<form action="users-edit" method='post'>


	<div class='table-responsive col-md-12'>
	<table class='table'>
	 <thead>
	  <tr>
	   <th>Previlages</th>
	   <th>Display Name</th>
	   <th>Login Name</th>
	   <th>Password</th>
	   <th>Items</th>
	   <th>Customers</th>
	   <th>Sales</th>
	   <th>Suppliers</th>
	   <th>Receiving</th>
	   <th>Users</th>
	   <th>Reports</th>
	   <th>Credits</th>
	   <th>Expenses</th>

	  </tr>
	 </thead>
	 <tbody>
	 <?php
		if(!isset($page)){$page=1;}elseif($page<=0){$page=1;}
		$maxitem = 50; // maximum items
		$limit = ($page*$maxitem)-$maxitem;
	 $query = "SELECT * FROM tbl_users";
	 $x = $_SESSION["selectusers"];
	 //var_dump($x);
	 if($x!=NULL){
	 $cnt = 0;
	 foreach($x as $dbaccountID){
		 if($cnt==0){
			 $query.=" WHERE accountID = $dbaccountID";
		 }else{
			 $query.=" OR accountID = $dbaccountID";
		 }
		 $cnt++;
	 }
	 }else{
		 header("location:users");
	 }
	 $query.=" ORDER BY accountID";
	 $numitemquery = mysql_query($query);
	 $numitem = mysql_num_rows($numitemquery);
	 //echo $query;
	 
	 $itemquery = mysql_query($query);
	 if(mysql_num_rows($itemquery)!=0){
		 while($itemrow=mysql_fetch_assoc($itemquery)){
			 $dbusername = $itemrow["username"];
			 $dbpassword = $itemrow["password"];
			 $dbtype = $itemrow["type"];
			 $dbemployee_name = $itemrow["employee_name"];
			 $dbitems = $itemrow["items"];
			 $dbcustomers = $itemrow["customers"];
			 $dbsales = $itemrow["sales"];
			 $dbsuppliers = $itemrow["suppliers"];
			 $dbreceiving = $itemrow["receiving"];
			 $dbusers = $itemrow["users"];
			 $dbreports = $itemrow["reports"];
			 $dbexpenses = $itemrow["expenses"];
			 $dbcredits = $itemrow["credits"];
			 if($dbtype=='user'){
				 $state = "selected='selected'";
				 
			 }else{
				 $state = '';
			 }
			 echo '
			 <tr>
			  <td>
			  <select class="tbl_row" name="type[]">
				<option value="admin">Admin</option>
				<option value="user" $state>User</option>
			  </select>
			  </td>
			  <td><input type="text" name="employee_name[]" value="'.$dbemployee_name.'" required="required"></td>
			  <td><input type="text" name="username[]" value="'.$dbusername.'" required="required"></td>
			  <td><input type="password" name="password[]" value="'.$dbpassword.'" required="required"></td>
			  <td>
			  <select class="tbl_row" name="items[]">
				<option value="0">Deny</option>
				<option value="1" ';
				if($dbitems=='1'){
					echo "selected='selected'";
				}
				echo ">Allow</option>
			  </select>
			  </td>
			  
			  <td>
			  <select class='tbl_row' name='customers[]'>
				<option value='0'>Deny</option>
				<option value='1' ";
				if($dbcustomers=='1'){
					echo "selected='selected'";
				}
				echo ">Allow</option>
			  </select>
			  </td>
			  
			  <td>
			  <select class='tbl_row' name='sales[]'>
				<option value='0'>Deny</option>
				<option value='1' ";
				if($dbsales=='1'){
					echo "selected='selected'";
				}
				echo ">Allow</option>
			  </select>
			  </td>
			  <td>
			  <select class='tbl_row' name='suppliers[]'>
				<option value='0'>Deny</option>
				<option value='1' ";
				if($dbsuppliers=='1'){
					echo "selected='selected'";
				}
				echo ">Allow</option>
			  </select>
			  </td>
			  
			  <td>
			  <select class='tbl_row' name='receiving[]'>
				<option value='0'>Deny</option>
				<option value='1' ";
				if($dbreceiving=='1'){
					echo "selected='selected'";
				}
				echo ">Allow</option>
			  </select>
			  </td>
			  
			  <td>
			  <select class='tbl_row' name='users[]'>
				<option value='0'>Deny</option>
				<option value='1' ";
				if($dbusers=='1'){
					echo "selected='selected'";
				}
				echo ">Allow</option>
			  </select>
			  </td>
			  
			  <td>
			  <select class='tbl_row' name='reports[]'>
				<option value='0'>Deny</option>
				<option value='1' ";
				if($dbreports=='1'){
					echo "selected='selected'";
				}
				echo ">Allow</option>
			  </select>
			  </td>
			  <td>
			  <select class='tbl_row' name='credits[]'>
				<option value='0'>Deny</option>
				<option value='1' ";
				if($dbcredits=='1'){
					echo "selected='selected'";
				}
				echo ">Allow</option>
			  </select>
			  </td>
			    <td>
			    <select class='tbl_row' name='expenses[]'>
			  	<option value='0'>Deny</option>
			  	<option value='1' ";
			  	if($dbexpenses=='1'){
			  		echo "selected='selected'";
			  	}
			  	echo ">Allow</option>
			    </select>
			    </td>	  
			  ";
			  
			 echo "</tr>
			 ";
		 }
	 }
	 ?>
	 </tbody>
	</table>	
	<div class="span7 text-center"><button class='btn btn-primary' type='save' name='save'><span class='glyphicon glyphicon-floppy-disk'></span> Save</button></div>
	<br>
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