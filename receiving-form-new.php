<?php
ob_start();
session_start();
$accountID=@$_SESSION['accountID'];
$page=@$_GET['page'];
$cat=@$_GET['cat'];
$id=@$_GET['id'];
$search=@$_GET['search'];
			$by=@$_GET['by'];
			$order=@$_GET['order'];

			include 'db.php';
			if(isset($_POST["savecontinue"])){

				$comments = mysql_real_escape_string(htmlspecialchars(trim($_POST["comments"])));
				$purchase_order = mysql_real_escape_string(htmlspecialchars(trim($_POST["purchase_order"])));
				$itemIDarray = $_POST["itemID"];
				$quantity = $_POST["quantity"];
				$costprice = $_POST["costprice"];
				$serial_number = $_POST["serial_number"];
				$receiving_costprice = $_POST["costprice"];
				$supplierID = $_POST["supplierID"];
				$supplier_name_post = mysql_real_escape_string(htmlspecialchars(trim($_POST["supplier_name_post"])));
				$mode = $_POST["m"];
				$total_cost = array();
				$timezone  = 0; //(GMT -5:00) EST (U.S. & Canada) 
				$date_received=gmdate("m/d/Y", time() + 3600*($timezone+date("I")));
				$time_received=gmdate("h:i:s A", time() + 3600*($timezone+date("I")));
				$i=0;
				$overall= 0;



				$receiving_query = mysql_query("SELECT * FROM tbl_cart_receiving WHERE accountID='$accountID'");
				while($receiving_row=mysql_fetch_assoc($receiving_query)){
					$itemID = $receiving_row["itemID"];
					// echo $serial_number[$i];

					$item_data = mysql_fetch_assoc(mysql_query("SELECT * FROM tbl_items WHERE itemID='$itemID'"));
					if($mode=='restock'){
						$total_cost=0;
						$receiving_costprice[$i] = 0;
					}else{
						$total_cost[$i] = $quantity[$i] * $costprice[$i];
					}
					


					$serial_number[$i] = mysql_real_escape_string(htmlspecialchars(trim($serial_number[$i])));
					if($item_data["has_serial"]==1){
						mysql_query("INSERT INTO tbl_items_detail (itemID,serial_number,has_serial) VALUES ('$itemID','$serial_number[$i]','1')");
						mysql_query("UPDATE tbl_items SET costprice='$costprice[$i]' WHERE itemID='$itemID'");

						$new_item_data = mysql_fetch_array(mysql_query("SELECT * FROM tbl_items_detail WHERE itemID='$itemID' ORDER BY item_detail_id DESC LIMIT 1"));
						$item_detail_id = $new_item_data["item_detail_id"];

						mysql_query("INSERT INTO tbl_receiving VALUES ('','$item_detail_id','$quantity[$i]','$receiving_costprice[$i]','$total_cost[$i]','$accountID','0','".strtotime($date_received)."','$serial_number[$i]')");

					}else{
						mysql_query("UPDATE tbl_items SET costprice='$costprice[$i]' WHERE itemID='$itemID'");
						$new_item_detail_data = mysql_fetch_assoc(mysql_query("SELECT * FROM tbl_items_detail WHERE itemID='$itemID'")); 
						$item_detail_id = $new_item_detail_data["item_detail_id"];
						mysql_query("INSERT INTO tbl_receiving VALUES ('','$item_detail_id','$quantity[$i]','$receiving_costprice[$i]','$total_cost[$i]','$accountID','0','".strtotime($date_received)."','$serial_number[$i]')");
					}


					$searchquery = mysql_query("SELECT * FROM tbl_orders_receiving ORDER BY orderID DESC LIMIT 0,1");
					if(mysql_num_rows($searchquery)==0){
							$orderID = 1;
					}else{
						while($searchrow = mysql_fetch_assoc($searchquery)){
							$orderID = $searchrow["orderID"]+1;
						}
					}

					$description = 'ReceiveID: <a href="receiving-re?id='.$orderID.'">R'.sprintf("%06d",$orderID).'</a>';
					if($mode=='restock'){
						$receiving_type = "Stock In";
					}else{
						$receiving_type = "Purchase";
					}
					$description = mysql_real_escape_string($_POST["comments"]);
					// var_dump($description);
					mysql_query("INSERT INTO tbl_items_history (item_detail_id,itemID,description,date_time,quantity,accountID,serial_number,type,referenceID,reference_number) VALUES ('$item_detail_id','$itemID','".$description."','".strtotime(date("m/d/Y"))."','$quantity[$i]','$accountID','$serial_number[$i]','$receiving_type','$orderID','$purchase_order')");


					$overall = $overall + $total_cost[$i];
					$updatequery = mysql_query("SELECT * FROM tbl_items_detail WHERE item_detail_id = '$item_detail_id'");
					while($updaterow=mysql_fetch_assoc($updatequery)){
						$remain = $updaterow["quantity"];
					}
					$remain = $remain + $quantity[$i];
					mysql_query("UPDATE tbl_items_detail SET quantity='$remain' WHERE item_detail_id='$item_detail_id'");
					if($item_data["has_serial"]==1){
						mysql_query("UPDATE tbl_items SET costprice='$costprice[$i]' WHERE itemID='$itemID'");
					}else{
						mysql_query("UPDATE tbl_items SET costprice='$costprice[$i]' WHERE itemID='$itemID'");
					}

					$i++;

				}
				
					if($mode=='restock'){
						$overall=0;
					}
				mysql_query("INSERT INTO tbl_orders_receiving (total_cost,date_received,time_received,accountID,comments,supplierID,mode) VALUES ('$overall','".strtotime($date_received)."','$time_received','$accountID','$comments','$supplierID','$mode')");
				$searchquery = mysql_query("SELECT * FROM tbl_orders_receiving ORDER BY orderID DESC LIMIT 0,1");
				while($searchrow = mysql_fetch_assoc($searchquery)){
					$orderID = $searchrow["orderID"];
				}
				mysql_query("UPDATE tbl_receiving SET orderID = '$orderID' WHERE accountID='$accountID' AND orderID ='0'");
				mysql_query("DELETE FROM tbl_cart_receiving WHERE accountID='$accountID'");
				echo json_encode(array("orderID"=>$orderID,"query"=>"INSERT INTO tbl_orders_receiving (total_cost,date_received,time_received,accountID,comments,supplierID,mode) VALUES ('$overall','".strtotime($date_received)."','$time_received','$accountID','$comments','$supplierID','$mode')"));
				?>

				
				
				<?php
				

			}