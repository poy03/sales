<?php
ob_start();
session_start();
$accountID=@$_SESSION['accountID'];
$page=@$_GET['page'];
$d=@$_GET['d'];
$keyword=@$_GET['keyword'];
$search=@$_GET['search'];
$f=@$_GET['f'];
$t=@$_GET['t'];



include 'db.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title><?php echo $app_name; ?> - Expenses Reports</title>
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

    $(".delete").click(function(e){
    	$("#orderID").val(e.target.id);
    	$("#expenses-modal").modal("show");
    });
	
	$("#expenses-delete-form").submit(function(e){
		e.preventDefault();
		// alert($("#expenses-delete-form").serialize());
		$.ajax({
			type: "POST",
			url: $("#expenses-delete-form").attr("action"),
			data: $("#expenses-delete-form").serialize(),
			cache: false,
			success: function(data){
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
			echo $header->header($module,0,1);
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


	   <!-- Modal -->
	   <div id="expenses-modal" class="modal fade" role="dialog">
	     <div class="modal-dialog">

	       <!-- Modal content-->
	       <div class="modal-content">
	         <div class="modal-header">
	           <button type="button" class="close" data-dismiss="modal">&times;</button>
	           <h4 class="modal-title">Expenses Delete</h4>
	         </div>
	         <div class="modal-body">
	           <p>
	           	<form action="expenses-delete" id="expenses-delete-form" method="post">
	           		<label>Reason for Deleting:</label>
	           		<input type="hidden" name="orderID" id="orderID">
	           		<textarea name="deleted_comment" class="form-control" required></textarea>
	           	</form>
	           </p>
	         </div>
	         <div class="modal-footer">
	           <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	           <button type="submit" form="expenses-delete-form" class="btn btn-primary">Confirm</button>
	         </div>
	       </div>

	     </div>
	   </div>


<div class="container-fluid">
  <div class='row'>
  	<div class='col-md-12'>
	
	
	<?php
	if($logged==1||$logged==2){
	if($reports=='1'){
		if(isset($_GET["all"])){

		}else{
			echo "<center><h3>Expenses in ".date("F d, Y - ",strtotime($f)).date("F d, Y",strtotime($t))."</h3></center>";
		}
	?>
	<div class='table-responsive'>
	<button class="btn btn-primary prints" onclick="window.print()"><span class="glyphicon glyphicon-print"></span> Print</button>
	<table class='table table-responsive'>
	<thead>
		<tr>
			<th style='text-align:center;'>Expense Description</th>
			<th style='text-align:left;'>Date</th>
			<th style='text-align:left;'>Time</th>
			<th style='text-align:right'>Expense Amount</th>
			<th style='text-align:center'>Comments</th>
		</tr>
	</thead>
	<tbody>
		<?php
		if(!isset($page)){$page=1;}elseif($page<=0){$page=1;}
		$maxitem = $maximum_items_displayed; // maximum items
		$limit = ($page*$maxitem)-$maxitem;


		$query = "SELECT * FROM tbl_orders_expenses WHERE deleted='0'";
		if(isset($_GET["all"])){
		}else{
			$query .=" AND date_of_expense BETWEEN '".strtotime($f)."' AND '".strtotime($t)."'";

		}
		$numitemquery = mysql_query($query);
		$numitem = mysql_num_rows($numitemquery);
		$query.=" LIMIT $limit, $maxitem";
		// echo $query;

 		if(($numitem%$maxitem)==0){
			$lastpage=($numitem/$maxitem);
		}else{
			$lastpage=($numitem/$maxitem)-(($numitem%$maxitem)/$maxitem)+1;
		}
		$maxpage = 3;


		$expenses_query = mysql_query($query);
		$total_expenses = 0;
		if(mysql_num_rows($expenses_query)!=0){
			while($expenses_row=mysql_fetch_assoc($expenses_query)){
				$time_expended=$expenses_row["time_expended"];
				$orderID=$expenses_row["orderID"];
				$date_of_expense=$expenses_row["date_of_expense"];
				$description=htmlspecialchars_decode($expenses_row["description"]);
				$expenses=$expenses_row["expenses"];
				$comments=$expenses_row["comments"];
				$total_expenses+=$expenses;
				echo "
				<tr>
					<td>".$description."</td>
					<td>".date("m/d/Y",$date_of_expense)."</td>
					<td>".date("h:i:s A",$time_expended)."</td>
					<td style='text-align:right'>₱".number_format($expenses,2)."</td>
					<td style='text-align:center'>$comments</td>
					<td style='text-align:center'><a href='#' class='delete' id='$orderID'>&times</td>
				</tr>
				";
			}
		}
		?>
	</tbody>
	<tfoot>
	<?php
	if(mysql_num_rows($expenses_query)!=0){ 
		echo "
		<tr>
			<th style='text-align:right' colspan='4'>₱".number_format($total_expenses,2)."</th>
			<th style='text-align:right'></th>
		<tr>
		";
	}
	?>
	</tfoot>
	</table>
	<?php 
		echo "
	<div class='text-center'>
		<ul class='pagination prints'>
		
		";
		// $url="?cat=$cat&sort=$sort&";
		if(isset($_GET["all"])){
			$all = $_GET["all"];
			$url="?f=$f&t=$t&all=$all&submit=&";
		}else{
			$url="?f=$f&t=$t&submit=&";
		}
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
		echo "</ul><span class='page' >Page $page</span>
		</div>
		";
		

	 ?>
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