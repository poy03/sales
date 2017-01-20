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
  <title><?php echo $app_name; ?> - Sales Reports per Item</title>
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
  @media screen{

  	#hide{
  		display: none;
  	}
}
  @media print{
		
  	#prints{
  		display: none;
  	}
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
	
	<!-- <h3 id="hide" style="text-align:center"><?php echo $app_company_name; ?></h3> -->
	<?php
	if($logged==1||$logged==2){
	if($reports=='1'){
		if(isset($_GET["all"])){
		}else{
			echo "<center><h3>Sales in ".date("F d, Y - ",strtotime($f)).date("F d, Y",strtotime($t))."</h3></center>";
		}
		?>
		
<!-- 		<h5 id="hide" style="text-align:center">
					<?php
					echo   date("Y/m/d") . '&nbsp; / &nbsp;' . date("l");

					?>
					</h5>
					<hr> -->
		<div class="table-responsive">
		<button onclick="myFunction()" class="btn btn-primary prints"><span class="glyphicon glyphicon-print"></span>&nbsp;&nbsp; Print </button>
			<table class="table table-hover">
				<thead>
					<tr id="tbl">
						<th>Date </th>
						<th>SalesID</th>
						<th>Item Name</th>
						<th>Serial Number</th>
						<th style='text-align:center;'>Cost Price</th>
						<th style='text-align:center;'>Selling Price</th>
					</tr>
				</thead>
				<tbody>
					<?php

					if(!isset($page)){$page=1;}elseif($page<=0){$page=1;}
					$maxitem = $maximum_items_displayed; // maximum items
					$limit = ($page*$maxitem)-$maxitem;


					$query = "SELECT * FROM tbl_purchases WHERE itemID = '".$_GET["by"]."'";
					if(isset($_GET["all"])){
					
					}else{
						$query.=" AND date_ordered_int BETWEEN '".strtotime($f)."' AND '".strtotime($t)."'";
					}

					$numitemquery = mysql_query($query);
					$numitem = mysql_num_rows($numitemquery);
					$query.=" LIMIT $limit, $maxitem";



			 		if(($numitem%$maxitem)==0){
						$lastpage=($numitem/$maxitem);
					}else{
						$lastpage=($numitem/$maxitem)-(($numitem%$maxitem)/$maxitem)+1;
					}
					$maxpage = 3;

					// echo $query;
					$sales_items_query = mysql_query($query);
					if(mysql_num_rows($sales_items_query)!=0){
						while($sales_items_row=mysql_fetch_assoc($sales_items_query)){
							$item_data = mysql_fetch_assoc(mysql_query("SELECT * FROM tbl_items WHERE itemID='".$_GET["by"]."' AND deleted='0'"));

							$total_cost_data = mysql_fetch_assoc(mysql_query("SELECT SUM(costprice) as total_costprice FROM tbl_purchases WHERE itemID='".$_GET["by"]."' AND deleted='0'"));
							$total_selling_price_data = mysql_fetch_assoc(mysql_query("SELECT SUM(price) as total_selling_price FROM tbl_purchases WHERE itemID='".$_GET["by"]."' AND deleted='0'"));
							echo "
							<tr>
								<td>".date("m/d/Y",$sales_items_row["date_ordered_int"])."</td>
								<td><a href='sales-re?id=".$sales_items_row["orderID"]."'>S".sprintf("%06d",$sales_items_row["orderID"])."</a></td>
								<td>".$item_data["itemname"]."</td>
								<td>".$sales_items_row["serial_number"]."</td>
								<td style='text-align:right'>".number_format($sales_items_row["costprice"],2)."</td>
								<td style='text-align:right'>".number_format($sales_items_row["price"],2)."</td>
							</tr>
							";
						}
					}

					?>
				</tbody>
				<tfoot>
						<?php


						$sales_items_query = mysql_query($query);
						if(mysql_num_rows($sales_items_query)!=0){
						echo "<tr>
						<td style='text-align:right' colspan='5'>Total Cost:</td>
						<td style='font-weight:bold;text-align:right'>".number_format($total_cost_data["total_costprice"],2)."</td>



						</tr>";
						echo "<tr>
						<td style='text-align:right' colspan='5'>Total Sales:</td>
						<td style='font-weight:bold;text-align:right'>".number_format($total_selling_price_data["total_selling_price"],2)."</td></tr>";
					}
						?>
					
				</tfoot>
				
			</table>
			<?php
				echo "
			<div class='text-center prints'>
				<ul class='pagination prints'>
				
				";
				$url="?by=$by&f=$f&t=$t&";
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


<script>
function myFunction() {
    window.print();
}
</script>
