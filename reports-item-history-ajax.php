<?php
ob_start();
session_start();
$accountID=@$_SESSION['accountID'];
$page=@$_POST['page'];

include 'db.php';


?>


		<?php
		$page = $_POST["page"];
		if(!isset($page)){$page=1;}elseif($page<=0){$page=1;}
		$maxitem = $maximum_items_displayed; // maximum items
		$limit = ($page*$maxitem)-$maxitem;



		$query = "SELECT * FROM tbl_items_history";
		$query.= " WHERE";
		// $_POST["category"] = "Categ";
		if(
			(isset($_POST["serial_number"])&&$_POST["serial_number"]!="")||
			(isset($_POST["date_from"])&&$_POST["date_from"]!="")||
			(isset($_POST["date_to"])&&$_POST["date_to"]!="")||
			(isset($_POST["item_name"])&&$_POST["item_name"]!="")||
			(isset($_POST["category"])&&$_POST["category"]!="")
			){

			if(isset($_POST["item_name"])&&$_POST["item_name"]!=""){
				$query.=" itemID ='".$_POST["item_name"]."' AND";
			}

			if(isset($_POST["serial_number"])&&$_POST["serial_number"]!=""){
				$query.=" item_detail_id ='".$_POST["serial_number"]."' AND";
			}
			if((isset($_POST["date_to"])&&$_POST["date_to"]!="")&&(isset($_POST["date_from"])&&$_POST["date_from"]!="")){
				$query.=" date_time BETWEEN '".strtotime($_POST["date_from"])."' AND '".strtotime($_POST["date_to"])."' AND";
			}
			if(isset($_POST["category"])&&$_POST["category"]!=""){

				$category_query = mysql_query("SELECT * FROM tbl_items WHERE category='".$_POST["category"]."'");
				if(mysql_num_rows($category_query)!=0){
					$i=0;
					$query.=" (";
					while($category_row=mysql_fetch_assoc($category_query)){
						$category_itemID = $category_row["itemID"];
						if($i==0){
							$query.=" itemID='$category_itemID'";
						}else{
							$query.=" OR itemID='$category_itemID'";
						}
						$i++;
					}
					$query.=") AND";
				}
			}

		}
		if($_POST["type"]=="all"){
			$query.=" itemID!=''";
		}else{
			$query.=" type='".$_POST["type"]."'";
		}
		$query.= " ORDER BY id DESC";



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

		$history_query = mysql_query($query);
		if(mysql_num_rows($history_query)!=0){
			while($history_row=mysql_fetch_assoc($history_query)){
				$itemID = $history_row["itemID"];
				$item_data = mysql_fetch_assoc(mysql_query("SELECT * FROM tbl_items WHERE itemID='$itemID'"));
				$item_detail_id = $history_row["item_detail_id"];
				$serial_number = $history_row["serial_number"];
				$type = $history_row["type"];
				$reference_number = $history_row["reference_number"];
				$referenceID = $history_row["referenceID"];
				$description = $history_row["description"];
				$date_time = $history_row["date_time"];
				$quantity = $history_row["quantity"];
				$user_accountID = $history_row["accountID"];
				$user_data = mysql_fetch_assoc(mysql_query("SELECT * FROM tbl_users WHERE accountID='$user_accountID'"));
				if(strtolower($type)=="sales"||strtolower($type)=="sales delete"){
					$referenceID = "<a href='sales-re?id=".$referenceID."'>S".sprintf("%06d",$referenceID)."</a>";
				}elseif(strtolower($type)=="purchase"||strtolower($type)=="stock in"||strtolower($type)=="purchase delete"){
					$referenceID = "<a href='receiving-re?id=".$referenceID."'>R".sprintf("%06d",$referenceID)."</a>";
				}
				echo "
				<tr>
					<td>".date("m/d/Y",$date_time)."</td>
					<td>".$type."</td>
					<td>".$item_data["category"]."</td>
					<td>".$item_data["itemname"]."</td>
					<td>".$serial_number."</td>
					<td>".$description."</td>
					<td>".$quantity."</td>
					<td>".$reference_number."</td>
					<td>".$referenceID."</td>
					<td>".$user_data["username"]."</td>
				</tr>
				";
			}
		}

		?>


		<?php
					echo "
				<tr>
					<th class='prints' colspan='20' style='text-align:center'>
					<ul class='pagination'>
					";
					$url="?";
					$cnt=0;
					if($page>1){
						$back=$page-1;
						echo "<li><a href = '#' class='paging' id='1'>&laquo;&laquo;</a></li>";	
						echo "<li><a href = '#' class='paging' id='$back'>&laquo;</a></li>";	
						for($i=($page-$maxpage);$i<$page;$i++){
							if($i>0){
								echo "<li><a href = '#' class='paging' id='$i'>$i</a></li>";	
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
							echo "<li><a href = '#' class='paging' id='$i'>$i</a></li>";	
						}
						if($cnt==$maxpage){
							break;
						}
					}
					
					$cnt=0;
					for($i=($page+$maxpage);$i<=$lastpage;$i++){
						$cnt++;
						echo "<li><a href = '#' class='paging' id='$i'>$i</a></li>";	
						if($cnt==$maxpage){
							break;
						}
					}
					if($page!=$lastpage&&$numitem>0){
						$next=$page+1;
						echo "<li><a href = '#' class='paging' id='$next'>&raquo;</a></li>";
						echo "<li><a href = '#' class='paging' id='$lastpage'>&raquo;&raquo;</a></li>";
					}
					echo "</ul></th><span class='page' >Page $page</span>
					</tr>
					";
					
					?>