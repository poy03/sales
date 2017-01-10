<?php
ob_start();
session_start();
$accountID=@$_SESSION['accountID'];
$page=@$_GET['page'];
$cat=@$_GET['cat'];
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
  <title><?php echo $app_name; ?> - Suppliers</title>
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
	  $("#myTable").tablesorter();
	   $("#myTable").tablesorter( {sortList: [[1,0], [0,0]]} ); 
	  $("#add").click(function(){
		  window.location="suppliers-add";
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
	
	$("#cat").change(function(){
		var cat = $(this).val();
		window.location = "item?cat="+cat;
	});

	
	$( "#search" ).autocomplete({
      source: 'search-item-all',
	  select: function(event, ui){
		  window.location='item?s='+ui.item.data;
	  }
    });
	
	$( "#suppliers" ).autocomplete({
      source: 'search-supplier',
	  select: function(event, ui){
		  window.location='suppliers?id='+ui.item.data;
	  }
    });
	
  });
  
  </script>
  
    <style>
	#item_results
	{
		position:absolute;
		z-index:10;
		width:250px;
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
	if($suppliers==1){
	if(isset($_POST["edit"])){
		$x = array();
		$x = $_POST["select"];
		$_SESSION["selectsupplier"]=$x;
		header("location:suppliers-edit");
	}


	if(isset($_POST["delete"])){
		$x = array();
		$x = @$_POST["select"];
		$_SESSION["selectsupplier"]=$x;
		if($x!=NULL){ 
		?>
		<script type="text/javascript">
			$(document).ready(function() {
				 var conf = confirm("Are you sure you want to delete selected items?");
				 if(conf==true){
					 window.location="suppliers-delete";
				 }
			});
		</script>
		<?php

		}
	}
	?>
	<form action="suppliers" method='post'>
	<div class='col-md-2'>
	<label>Controls:</label>
	<span class='btn btn-primary btn-block' name='add' id='add'><span class='glyphicon glyphicon-phone'></span> Add Suppliers</span>
	<?php if($type=='admin'){ ?>
	<button class='btn btn-primary btn-block' name='edit' type='submit'><span class='glyphicon glyphicon-edit'></span> Edit Suppliers</button>
	<button class='btn btn-danger btn-block' name='delete' type='submit' id='delete'><span class='glyphicon glyphicon-trash'></span> Delete Suppliers</button>
	<input type='text' placeholder='Search for Supplier' id='suppliers' class='search form-control' style='margin-top:.5em;'>
	<br>
	<?php } ?>
	</div>
	<div class='table-responsive col-md-10'>
	<table class='table table-hover tablesorter tablesorter-default' id='myTable'>
	 <thead>
	  <tr>
	   <th><input type="checkbox" id="select-all" value='all'> All</th>
	   <th>Contact Person</th>
	   <th>Company Name</th>
	   <th>Contact Number</th>
	   <th>Address</th>
	  </tr>
	 </thead>
	 <tbody>
	 <?php
		if(!isset($page)){$page=1;}elseif($page<=0){$page=1;}
		$maxitem = $maximum_items_displayed; // maximum items
		$limit = ($page*$maxitem)-$maxitem;
	 $query = "SELECT * FROM tbl_suppliers WHERE deleted = 0";
	 if(isset($_GET["id"])){
		 $id = $_GET["id"];
		 $query.= " AND supplierID = '$id'";
	 }
	 $query.=" ORDER BY supplier_name";
	 $numitemquery = mysql_query($query);
	 $numitem = mysql_num_rows($numitemquery);
	 $query.=" LIMIT $limit, $maxitem";
	 // echo $query;
	 $itemquery = mysql_query($query);
	 
	 
	 		if(($numitem%$maxitem)==0){
				$lastpage=($numitem/$maxitem);
			}else{
				$lastpage=($numitem/$maxitem)-(($numitem%$maxitem)/$maxitem)+1;
			}
			$maxpage = 3;
			
	 
	 
	 if(mysql_num_rows($itemquery)!=0){
		$i =0;
		$q = @$_POST['select'];
		
		 while($itemrow=mysql_fetch_assoc($itemquery)){
			 $supplierID = $itemrow["supplierID"];
			 $supplier_name = $itemrow["supplier_name"];
			 $supplier_company = $itemrow["supplier_company"];
			 $supplier_number = $itemrow["supplier_number"];
			 $supplier_address = $itemrow["supplier_address"];
			 echo "
			 <tr class='selected'>
			  <td><input type='checkbox' name='select[]' value='$supplierID' class='select' ";
			  if(isset($_POST["select"])){
				  if(in_array($supplierID,$_POST['select'])) echo 'checked';
			  }
			  echo "></td>
			  <td>$supplier_name</td>
			  <td>$supplier_company</td>
			  <td>$supplier_number</td>
			  <td>$supplier_address</td>
			 </tr>
			 ";
	     $i++;
		 }
	 }
	 ?>
	 </tbody>

	</table>
	<div class='text-center'>
<?php
			echo "<ul class='pagination prints'>
			
			";
			$url="?cat=$cat&";
			$cnt=0;
			if($page>1){
				$back=$page-1;
				echo "<li><a href = '".$url."page=1'>&laquo;&laquo;</a></li>";	
				echo "<li><a href = '".$url."page=$back'>&laquo;</a></li>";	
				for($i=($page-$maxpage);$i<$page;$i++){
					if($i>0){
						echo "<li><a href = '".$url."page=$i'>$i</a></li>";	
					}
					$cnt++;
					if($cnt==$maxpage){
						break;
					}
				}
			}
			
			$cnt=0;
			for($i=$page;$i<=$lastpage;$i++){
				$cnt++;
				if($i==$page){
					echo "<li class='active'><a href = '#'>$i</a></li>";	
				}else{
					echo "<li><a href = '".$url."page=$i'>$i</a></li>";	
				}
				if($cnt==$maxpage){
					break;
				}
			}
			
			$cnt=0;
			for($i=($page+$maxpage);$i<=$lastpage;$i++){
				$cnt++;
				echo "<li><a href = '".$url."page=$i'>$i</a></li>";	
				if($cnt==$maxpage){
					break;
				}
			}
			if($page!=$lastpage&&$numitem>0){
				$next=$page+1;
				echo "<li><a href = '".$url."page=$next'>&raquo;</a></li>";
				echo "<li><a href = '".$url."page=$lastpage'>&raquo;&raquo;</a></li>";
			}
			echo "</ul><span class='page' >Page $page</span></div>";
			
			?>
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