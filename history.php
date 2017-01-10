<?php
ob_start();
session_start();
require_once 'template';
$template = new template;
$accountID=@$_SESSION['accountID'];
$page=@$_GET['page'];
$cat=@$_GET['cat'];
if(!isset($cat)){
	$cat='all';
}
$date=@$_GET['date'];
$type=@$_GET['type'];
if(!isset($date)){
	 $timezone  = 0; //(GMT -5:00) EST (U.S. & Canada) 
		  
	$date=gmdate("m/d/Y", time() + 3600*($timezone+date("I")));
	$transtime=gmdate("h:i:s A", time() + 3600*($timezone+date("I")));
	
}
if(isset($accountID)){
	if($accountID==1){
		$logged=2;
	}else{
		$logged=1;
	}	
}else{
	$logged=0;
}
$header = $template->header($logged);
#$connect = mysql_connect("localhost","qfcdavao_admin","_39a11nwpm");
#mysql_select_db("qfcdavao_dbinventory");

include 'db.php';
mysql_select_db("inventory");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>History</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="style.css">
  <script src="jquery.min.js"></script>
  <script src="main.js"></script>
  <script src="js/bootstrap.min.js"></script>
  
   
  <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
  <link rel="stylesheet" href="/resources/demos/style.css">
  
  
  <script>
  $(document).ready(function(){
	  $( "#datepicker" ).datepicker();
	  $( "#datepicker" ).change(function(){
		  window.location.href='history?date='+$(this).val()+"&by=d";
	  });
	  
	  $( "#date" ).click(function(){
		  var dat = $("#datepicker").val();
		  window.location.href='history?date='+dat+"&by=d&cat=all";
		  
	  });
	  var by = $("#by").val();
	  
	  $( "#type" ).change(function(){
		var type = $("#type").val();
		  var dat = $("#datepicker").val();
		  var cat = $(this).val();
		  window.location.href='history?date='+dat+"&by="+by+"&cat=all&type="+type;
		  
	  });
	  $( "#mon" ).click(function(){
		  var type = $("#type").val();
		  var dat = $("#datepicker").val();
		  var cat = $(this).val();
		  window.location.href='history?date='+dat+"&by=m&cat=all&type="+type;
		  
	  });
	  $( "#year" ).click(function(){
		  var type = $("#type").val();
		  var dat = $("#datepicker").val();
		  var cat = $(this).val();		  
		  window.location.href='history?date='+dat+"&by=y&cat=all&type="+type;
	  });
	  $("#cat").change(function(){
		  var type = $("#type").val();
		  var dat = $("#datepicker").val();
		  var cat = $(this).val();
		  window.location.href='history?date='+dat+"&by="+by+"&cat="+cat+"&type="+type;
	  });
	  
  });
  </script>
  <style>
	.page{
		display:none;
	}
@media print{
	.page{
		display:inline !important;
	}
  .prints{display:none;}
      a[href]:after {
    content: none !important;
  }
    .table-responsive{
          width: 8.5in;
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
		  <a class = "navbar-brand" href = "index">Inventory</a>
	   </div>
	   
	   <div class = "collapse navbar-collapse" id = "example-navbar-collapse">
		  <ul class = "nav navbar-nav  navbar-left">
			 
			 <li class='active'><a href="history"><span class = "glyphicon glyphicon-folder-open"></span>&nbsp; History</a></li>
			 <li><a href="actions"><span class = "glyphicon glyphicon-heart"></span> Actions</a></li>
				<form class="navbar-form navbar-right" role="search" action='index' method='get'>
					<div class="form-group">
						<input type="text" class="form-control" placeholder="Search" name='keyword'>
					</div>
					<button type="submit" class="btn btn-primary" name='search'><span class='glyphicon glyphicon-search'></span></button>
				</form>		
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
						
						<a href = 'settings?tab=1' class = 'list-group-item'><span class='glyphicon glyphicon-plus'></span> Add Products</a>
						<a href = 'settings?tab=2' class = 'list-group-item'><span class='glyphicon glyphicon-cog'></span> Application Settings</a>
										  					  
					  ">
					<span class='glyphicon glyphicon-cog'></span> Settings</a>
				</li>
				
				<li><a href='logout'>Hi <?php echo $employee_name; ?>&nbsp;&nbsp;<span class='glyphicon glyphicon-log-in'></span> Logout</a></li>
			</ul>
		  <?php }?>
		  
		  

		  </div>

	   </nav>	
<div class="container-fluid">
  <div class='row'>
	<!--content-->
	<div class='col-md-12'>
	<?php
	if($logged!=0){
	?>

		<div class='row'>
		
			<div class='col-md-2 prints'>
			<ul class="pager">
			  <li class = "previous">
			  	<?php
				$yesterday = strtotime($date);
				$yesterday = strtotime("-1 day", $yesterday);
				$yesterday= date('m/d/Y', $yesterday);
				echo "<a href='history?date=$yesterday'>&larr; $yesterday";
				?>
			  </a></li>
			</ul>
			</div>
		
			<div class='col-md-2 form-group prints' style='margin-top:1.4em;'>
				<select class='form-control' id='type' data-toggle = "tooltip"  title='Type'>
					<option value='all'>All</option>
					<?php
					echo "<option value='out'";
					if(strtolower($type)=='out'){
						echo " selected='selected'";
					}
					echo ">Out</option>";
					echo "<option value='in'";
					if(strtolower($type)=='in'){
						echo " selected='selected'";
					}
					echo ">In</option>";
					?>
				</select>
			</div>

		
		<div class = "col-md-4 prints" style='margin-top:1.4em;'>
            <div class = "input-group">
               
               <input type = "text" class = "form-control" value='<?php echo $date; ?>' id='datepicker'>
               <div class = "input-group-btn">
                  
                  <button type = "button" class = "btn btn-primary dropdown-toggle" 
                     data-toggle = "dropdown">
                     <?php
					 $by = @$_GET['by'];
					 if(isset($by)){
						 if(strtolower($by)=='m'){
							 echo "Month
							 <input type='hidden' value='$by' id='by'>";
						 }elseif(strtolower($by)=='y'){
							 echo "Year
							 <input type='hidden' value='$by' id='by'>";
						 }else{
							$by='d';
							 echo "Date
							 <input type='hidden' value='$by' id='by'>";
						 }
					 }else{
						 $by='d';
						 echo "Date
						<input type='hidden' value='$by' id='by'>";
					 }
					 ?> 
                     <span class = "caret"></span>
                  </button>
                  
                  <ul class = "dropdown-menu pull-right">
                     <li id='date'><a href = "#">Date</a></li>
                     <li id='mon'><a href = "#">Month</a></li>
                     <li id='year'><a href = "#">Year</a></li>
                  </ul>
               </div><!-- /btn-group -->
               
            </div><!-- /input-group -->
         </div><!-- /.col-lg-6 -->
		
		<?php
			if(!isset($page)){$page=1;}elseif($page<=0){$page=1;}
			$maxitem = 50; // maximum items
			$limit = ($page*$maxitem)-$maxitem;
			$query = "SELECT * FROM tbl_history";
			$catquery = "SELECT DISTINCT category FROM tbl_history";
			

			if(strtolower($by)=='m'){
				$qdate = $date[0].$date[1]."/%/".$date[6].$date[7].$date[8].$date[9];
				
				$mdate = strtotime($date);
				$mdate = date("F Y",$mdate);
				$state = "<h3 align='center'>Month of $mdate</h3>";
				
			}elseif(strtolower($by)=='y'){
				$qdate = "%/%/".$date[6].$date[7].$date[8].$date[9];
				$state = "<h3 align='center'>Year ".$date[6].$date[7].$date[8].$date[9]."</h3>";
			}else{
				$ddate = strtotime($date);
				$ddate = date("F d, Y",$ddate);
				$qdate=$date;
				$state="<h3 align='center'>".$ddate."</h3>";
			}
			
				$query.=" WHERE date LIKE '$qdate'";
				$catquery.=" WHERE date LIKE '$qdate'";
				
				if(isset($cat)){
					if(strtolower($cat)!='all'){
						$query.=" AND category='$cat'";
					}
				}
				if(isset($type)){
					if(strtolower($type)!='all'){
						$query.=" AND type='$type'";
					}
				}
				$query.=" ORDER BY date";
			//echo "$query";
			//echo "$catquery";
			
			
			$numitemquery = mysql_query($query);
			$numitem = mysql_num_rows($numitemquery);
			
			
			$query.=" LIMIT $limit,$maxitem";
			echo "$query";
			
			$historyquery = mysql_query($query);
			if(($numitem%$maxitem)==0){
				$lastpage=($numitem/$maxitem);
			}else{
				$lastpage=($numitem/$maxitem)-(($numitem%$maxitem)/$maxitem)+1;
			}
			$maxpage = 3;
			
			
			
			
		?>
		
		
		
		
			<div class='col-md-2 form-group prints' style='margin-top:1.4em;'>
				<select class='form-control' id='cat' data-toggle = "tooltip"  title='Category'>
					<option value='all'>All</option>
					<?php 
					$catquery = mysql_query($catquery);
					if(mysql_num_rows($catquery)!=0){
						while($catrow=mysql_fetch_assoc($catquery)){
							$dbcategory = $catrow['category'];
							echo "<option value='$dbcategory' ";
							if(strtolower($dbcategory)==strtolower($cat)){
								echo "selected='selected'";
							}
							echo ">$dbcategory</option>";
						}
					}
					?>
				</select>
			</div>
			
			
			<div class='col-md-2 prints'>
			<ul class="pager">
			  <li class = "next">
			  	<?php
				$tommorow = strtotime($date);
				$tommorow = strtotime("+1 day", $tommorow);
				$tommorow= date('m/d/Y', $tommorow);
				echo "<a href='history?date=$tommorow'>$tommorow &rarr;";
				?>
			  </a></li>
			</ul>
			</div>
		</div>
	
	<div class='row'>
		<div class="col-md-12"  style='padding:1em;'>
		<?php echo $state; ?>
		<div class='table-responsive'>
			<table class='table table-hover'>
			<thead>
			 <tr>
			  <th>#</th>
			  <th>Type</th>
			  <th>Category</th>
			  <th>Item</th>
			  <th>Stock</th>
			  <th>Value</th>
			  <th>Remaining</th>
			  <th>Date</th>
			  <th>Time</th>
			  <th>Comment</th>
			  <th class='prints'>User</th>
			 </tr>
			</thead>
			<tbody>
			<?php
			if(mysql_num_rows($historyquery)!=0){
			while($historyrow = mysql_fetch_assoc($historyquery)){
				$number=$historyrow['historyID'];
				$type=$historyrow['type'];
				$itemname=$historyrow['itemname'];
				$itemID=$historyrow['itemID'];
				$category=$historyrow['category'];
				$value=$historyrow['value'];
				$stock=$historyrow['stock'];
				$remain=$historyrow['remain'];
				$date=$historyrow['date'];
				$time=$historyrow['time'];
				$comment=$historyrow['comment'];
				$dbaccountID=$historyrow['accountID'];
				$accountquery = mysql_query("SELECT * FROM tbl_accounts WHERE accountID='$dbaccountID'");
				while($accountrow=mysql_fetch_assoc($accountquery)){
					$username=$accountrow['username'];
				}
				echo "
				<tr>
				 <td>$number</td>
				 <td>".ucfirst($type)."</td>
				 <td>$category</td>
				 <td><a href='item?id=$itemID'>$itemname</a></td>
				 <td>$stock</td>
				 <td>$value</td>
				 <td>$remain</td>
				 <td>$date</td>
				 <td>$time</td>
				 <td>$comment</td>
				 <td class='prints'>$username</td>
				</tr>
				";
			}
			
			
			
			
			?>
			</tbody>
			</table>
			</div>
			<?php
			echo "<div class='text-center'><ul class='pagination prints'>
			
			";
			$types=$_GET['type'];
			if(isset($_GET['date'])){
				$url="history?date=$date&by=$by&cat=$cat&type=$types&";
			}else{
				$url="history?";
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
			if($page!=$lastpage){
				$next=$page+1;
				echo "<li><a href = '".$url."page=$next'>&raquo;</a></li>";
				echo "<li><a href = '".$url."page=$lastpage'>&raquo;&raquo;</a></li>";
			}
			echo "</ul><span class='page' >Page $page</span></div>";
			}
			?>
		</div>
	</div>
	<?php
	}else{ ?>
	<form action='login' method='post' role='form'>
		<div class='form-group' style='margin:1em;'>
		<label for='username'>Username</label>
		<input type='text' name='username' class='form-control' placeholder='Username'>
		</div>
		
		<div class='form-group' style='margin:1em;'>
		<label for='password'>Password</label>
		<input type='password' name='password' class='form-control' placeholder='Password'>
		</div>
		<button class='btn btn-primary' type='submit' name='submit' style='margin:1em;'>Sign in</button>
	</form>
	<?php } ?>
	
	</div><!--content-->

  </div>
</div>
</body>
</html>
<?php mysql_close($connect);?>
  <script>
$("[data-toggle=popover]").popover({html:true})
$(function () { $("[data-toggle = 'tooltip']").tooltip(); });
</script>