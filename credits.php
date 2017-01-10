<?php
ob_start();
session_start();
$accountID=@$_SESSION['accountID'];
$page=@$_GET['page'];
$tab=@$_GET['tab'];
$id=@$_GET['id'];
if(!isset($tab)){
	$tab=1;
}
$keyword=@$_GET['keyword'];
$search=@$_GET['search'];
$by=@$_GET['by'];
$order=@$_GET['order'];

include 'db.php';
if(!isset($page)){$page=1;}elseif($page<=0){$page=1;}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title><?php echo $app_name; ?> - Credits</title>
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
  .popover{
    width:100%;   
}
  </style>
  <script>
		$(document).ready(function(){

			$("#date_from,#date_to").datepicker();
	$( "#ar_no" ).autocomplete({
      source: 'ar-search',
	  select: function(event, ui){
		  window.location='item?s='+ui.item.data;
	  }
    });
			
	$( "#search" ).autocomplete({
      source: 'search-item-all',
	  select: function(event, ui){
		  window.location='item?s='+ui.item.data;
	  }
    });
	$( "#customer" ).autocomplete({
      source: 'search-customer-auto',
	  select: function(event, ui){
		  window.location='credits?tab=2&id='+ui.item.data;
	  }
    });
	$( "#search_customer" ).autocomplete({
      source: 'search-customer-auto',
	  select: function(event, ui){
		  var tab = $("#tab").val();
		  window.location='credits?tab='+tab+'&id='+ui.item.data;
	  }
    });
	$("#date_now").datepicker();
	$("#date_now").change(function(){
		var date_now = $(this).val();
		window.location = 'credits?d='+date_now;
	});
	
		 $('.selected').click(function(event) {
        if (event.target.type !== 'checkbox') {
            $(':checkbox', this).trigger('click');
        }
	    });

		 $(".payment").click(function(e){
		 	$("#payment").modal("show");
		 	$("#payment-textbox").val("");
		 	$.ajax({
		 		type: "POST",
		 		url: "credits-status",
		 		data: "id="+e.target.id,
		 		dataType: "json",
		 		success: function(data){
		 			$("#payment-textbox").attr("max",data.payment);
		 			$("#orderID").val(data.orderID);
		 			$("#balance-textbox").val(data.payment);
		 		}
		 	});
		 });
		<?php if($tab==5){
			echo " show_paid_accounts();";
		} ?>
	
  });
		 // show_paid_accounts();


		$(document).on("submit","#credits-payments",function(e){
			e.preventDefault();
			$.ajax({
				type: "POST",
				url: $("#credits-payments").attr("action"),
				data: $("#credits-payments :input").serialize(),
				cache: false,
				dataType: "json",
				success: function(data){
					alert("Success! Payments has been saved.");
					$("#payment").modal("hide");
					window.location = "sales-re?id="+data.orderID;
				}

			});
		});
		$(document).on("submit","#paid-accounts",function(e){
			e.preventDefault();
			show_paid_accounts();
		});
		$(document).on("click",".paging-paid-accounts",function(e){
			show_paid_accounts(e.target.id);
		});


		function show_paid_accounts(page=1) {
			// alert($("#paid-accounts :input").serialize()+"&page="+page);
			$.ajax({
				type: "POST",
				url: $("#paid-accounts").attr("action"),
				data: $("#paid-accounts :input").serialize()+"&page="+page,
				cache: false,
				success: function(data){
					$("#paid-credits-table tbody").html(data);
				}
			});
		}

  </script>
    <style>

	
	
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

	<div class='col-md-2 prints'>
	<?php
		echo "<label>Navigation:</label><a href = '?tab=1' class = 'list-group-item"; if(isset($tab)&&$tab=='1'){echo " active"; } echo "'>View Credits</a>";
		echo "<a href = '?tab=2' class = 'list-group-item"; if(isset($tab)&&$tab=='2'){echo " active"; } echo "'>Customer&#39s Credits</a>";
		echo "<a href = '?tab=5' class = 'list-group-item"; if(isset($tab)&&$tab=='5'){echo " active"; } echo "'>Paid Credits</a>";
		// echo "<a href = '?tab=6' class = 'list-group-item"; if(isset($tab)&&$tab=='6'){echo " active"; } echo "'>Unpaid Credits</a>";
		// echo "<a href = '?tab=3' class = 'list-group-item"; if(isset($tab)&&$tab=='3'){echo " active"; } echo "'>Payed Accounts</a>";
		// echo "<a href = '?tab=4' class = 'list-group-item"; if(isset($tab)&&$tab=='4'){echo " active"; } echo "'>Statement of Accounts</a>";
		// echo "<a href = '?tab=2&id=0' class = 'list-group-item"; if(isset($tab)&&$tab=='5'){echo " active"; } echo "'>Others</a>";
		$credit_num_rows = mysql_num_rows(mysql_query("SELECT * FROM tbl_payments WHERE type_payment = 'credit' AND customerID='$id' AND deleted='0'"));
		if($tab==2&&isset($id)&&$credit_num_rows!=0){
			echo "
			<br>
			<label>Controls:</label>
				<button class='btn btn-block btn-primary' type='submit' name='a_report'>Payments</button>
				<button class='btn btn-block btn-primary' type='submit' name='s_account'>Statement of Account</button>
			";

		}

		
	?>
	</div>
  	<div class='col-md-10 prints'>
	
	
	<?php
	if($logged==1||$logged==2){
		if($credits=='1'){

		if($tab==1){
			$date_now = @$_GET["d"];
			if(isset($date_now)){
				$yesterday = date("m/d/Y",strtotime($date_now.'-1 day'));
				$tommorow = date("m/d/Y",strtotime($date_now.'+1 day'));
			}else{
				$timezone  = 0;
				$date_now =gmdate("m/d/Y", time() + 3600*($timezone+date("I")));
				$yesterday = date("m/d/Y",strtotime($date_now.'-1 day'));
				$tommorow = date("m/d/Y",strtotime($date_now.'+1 day'));
			}

			echo "

			
			<div class='row'>
				<div class='col-md-4'>
				<ul class='pager'>
				  <li class='previous'><a href='?tab=1&d=$yesterday'>← $yesterday</a></li>
				</ul>
				</div>
				
				<div class='col-md-4'>
				<input type='text' id='date_now' value='$date_now' class='form-control pager' placeholder='Pick a Date'>
				</div>
				
				<div class='col-md-4'>
				<ul class='pager'>
				  <li class='next'><a href='?tab=1&d=$tommorow'>$tommorow →</a></li>
				</ul>
				</div>
			</div>
			
			<div class='table-responsive'>
			<table class='table table-hover'>
			<thead>
			<tr>
				<th>Sales No.</th>
				<th>Date</th>
				<th>Time</th>
				<th>Customer</th>
				<th>Date Due</th>
				<th>Amount</th>
				<th>Invoice #</th>
				<th></th>
			</tr>
			<tbody>";
			$credit_search_query = mysql_query("SELECT * FROM tbl_payments WHERE type_payment = 'CREDIT' AND date = '".strtotime($date_now)."' AND deleted='0' AND payment!='0'");
			if(mysql_num_rows($credit_search_query)!=0){
				while($credit_search_row=mysql_fetch_assoc($credit_search_query)){
					$orderID = $credit_search_row["orderID"];
					
					$credit_query = mysql_query("SELECT * FROM tbl_orders WHERE orderID='$orderID' AND deleted='0'");
					if(mysql_num_rows($credit_query)!=0){
						while($credit_row=mysql_fetch_assoc($credit_query)){
							$orderID = $credit_row["orderID"];
							$date_ordered = date("m/d/Y",$credit_row["date_ordered"]);
							$time_ordered = $credit_row["time_ordered"];
							$total = $credit_row["total"];
							$comments = $credit_row["comments"];
							$customerID = $credit_row["customerID"];
							$customer = $credit_row["customer"];
							$date_due = date("m/d/Y",$credit_row["date_due"]);
							$customer_data = mysql_fetch_assoc(mysql_query("SELECT * FROM tbl_customer WHERE customerID='$customerID'"));
							($customerID==0?$customer_name = $customer:$customer_name=$customer_data["companyname"]);
							$amount_credit_query = mysql_query("SELECT * FROM tbl_payments WHERE type_payment = 'credit' AND deleted='0' AND orderID='$orderID'");
							if(mysql_num_rows($amount_credit_query)!=0){
								while($amount_credit_row=mysql_fetch_assoc($amount_credit_query)){
									$payment = $amount_credit_row["payment"];
									$comments = $amount_credit_row["comments"];
								}
							}
							echo "
							<tr>
								<td><a href='sales-re?id=$orderID'>S".sprintf("%06d",$orderID)."</a></td>
								<td>$date_ordered</td>
								<td>$time_ordered</td>";
								if($customerID!=0){
									echo "<td><a href='?tab=2&id=$customerID'>".$customer_name."</a></td>";
								}else{
									echo "<td>".$customer_name."</td>";
								}
								echo "<td>$date_due</td>
								<td>₱".number_format($payment,2)."</td>
								<td>$comments</td>
								<td><a class='payment' id='$orderID' href='#'><span class='glyphicon glyphicon-shopping-cart'></span> Payment</a></td>
							</tr>
							";
						}
					}
				}
			}
			
			$credit_search_query = mysql_query("SELECT * FROM tbl_payments WHERE type_payment LIKE 'CREDIT' AND date_due <= '".strtotime($date_now)."' AND deleted='0' AND payment!='0'");
			if(mysql_num_rows($credit_search_query)!=0){
				while($credit_search_row=mysql_fetch_assoc($credit_search_query)){
					$orderID = $credit_search_row["orderID"];
					
					$credit_query = mysql_query("SELECT * FROM tbl_orders WHERE orderID='$orderID' AND deleted='0'");
					if(mysql_num_rows($credit_query)!=0){
						while($credit_row=mysql_fetch_assoc($credit_query)){
							$orderID = $credit_row["orderID"];
							$date_ordered = date("m/d/Y",$credit_row["date_ordered"]);
							$time_ordered = $credit_row["time_ordered"];
							$total = $credit_row["total"];
							$comments = $credit_row["comments"];
							$customer = $credit_row["customer"];
							$customerID = $credit_row["customerID"];
							$customer_data = mysql_fetch_assoc(mysql_query("SELECT * FROM tbl_customer WHERE customerID='$customerID'"));
							$date_due = date("m/d/Y",$credit_row["date_due"]);
							($customerID==0?$customer_name = $customer:$customer_name=$customer_data["companyname"]);
							$amount_credit_query = mysql_query("SELECT * FROM tbl_payments WHERE type_payment = 'credit' AND deleted='0' AND orderID='$orderID'");
							if(mysql_num_rows($amount_credit_query)!=0){
								while($amount_credit_row=mysql_fetch_assoc($amount_credit_query)){
									$payment = $amount_credit_row["payment"];
									$comments = $amount_credit_row["comments"];
								}
							}							
							
							echo "
							<tr class='danger'>
								<td><a href='sales-re?id=$orderID'>S".sprintf("%06d",$orderID)."</a></td>
								<td>$date_ordered</td>
								<td>$time_ordered</td>";
								if($customerID!=0){
									echo "<td><a href='?tab=2&id=$customerID'>".$customer_name."</a></td>";
								}else{
									echo "<td>".$customer_name."</td>";
								}
								echo "<td>$date_due</td>
								<td>₱".number_format($payment,2)."</td>
								<td>$comments</td>
								<td><a class='payment' id='$orderID' href='#'><span class='glyphicon glyphicon-shopping-cart'></span> Payment</a></td>
							</tr>
							";
						}
					}
				}
			}
			
			
			echo "
			</tbody>
			</thead>
			</table>";

			
			echo "</div>
			";
		}elseif($tab==2){
			if(!isset($id)){
				echo "
				<div class='col-md-6 row'>
				<h3 style='text-align:center'>Customer&#39s Credits</h3>
				<form action='#' method='get'>
					<div class='form-group'>
					<label>Customer Name:</label>
					<input type='text' name='customer' id='customer' placeholder='Customer Name' class='form-control'>
					</div>
				</form>
				</div>
				";
			}else{
				

				$query = "SELECT * FROM tbl_customer WHERE customerID='$id'";
				$customer_query = mysql_query($query);
				if(mysql_num_rows($customer_query)!=0){
					while($customer_row=mysql_fetch_assoc($customer_query)){
						$companyname = $customer_row["companyname"];
						$address = $customer_row["address"];
						$phone = $customer_row["phone"];
						$contactperson = $customer_row["contactperson"];
					}
				}else{
					$companyname = $address = $phone = $contactperson = "";
				}
				if($id==0){
					$companyname = "Others";
				}
				echo "
				<input type='hidden' name='customerID' value='$id'>
				<h3 style='text-align:center'>Customer&#39s Credits</h3>
				<p><label>Company Name: </label> $companyname
				<br><label>Address: </label> $address
				<br><label>Contact Number: </label> $phone
				<br><label>Contact Person: </label> $contactperson</p>
				<div class='table-responsive'>
				<table class='table table-hover'>
				<thead>
				<tr>
					<th></th>
					<th>Sales ID</th>";
					if($id==0){
						echo "<th>Customer</th>";
					}
					echo "<th>Date</th>
					<th>Time</th>
					<th>Date Due</th>
					<th>Invoice #</th>
					<th>Total</th>
				</tr>
				</thead>
				<tbody>";
				$maxitem = $maximum_items_displayed; // maximum items
				$limit = ($page*$maxitem)-$maxitem;
				$query="SELECT * FROM tbl_payments WHERE type_payment = 'credit' AND customerID='$id' AND deleted='0'";
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
				
				$credit_query = mysql_query($query);
				if(mysql_num_rows($credit_query)!=0){
					while($credit_row=mysql_fetch_assoc($credit_query)){
						$orderID = $credit_row["orderID"];
						$order_data = mysql_fetch_assoc(mysql_query("SELECT * FROM tbl_orders WHERE orderID='$orderID'"));
						$date = $credit_row["date"];
						$time = $credit_row["time"];
						$comments = $credit_row["comments"];
						$date_due = date("m/d/Y",$credit_row["date_due"]);
						echo "
						<tr class='selected'>
							<td><input type='checkbox' value='$orderID' name='select[]'></td>
							<td><a href='sales-re?id=$orderID'>S".sprintf("%06d",$orderID)."</a></td>";
							if($id==0){
								echo "<td>".$order_data["customer"]."</td>";
							}
							echo "<td>$date</td>
							<td>$time</td>
							<td>$date_due</td>
							<td>$comments</td>
							<td>".number_format($order_data["total"],2)."</td>
						</tr>
				";
					}
				}
				echo "
				</tbody>
				</table>
				</div>
				
				";
				echo "</table>
			";
			
			echo "
			<div class='text-center'>
			<ul class='pagination prints'>
			
			";
			$url="?tab=2&id=$id&";
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
			
			echo "
			</div>
			"; 
				
			}
			
			if(isset($_POST["a_report"])){
				$select = $_POST["select"];
				$customerID = $_POST["customerID"];
				if($select!=NULL){
					if(isset($_SESSION["selectcredit"])){
						//merge existing arrays in $_SESSION["selectcredit"]
						$_SESSION["selectcredit"]=array_merge($_SESSION["selectcredit"],$select);
					}else{
						$_SESSION["selectcredit"]=$select;
					}		
					header("location:credits-payments");					
				}else{
					//if user did not select any transactions then return to this page
					header("location:credits?tab=2&id=".$customerID);
				}

			}
			if(isset($_POST["s_account"])){
				$customerID = $_POST["customerID"];
				$select = @$_POST["select"];
				if($select!=NULL){
					$_SESSION["selectcredit"]= $select;
					// I used implode to insert other transactions in one row if user has selected many transactions see database structure
					$paymentID = implode(",",$select);
					$timezone  = 0; //(GMT -5:00) EST (U.S. & Canada) 
					$datenow=gmdate("m/d/Y", time() + 3600*($timezone+date("I")));
					$timenow=gmdate("h:i:s A", time() + 3600*($timezone+date("I")));
					mysql_query("INSERT INTO tbl_soa VALUES ('','$','$accountID','0','$datenow','$timenow','$paymentID','$customerID','','')");
					$soa_query = mysql_query("SELECT * FROM tbl_soa ORDER BY soaID DESC LIMIT 0,1");
					if(mysql_num_rows($soa_query)!=0){
						while($soa_row=mysql_fetch_assoc($soa_query)){
							$soaID = $soa_row["soaID"];
						}
					header("location:credits-soa?id=".$soaID);
					}
				}else{
					//if user did not select any transactions then return to this page
					header("location:credits?tab=2&id=".$customerID);
				}
			}
			
		}elseif($tab==3){
			echo "
			</form>
			<div class='row'>
				<div class='col-md-12'>
				<h3 style='text-align:center'>Payed Accounts</h3>
				<div class='pull-left input-group col-md-3'>
				<input type='hidden' name='tab' value='3' id='tab'>
				<input type='text' class='form-control' placeholder='Search for Customer' id='search_customer'>
				</div>
				
				<form action='credits' method='get'>
				<div class='pull-right input-group col-md-3'>
				<input type='hidden' name='tab' value='3'>
				<span class='input-group-addon'>AR</span>
				<input type='number' min='0' class='form-control' placeholder='Search for AR Number' name='ar'>
				</form>
				</div>
				
				
				</div>
			</div>
			<br>
			<div class='table-responsive'>
			<table class='table table-hover'>
			<thead>
				<tr>
					<th>AR #</th>
					<th>Sale ID</th>
					<th>Invoice #</th>
					<th>Date and Time Paid</th>
					<th style='text-align:right'>Amount</th>
					<th style='text-align:right'>Payment Received</th>
					<th>Payment Type</th>
					<th>Customer</th>
					<th>Received By</th>
					<th>Comments</th>
				</tr>
			</thead>";
			$creditID=@$_GET["ar"];
			$customerID=@$_GET["id"];
			$maxitem = $maximum_items_displayed; // maximum items
			$limit = ($page*$maxitem)-$maxitem;
			$query ="SELECT * FROM tbl_credits";
			if(isset($creditID)){
				$query.=" WHERE creditID='$creditID'";
			}elseif(isset($customerID)){
				$query.=" WHERE customerID='$customerID'";
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
			$credit_query = mysql_query($query);
			if(mysql_num_rows($credit_query)!=0){
				while($credit_row=mysql_fetch_assoc($credit_query)){
					$creditID = $credit_row["creditID"];
					$dbaccountID = $credit_row["accountID"];
					$dbcustomerID = $credit_row["customerID"];
					$dbamount = $credit_row["amount"];
					$dbpayment = $credit_row["payment"];
					$dbpaymentID = $credit_row["paymentID"];
					$dbtype_payment = $credit_row["type_payment"];
					$dbcomments = $credit_row["comments"];
					$account_query = mysql_query("SELECT * FROM tbl_users WHERE accountID='$dbaccountID'");{
						while($account_row=mysql_fetch_assoc($account_query)){
							$db_employee_name = $account_row["employee_name"];
						}
					}
					$customer_query= mysql_query("SELECT * FROM tbl_customer WHERE customerID='$dbcustomerID'");{
						while($customer_row=mysql_fetch_assoc($customer_query)){
							$companyname = $customer_row["companyname"];
						}
					}
					$dbpaymentID_array = explode(",",$dbpaymentID);
					
					$orderID_array=array();
					$invoice_array=array();
					foreach($dbpaymentID_array as $dbpaymentID){
					$payments_query = mysql_query("SELECT * FROM tbl_payments WHERE paymentID='$dbpaymentID'");
						while($payments_row=mysql_fetch_assoc($payments_query)){
							$orderID_array[] = $payments_row["orderID"];
							$date = $payments_row["date"];
							$time = $payments_row["time"];
							$amount = $payments_row["time"];
							$invoice_array[] = $payments_row["comments"];
							
						}	
					}
					

					echo "
					<tr>
						<td><a href='credits-ar?id=$creditID'>AR".sprintf("%06d",$creditID)."</a></td>
						<td>";
						$i=0;
						foreach($orderID_array as $orderID){
							if($i==0){
								echo "<a href='sales-re?id=$orderID'>S".sprintf("%06d",$orderID)."</a>";
							}else{
								echo "<br><a href='sales-re?id=$orderID'>S".sprintf("%06d",$orderID)."</a>";
							}
							$i++;
						}
						
					echo "	</td>
					<td>";
						$i=0;
						foreach($invoice_array as $invoice){
							if($i==0){
								echo "$invoice";
							}else{
								echo "<br>$invoice";
							}
							$i++;
						}
						
					echo "	</td>
						<td>$date $time</td>
						<th style='text-align:right'>₱".number_format($dbamount,2)."</th>
						<th style='text-align:right'>₱".number_format($dbpayment,2)."</th>
						<td>$dbtype_payment</td>
						<td><a href='?tab=3&id=$dbcustomerID'>$companyname</a></td>
						<td>$db_employee_name</td>
						<td>$dbcomments</td>
					</tr>
					";
				}
			}else{
			echo "
			<tr>
				<td colspan='20' align='center'><b style='font-size:200%'>No Results Found.</b></td>
			</tr>
			<tfoot>
				<tr>
					<td></td>
				</tr>
			</tfoot>
			";
			}
			echo "</table>
			</div>";
			if(!isset($_GET['ar'])){
			echo "
			<div class='text-center'>
			<ul class='pagination prints'>
			
			";
			if(isset($_GET['id'])){
				$id = $_GET['id'];
				$url="?tab=3&id=$id&";
			}else{
				$url="?tab=3&";
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
			</div>";
			}

		}elseif($tab==4){
?>
			</form>
			
			<div class='row'>
			
			<div class='col-md-12'>
			
			<h3 style='text-align:center'>Statement of Accounts</h3>
			<div class='pull-left input-group col-md-3'>
			<input type='hidden' name='tab' value='4' id='tab'>
			<input type='text' class='form-control' placeholder='Search for Customer' id='search_customer'>
			</div>
			
			<form action='credits' method='get'>
			<div class='pull-right input-group col-md-3'>
			<input type='hidden' name='tab' value='4'>
			<span class='input-group-addon'>SOA</span>
			<input type='number' min='0' class='form-control' placeholder='Search for SOA Number' name='soa'>
			</div>
			</form>
				
			</div>
			</div>
			
			<?php
			echo "
			<br>
			<div class='table-responsive'>
				<table class='table table-hover'>
				<thead>
					<tr>
						<th>Statement #</th>
						<th>Date</th>
						<th>Customer</th>
						<th>Total</th>
						<th>Payments</th>
					</tr>
				</thead>
				<tbody>";
				$soaID=@$_GET['soa'];
				$id=@$_GET['id'];
				$maxitem = $maximum_items_displayed; // maximum items
				$limit = ($page*$maxitem)-$maxitem;
				$query = "SELECT * FROM tbl_soa";
				if(isset($soaID)){
					$query.=" WHERE soaID='$soaID'";
				}elseif(isset($id)){
					$query.=" WHERE customerID='$id'";
				}
				$numitemquery = mysql_query($query);
				$numitem = mysql_num_rows($numitemquery);
				$query.=" LIMIT $limit, $maxitem";
				
				$soa_query = mysql_query($query);
				// echo $query;
				if(($numitem%$maxitem)==0){
					$lastpage=($numitem/$maxitem);
				}else{
					$lastpage=($numitem/$maxitem)-(($numitem%$maxitem)/$maxitem)+1;
				}
				$maxpage = 3;
				if(mysql_num_rows($soa_query)!=0){
					while($soa_row=mysql_fetch_assoc($soa_query)){
						$soaID = $soa_row["soaID"];
						$date = $soa_row["date"];
						$creditID = $soa_row["creditID"];
						$customerID = $soa_row["customerID"];
						$total = $soa_row["total"];
						$paid = $soa_row["paid"];
						$customer_query = mysql_query("SELECT * FROM tbl_customer WHERE customerID='$customerID'");
						while($customer_row=mysql_fetch_assoc($customer_query)){
							$companyname = $customer_row['companyname'];
						}
						echo "
						<tr>
							<td><a href='credits-soa?id=$soaID'>SOA".sprintf("%06d",$soaID)."</a></td>
							<td>$date</td>
							<td><a href='?tab=4&id=$customerID'>$companyname</a></td>
							<td>$total</td>";
							if($paid=='0'){
								echo "<td><a href='credits-payments?id=$soaID'>Payment</a></td>";
							}else{
								echo "<td><a href='credits-ar?id=$creditID'>AR".sprintf("%06d",$creditID)."</a></td>";
							}
							echo "
						</tr>
						";
					}
				}else{
				echo "
				<tr>
					<td colspan='20' align='center'><b style='font-size:200%'>No Results Found.</b></td>
				</tr>
				<tfoot>
					<tr>
						<td></td>
					</tr>
				</tfoot>
				";
				}
				echo "
				</tbody>
				</table>
			</div>
			";
			if(!isset($_GET['soa'])){
			echo "
			<div class='text-center'>
			<ul class='pagination prints'>
			
			";
			if(isset($_GET['id'])){
				$id = $_GET['id'];
				$url="?tab=4&id=$id&";
			}else{
				$url="?tab=4&";
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
			</div>";
			}
		}elseif($tab==5){
			echo '
			<form action="credits-paid-accounts" method="post" id="paid-accounts" class="form-inline">

			<div class="form-group prints">
				<label>Reference Number:</label><br>
				<input type="text" name="comments" class="form-control" placeholder="Reference Number">
			</div>

			<div class="form-group prints">
				<label>Date From:</label><br>
				<input type="text" id="date_from" name="date_from" class="form-control" placeholder="Date From" readonly>
			</div>

			<div class="form-group prints">
				<label>Date To:</label><br>
				<input type="text" id="date_to" name="date_to" class="form-control" placeholder="Date To" readonly>
			</div>


			<div class="form-group prints">
				<label>&nbsp;</label><br>
				<button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-search"></span> Search</button>
			</div>

			</form>
			<div class="table-responsive">
				<table class="table table-hover" id="paid-credits-table">
					<thead>
						<tr>
							<th>Date of Payment</th>
							<th>Sale ID</th>
							<th>Amount</th>
							<th>Balance</th>
							<th>Customer</th>
							<th>Reference Number</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>

			';
		}elseif ($tab==6) {
			
			echo '
			<form action="credits-unpaid-accounts" method="post" id="paid-accounts" class="form-inline">

			<div class="form-group prints">
				<label>Reference Number:</label><br>
				<input type="text" name="comments" class="form-control" placeholder="Reference Number">
			</div>

			<div class="form-group prints">
				<label>Customer Name:</label><br>
				<input type="text" name="customer" class="form-control" placeholder="Customer Name">
			</div>

			<div class="form-group prints">
				<label>Date From:</label><br>
				<input type="text" id="date_from" name="date_from" class="form-control" placeholder="Date From" readonly>
			</div>

			<div class="form-group prints">
				<label>Date To:</label><br>
				<input type="text" id="date_to" name="date_to" class="form-control" placeholder="Date To" readonly>
			</div>


			<div class="form-group prints">
				<label>&nbsp;</label><br>
				<button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-search"></span> Search</button>
			</div>

			</form>
			<div class="table-responsive">
				<table class="table table-hover" id="paid-credits-table">
					<thead>
						<tr>
							<th>Date of Payment</th>
							<th>Sale ID</th>
							<th>Amount</th>
							<th>Balance</th>
							<th>Customer</th>
							<th>Reference Number</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>

			';
			
		}
			
		}else{
			echo "<strong><center>You do not have the authority to access this module.</center></strong>";
		}
	}else{
			header("location:index");

		} ?>
	</div>
	

	<?php

	echo '
	<!-- Modal -->
	<div id="payment" class="modal fade" role="dialog">
	  <div class="modal-dialog">

	    <!-- Modal content-->
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal">&times;</button>
	        <h4 class="modal-title">Modal Header</h4>
	      </div>
	      <div class="modal-body">
	      	<form action="credits-payments-new" method="post" id="credits-payments" class="form-horizontal">
	      	<input type="hidden" id="orderID" name="orderID">


	      	<div class="form-group">
	      		<label for="itemname" class="col-md-4">Balance:</label>
	      	<div class="col-md-8">
	      		<input type="text" class="form-control" id="balance-textbox" placeholder="Payment" readonly>
	      	</div>
	      	</div>


	      	<div class="form-group">
	      		<label for="itemname" class="col-md-4">Type of Payment:</label>
	      	<div class="col-md-8">
	      		<select name="type_payment" class="form-control">
	      	';
	      	$type_payment_array = explode(",", $type_payment);
	      	foreach ($type_payment_array as $type_payment_each) {
	      		if(strtolower($type_payment_each)!="credit"){
	      			echo '
	      			<option value="'.$type_payment_each.'">'.$type_payment_each.'</option>
	      			';
	      		}
	      	}
	      	echo '
		      	</select>
	      	</div>
	      	</div>


	      	<div class="form-group">
	      		<label for="itemname" class="col-md-4">Payments:</label>
	      	<div class="col-md-8">
	      		<input type="number" class="form-control" name="payment" id="payment-textbox" placeholder="Payment" required="required" min="1">
	      	</div>
	      	</div>

	      	<div class="form-group">
	      		<label for="itemname" class="col-md-4">Reference Number:</label>
	      	<div class="col-md-8">
	      		<input type="text" class="form-control" name="comments" id="referrence-textbox" placeholder="Reference Number">
	      	</div>
	      	</div>


	      	</form>

	      </div>
	      <div class="modal-footer">
	        <button type="submit" form="credits-payments" name="submit" class="btn btn-primary save">Save</button>
	        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	      </div>
	    </div>

	  </div>
	</div>

	';


	?>
  </div>
</div>
</body>
</html>
<?php mysql_close($connect);?>
  <script>
$("[data-toggle=popover]")
.popover({html:true})
</script>