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
  <title><?php echo $app_name; ?> - Edit Customers</title>
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
	if(isset($_POST["save"])&&isset($_SESSION["selectcustomer"])){
		$x = array();
		$x = $_SESSION["selectcustomer"];
		$companyname = $_POST["companyname"];
		$address = $_POST["address"];
		$email = $_POST["email"];
		$phone = $_POST["phone"];
		$contactperson = $_POST["contactperson"];
		$i = 0;
		foreach($x as $customerID){


			$companyname[$i] = mysql_real_escape_string(htmlspecialchars(trim($companyname[$i])));
			$address[$i] = mysql_real_escape_string(htmlspecialchars(trim($address[$i])));
			$email[$i] = mysql_real_escape_string(htmlspecialchars(trim($email[$i])));
			$phone[$i] = mysql_real_escape_string(htmlspecialchars(trim($phone[$i])));
			$contactperson[$i] = mysql_real_escape_string(htmlspecialchars(trim($contactperson[$i])));


			$customer_query = mysql_query("SELECT * FROM tbl_customer WHERE companyname='$companyname[$i]' AND contactperson='$contactperson[$i]'");

			mysql_query("UPDATE tbl_customer SET
			companyname = '$companyname[$i]',
			address = '$address[$i]',
			email = '$email[$i]',
			phone = '$phone[$i]',
			contactperson = '$contactperson[$i]'
			WHERE customerID = '$customerID'");
			
			$i++;
		}
		echo "
				<div class = 'alert alert-success alert-dismissable' style='text-align:center'>
				   <button type = 'button' class = 'close' data-dismiss = 'alert' aria-hidden = 'true'>
					  &times;
				   </button>
					
				   <strong>Saved! </strong>Selected Customers are updated. <a href='customer'>Back to Customers.</a>
				</div>
				";
				unset($_SESSION["selectcustomer"]);
		exit;
	}
	?>
	<form action="customer-edit" method='post'>


	<div class='table-responsive col-md-12'>
	<table class='table'>
	 <thead>
	  <tr>
	   <th>Company Name</th>
	   <th>Address</th>
	   <th>Email</th>
	   <th>Phone</th>
	   <th>Contact Person</th>
	  </tr>
	 </thead>
	 <tbody>
	 <?php
		if(!isset($page)){$page=1;}elseif($page<=0){$page=1;}
		$maxitem = 50; // maximum items
		$limit = ($page*$maxitem)-$maxitem;
	 $query = "SELECT * FROM tbl_customer";
	 $x = $_SESSION["selectcustomer"];
	 if($x!=NULL){
	 $cnt = 0;
	 foreach($x as $customerID){
		 if($cnt==0){
			 $query.=" WHERE customerID = $customerID";
		 }else{
			 $query.=" OR customerID = $customerID";
		 }
		 $cnt++;
	 }
	 }else{
		 header("location:customer");
	 }
	 $query.=" ORDER BY companyname";
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
			 $dbcompanyname = $itemrow["companyname"];
			 $dbaddress = $itemrow["address"];
			 $dbemail = $itemrow["email"];
			 $dbphone = $itemrow["phone"];
			 $dbcontactperson = $itemrow["contactperson"];
			 echo '
			 <tr>
			  <td><input type="text" name="companyname[]" value="'.$dbcompanyname.'" required="required"></td>
			  <td><input type="text" name="address[]" value="'.$dbaddress.'"></td>
			  <td><input type="text" name="email[]" value="'.$dbemail.'"></td>
			  <td><input type="text" name="phone[]" value="'.$dbphone.'"></td>
			  <td><input type="text" name="contactperson[]" value="'.$dbcontactperson.'"></td>
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