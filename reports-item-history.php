<?php
ob_start();
session_start();
$accountID=@$_SESSION['accountID'];
$page=@$_GET['page'];
$f=@$_GET['f'];
$t=@$_GET['t'];
$by=@$_GET['by'];
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
  <title><?php echo $app_name; ?> - Sales Reports</title>
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
   
  <script src="jquery-ui.js"></script>
  <script type="text/javascript" src="js/shortcut.js"></script>
  <link rel="stylesheet" href="/resources/demos/style.css">
  

  <style>
  .item:hover{
	  cursor:pointer;
  }
  
  </style>
  <script>
		$(document).ready(function(){
			$("#date_from").datepicker();
			$("#date_to").datepicker();
			$("#by").change(function(){
				var date_from = $("#date_from").val();
				var date_to = $("#date_to").val();
				var by = $(this).val();
				window.location= "reports?by="+by+"&f="+date_from+"&t="+date_to;
			});
			
		$( "#search" ).autocomplete({
      source: 'search-item-all',
	  select: function(event, ui){
		  window.location='item?s='+ui.item.data;
	  }
    });
	

	$( "#serial_number-search" ).autocomplete({
      source: 'search-item-serial-number-all',
	  select: function(event, ui){
		  $("#serial_number").val(ui.item.data);
	  }
    });

	$( "#item_name-search" ).autocomplete({
      source: 'search-item-history',
	  select: function(event, ui){
		  $("#item_name").val(ui.item.data);
	  }
    });
	
	$( "#category-search" ).autocomplete({
      source: 'search-item-category-history',
	  select: function(event, ui){
		  $("#category").val(ui.item.data);
	  }
    });
	
	$( "#reference_number" ).autocomplete({
      source: 'search-item-history-ref-number'
    });

    $("#item-history-form").submit(function(e) {
    	e.preventDefault();
    	show_history();
    });
  
	show_history();



  });

  $(document).on("click",".paging",function(e) {
  	show_history(e.target.id);
  });



  function show_history(page=1) {
  	// alert($("#item-history-form :input").serialize());
  	$.ajax({
  		type: "POST",
  		url: $("#item-history-form").attr("action"),
  		data: $("#item-history-form :input").serialize()+"&page="+page,
  		cache: false,
  		success: function(data){
  			$("#item-history-table tbody").html(data);
  		}
  	});
  }
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
  	<div class='col-md-12'>
	
	
	<?php
	if($logged==1||$logged==2){
	if($reports=='1'){ ?>

	<div class="table-responsive">
		<form action="reports-item-history-ajax" method="post" id="item-history-form">
		<input type="text" id="serial_number-search" placeholder="Serial Number">
		<input type="text" id="item_name-search" placeholder="Item Name">
		<input type="text" id="category-search" placeholder="Category">
		<input type="text" id="reference_number" name="reference_number" placeholder="Ref #">

		<input type="text" id="date_from" name="date_from" placeholder="Date From">
		<input type="text" id="date_to" name="date_to" placeholder="Date To">
		<input type="hidden" id="serial_number" name="serial_number" placeholder="Serial Number">
		<input type="hidden" id="item_name" name="item_name" placeholder="Item Name">
		<input type="hidden" id="category" name="category" placeholder="Item Name">
		<button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-search"></span> Search</button>

		</form>
		<table class="table table-responsive" id="item-history-table">
			<thead>
				<tr>
					<th>Date</th>
					<th>Type</th>
					<th>Category</th>
					<th>Item Name</th>
					<th>Serial Number</th>
					<th>Description</th>
					<th>Quantity</th>
					<th>Ref #</th>
					<th>ID</th>
					<th>User</th>
				</tr>
			</thead>
			<tbody>

			</tbody>
		</table>



	</div>
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