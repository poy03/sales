<?php
ob_start();
session_start();
$accountID=@$_SESSION['accountID'];
$page=@$_GET['page'];
$tab=@$_GET['tab'];
$keyword=@$_GET['keyword'];
$search=@$_GET['search'];
			$by=@$_GET['by'];
			$order=@$_GET['order'];

#$connect = mysql_connect("localhost","qfcdavao_admin","_39a11nwpm");
#mysql_select_db("qfcdavao_dbinventory");

include 'db.php';
include 'importer.php';


?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title><?php echo $app_name; ?> - Settings</title>
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
		//item ajax search
	$( "#search" ).autocomplete({
      source: 'search-item-all',
	  select: function(event, ui){
		  window.location='item?s='+ui.item.data;
	  }
    });

    $("#settings-form").submit(function(e){
    	e.preventDefault();
    	$.ajax({
    		type: "POST",
    		url: $("#settings-form").attr("action"),
    		data: $("#settings-form :input").serialize()+"&save=1",
    		cache: false,
    		success: function(data){
    			alert("Success! Changes are Saved.");
    			location.reload();
    		}

    	});
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
	?>

	<?php
	
		echo "
		<form action='settings-form' class='form-horizontal' method='post' enctype='multipart/form-data' id='settings-form'>";
			?>
			<div class='col-md-2'>
			<button class='btn btn-primary btn-block' type='submit' name='save'>Save</button>
			</div>
			<div class='col-md-10'>
			<div class='form-group'>
			<label for='app_name' class='col-md-2'>Application Name:</label>
			<div class='col-md-5'>
			<input type='text' class='form-control' name='app_name' value="<?php			
			echo $app_name; ?>">
			</div>
			</div>
			
			<div class='form-group'>
			<label for='app_company_name' class='col-md-2'>Company Name:</label>
			<div class='col-md-5'>
			<input type='text' class='form-control' name='app_company_name' value="<?php			
			echo $app_company_name; ?>">
			</div>
			</div>
			
			
			<div class='form-group'>
			<label for='address' class='col-md-2'>Address:</label>
			<div class='col-md-5'>
			<input type='text' class='form-control' name='address' value="<?php			
			echo $address; ?>">
			</div>
			</div>
			
			<div class='form-group'>
			<label for='contact_number' class='col-md-2'>Contact Number:</label>
			<div class='col-md-5'>
			<input type='text' class='form-control' name='contact_number' value="<?php			
			echo $contact_number; ?>">
			</div>
			</div>
			
			<div class='form-group'>
			<label for='type_of_payments' class='col-md-2'>Type of Payments:</label>
			<div class='col-md-5'>
			<input type='text' class='form-control' name='type_payment' value="<?php			
			echo $type_payment; ?>" required>
			<i style='font-size:75%;'>* Separated by Commas(,).</i>
			</div>
			</div>
			
			<div class='form-group'>
			<label for='type_of_payments' class='col-md-2'>Maximum Items Displayed:</label>
			<div class='col-md-5'>
			<input type='number' min='1' max='100000' required='required' class='form-control' name='maximum_items_displayed' value="<?php echo $maximum_items_displayed; ?>">
			<i style="font-size:75%;">* Applies in all table.</i>
			
			</div>
			</div>
			
			<div class='form-group'>
			<label for='type_of_payments' class='col-md-2'>Logo:</label>
			<div class='col-md-5'>
			<input type='file' name='logo' accept="image/*" capture="camera">
			<i style='font-size:75%;'>* .JPG,.PNG,.GIF Allowed.</i>
			
			</div>
			</div>
			
			
			<div class='form-group'>
			<label for='themes' class='col-md-2'>Select Theme:</label>
			<div class='col-md-5'>
			<select name='themes' class='form-control'>
				<option value='bootstrap.min.css' <?php if($themes=='bootstrap.min.css'){echo "selected='selected'";} ?> >Default Theme</option>
				<option value='cosmo-bootstrap.min.css' <?php if($themes=='cosmo-bootstrap.min.css'){echo "selected='selected'";} ?> >Cosmo Theme</option>
				<option value='cerulean-bootstrap.min.css' <?php if($themes=='cerulean-bootstrap.min.css'){echo "selected='selected'";} ?> >Cerulean Theme</option>
				<option value='simplex-bootstrap.min.css' <?php if($themes=='simplex-bootstrap.min.css'){echo "selected='selected'";} ?> >Simplex Theme</option>
				<option value='lumen-bootstrap.min.css' <?php if($themes=='lumen-bootstrap.min.css'){echo "selected='selected'";} ?> >Lumen Theme</option>
				<option value='flatly-bootstrap.min.css' <?php if($themes=='flatly-bootstrap.min.css'){echo "selected='selected'";} ?> >Flatly Theme</option>
				<option value='sandstone-bootstrap.min.css' <?php if($themes=='sandstone-bootstrap.min.css'){echo "selected='selected'";} ?> >Sandstone Theme</option>
				<option value='united-bootstrap.min.css' <?php if($themes=='united-bootstrap.min.css'){echo "selected='selected'";} ?> >United Theme</option>
				<option value='spacelab-bootstrap.min.css' <?php if($themes=='spacelab-bootstrap.min.css'){echo "selected='selected'";} ?> >Spacelab Theme</option>
			</select>
			</div>
			</div>
			
			</div>

			
			<?php
		echo "</form>";
	
	?>
	
	
	
	<?php
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