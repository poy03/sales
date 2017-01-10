<?php
ob_start();
session_start();
$accountID=@$_SESSION['accountID'];


include 'db.php';

// if($_POST){
	// error_reporting(0);
	$page = $_POST["page"];

	// $page = 2;


	if(!isset($page)){$page=1;}elseif($page<=0){$page=1;}
	$maxitem = $maximum_items_displayed; // maximum items
	$limit = ($page*$maxitem)-$maxitem;




	$query = "SELECT * FROM tbl_payments WHERE type_payment != 'credit' AND credit_status='1'";

	if((isset($_POST["date_from"])&&$_POST["date_from"]!="")||isset($_POST["date_to"])&&$_POST["date_to"]!=""){
		$date_from = strtotime($_POST["date_from"]);
		$date_to = strtotime($_POST["date_to"]);
		$query .= " AND date BETWEEN '$date_from' AND '$date_to'";
	}


	if((isset($_POST["comments"])&&$_POST["comments"]!="")){
		$comments = $_POST["comments"];
		$query .= " AND comments LIKE '%$comments%'";
	}

	$numitemquery = mysql_query($query);
	$numitem = mysql_num_rows($numitemquery);
	$query.=" LIMIT $limit, $maxitem";
	// echo "<tr><td>";
	// echo $query;
	// echo "</td></tr>";

	if(($numitem%$maxitem)==0){
		$lastpage=($numitem/$maxitem);
	}else{
		$lastpage=($numitem/$maxitem)-(($numitem%$maxitem)/$maxitem)+1;
	}
		$maxpage = 3;



	$credit_query = mysql_query($query);
	if(mysql_num_rows($credit_query)!=0){
		while($credit_row=mysql_fetch_assoc($credit_query)){
			$current_credit_status_data = mysql_fetch_assoc(mysql_query("SELECT * FROM tbl_payments WHERE type_payment='credit' AND orderID='".$credit_row["orderID"]."'"));

			$customer_data = mysql_fetch_assoc(mysql_query("SELECT * FROM tbl_customer WHERE customerID = '".$current_credit_status_data["customerID"]."'"));
			echo "
			<tr>
				<td>".date("m/d/Y",$credit_row["date"])."</td>
				<td><a href='sales-re?id=".$credit_row["orderID"]."'>S".sprintf("%06d",$credit_row["orderID"])."</a></td>
				<td>".number_format($credit_row["payment"],2)."</td>
				<td>".number_format($current_credit_status_data["payment"],2)."</td>
				<td>".$customer_data["companyname"]."</td>
				<td>".$credit_row["comments"]."</td>
			</tr>
			";
		}
	}


					echo "
				<tr>
					<th class='prints' colspan='20' style='text-align:center'>
					<ul class='pagination'>
					";
					$url="?";
					$cnt=0;
					if($page>1){
						$back=$page-1;
						echo "<li><a href = '#' class='paging-paid-accounts' id='1'>&laquo;&laquo;</a></li>";	
						echo "<li><a href = '#' class='paging-paid-accounts' id='$back'>&laquo;</a></li>";	
						for($i=($page-$maxpage);$i<$page;$i++){
							if($i>0){
								echo "<li><a href = '#' class='paging-paid-accounts' id='$i'>$i</a></li>";	
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
							echo "<li><a href = '#' class='paging-paid-accounts' id='$i'>$i</a></li>";	
						}
						if($cnt==$maxpage){
							break;
						}
					}
					
					$cnt=0;
					for($i=($page+$maxpage);$i<=$lastpage;$i++){
						$cnt++;
						echo "<li><a href = '#' class='paging-paid-accounts' id='$i'>$i</a></li>";	
						if($cnt==$maxpage){
							break;
						}
					}
					if($page!=$lastpage&&$numitem>0){
						$next=$page+1;
						echo "<li><a href = '#' class='paging-paid-accounts' id='$next'>&raquo;</a></li>";
						echo "<li><a href = '#' class='paging-paid-accounts' id='$lastpage'>&raquo;&raquo;</a></li>";
					}
					echo "</ul></th><span class='page' >Page $page</span>
					</tr>
					";
					

// }