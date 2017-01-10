<?php
ob_start();
session_start();
$accountID=@$_SESSION['accountID'];
$page=@$_GET['page'];
$cat=@$_GET['cat'];
$sort=@$_GET['sort'];
if(!isset($sort)){
	$sort = "A-Z";
}
$keyword=@$_GET['s'];
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
  <title><?php echo $app_name; ?> - Items</title>
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
  <script src="js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="themes/smoothness/jquery-ui.css">
   
  <script src="jquery-ui.js"></script>
  <script type="text/javascript" src="js/shortcut.js"></script>
  <style>
  .item:hover{
	  cursor:pointer;
  }
  </style>
  <link rel="stylesheet" href="css/theme.default.min.css">
  <script type="text/javascript" src="js/jquery.tablesorter.js"></script> 
  <script>
  $(document).ready(function(){
	  
	$( "#search" ).autocomplete({
      source: 'search-item-all',
	  select: function(event, ui){
		  window.location='item?s='+ui.item.data;
	  }
    });

	
  });

  $(document).on("submit","#item-detail-form",function(e) {
  	e.preventDefault();
  	$.ajax({
  		type: "POST",
  		data: $("#item-detail-form :input").serialize(),
  		url: $("#item-detail-form").attr("action"),
  		cache: false,
  		dataType: "json",
  		success: function(data){
  			$("#"+data.item_detail_id).html(data.serial_number);
  			alert("Success!");
			$("#edit-modal").modal("hide");
  		}
	  });
  });


  $(document).on("click",".edit",function(e) {
  	$.ajax({
  		type: "POST",
  		data: "id="+e.target.id,
  		url: "item-detail-info",
  		cache: false,
  		dataType: "json",
  		success: function(data){
  			$("#edit-modal").modal("show");
  			$(".modal-title").html(data.title);
  			$("#serial_number").val(data.serial_number);
  			$("#item_detail_id").val(data.item_detail_id);
  		}
  	});
  });
  
  </script>
  
    <style>

	.tablesorter-default{
		font:20px/20px;
		font-weight:1200;
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
					Hi <?php echo $employee_name; ?></a>
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
	if($items==1){

		$itemID = $_GET["id"];
		$item_query = mysql_query("SELECT * FROM tbl_items WHERE itemID='$itemID' AND has_serial='1'");
		if(mysql_num_rows($item_query)==0){
			header("location:item");
		}
		$item_data = mysql_fetch_assoc($item_query);
		?>

		<div class="table-responsive">
			<table class="table table-hover">
				<thead>
					<tr>
						<th>Category</th>
						<th>Item Name</th>
						<th>Serial Number</th>
<!-- 						<th style="text-align: center;">Cost Price</th>
						<th style="text-align: center;">SR Price</th>
						<th style="text-align: center;">Dealers Price</th> -->
						<th></th>
					</tr>
				</thead>
				<tbody>
				<?php
				$item_detail_query = mysql_query("SELECT * FROM tbl_items_detail WHERE itemID='$itemID' AND deleted='0' AND quantity!='0'");
				if(mysql_num_rows($item_detail_query)!=0){
					while($item_detail_row=mysql_fetch_assoc($item_detail_query)){
						echo "
						<tr>
							<td>".$item_data["category"]."</td>
							<td>".$item_data["itemname"]."</td>
							<td><span id='".$item_detail_row["item_detail_id"]."'>".$item_detail_row["serial_number"]."</span></td>
							<!--<td style='text-align:right'>".number_format($item_data["costprice"],2)."</td>
							<td style='text-align:right'>".number_format($item_data["srp"],2)."</td>
							<td style='text-align:right'>".number_format($item_data["dp"],2)."</td> -->
							<td><a href='#' class='edit' id='".$item_detail_row["item_detail_id"]."'>Edit</a></td>
						</tr>
						";
					}
				}

				?>
				</tbody>
			</table>
		</div>

		<!-- Modal -->
		<div id="edit-modal" class="modal fade" role="dialog">
		  <div class="modal-dialog">

		    <!-- Modal content-->
		    <div class="modal-content">
		      <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal">&times;</button>
		        <h4 class="modal-title">Modal Header</h4>
		      </div>
		      <div class="modal-body">
		        <form action="item-details-edit" method="post" id="item-detail-form">
		        	<label>Serial Number:</label>
		        	<input type="text" class="form-control" placeholder="Serial Number" id="serial_number" name="serial_number">
		        	<input type="hidden" id="item_detail_id" name="item_detail_id">
		        </form>
		      </div>
		      <div class="modal-footer">
		        <button type="submit" form="item-detail-form" class="btn btn-primary"><span class="glyphicon glyphicon-floppy-disk"></span> Save</button>
		        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		      </div>
		    </div>

		  </div>
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