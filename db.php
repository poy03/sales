<?php
date_default_timezone_set('Asia/Manila');
ini_set('max_execution_time', 300);
ini_set('max_input_vars', 3000);
if(isset($_COOKIE["LOGGED"])){
	$accountID = explode("c", $_COOKIE["LOGGED"]);
	$accountID = array_pop($accountID);
}elseif(isset($_SESSION['accountID'])){
	$accountID = $_SESSION['accountID'];
}
$type='';
error_reporting(0);
$list_modules = array();
define("APP_VERSION","1.0");
define("DB_NAME","pos_main");
define("DB_USER","root");
define("DB_PASSWORD","");

$connect = mysql_connect("localhost",DB_USER,DB_PASSWORD);
mysql_select_db(DB_NAME);

$app_db = mysql_query("SELECT * FROM app_config");
if(mysql_num_rows($app_db)==0){
	mysql_query("INSERT INTO app_config VALUES ('','POS','CASH,CREDIT,BANK','','','','50','')");
}


			$configquery=mysql_query("SELECT * FROM app_config");
			if(mysql_num_rows($configquery)!=0){
				while($configrow=mysql_fetch_assoc($configquery)){
					$app_name=$configrow["app_name"];
					$type_payment=$configrow["type_payment"];
					$address=$configrow["address"];
					$contact_number=$configrow["contact_number"];
					$app_company_name=$configrow["app_company_name"];
					$maximum_items_displayed=$configrow["maximum_items_displayed"];
					$logo=$configrow["logo"];
				}
			}else{
				$app_name = $type_payment = $address = $contact_number = $app_company_name = $maximum_items_displayed = $logo = "";
			}
			$items = $customers = $sales = $receiving = $users = $reports = $suppliers = $credits = $expenses= '0';
			$badgequery = mysql_query("SELECT * FROM tbl_cart WHERE accountID='$accountID'");
			$badge = mysql_num_rows($badgequery);
			$badge_r_query = mysql_query("SELECT * FROM tbl_cart_receiving WHERE accountID='$accountID'");
			$badge_r = mysql_num_rows($badge_r_query);
			if($badge==0){
				$badge='';
			}
			if($badge_r==0){
				$badge_r='';
			}
			$timezone  = 0;
			$date_now =gmdate("m/d/Y", time() + 3600*($timezone+date("I")));

			// echo $date_now;
			$badge_credit_query = mysql_query("SELECT * FROM tbl_payments WHERE type_payment = 'CREDIT' AND date_due <= '".strtotime($date_now)."' AND deleted='0'");
			$badge_credit = mysql_num_rows($badge_credit_query);
			if($badge_credit==0){
				$badge_credit='';
			}
			
			$badge_i = 0;
			$badge_items_query = mysql_query("SELECT * FROM tbl_items WHERE deleted='0'");
			if(mysql_num_rows($badge_items_query)!=0){
				while($badge_items_row = mysql_fetch_assoc($badge_items_query)){
					$reorder_badge = $badge_items_row["reorder"];
					$itemID_badge = $badge_items_row["itemID"];

					$remaining_badge_data = mysql_query("SELECT * FROM tbl_items_detail WHERE itemID='$itemID_badge' AND deleted='0' AND quantity='1'");
					$remaining_badge = mysql_num_rows($remaining_badge_data);

					if($reorder_badge<=$remaining_badge){
						$badge_i++;
					}

				}	
				if($badge_i==0){
					$badge_i='';
				}			
			}

			

if(isset($accountID)){
$accquery = mysql_query("SELECT * FROM tbl_users WHERE accountID='$accountID'");
if(mysql_num_rows($accquery)!=0){
	while($accrow=mysql_fetch_assoc($accquery)){
		$type=$accrow["type"];
		$employee_name=$accrow["employee_name"];
		$themes=$accrow["themes"];
		$items=$accrow["items"];
		$customers=$accrow["customers"];
		$sales=$accrow["sales"];
		$receiving=$accrow["receiving"];
		$users=$accrow["users"];
		$reports=$accrow["reports"];
		$suppliers = $accrow["suppliers"];
		$credits = $accrow["credits"];
		$expenses = $accrow["expenses"];
		$total_modules = $items + $customers + $sales + $receiving + $users+ $reports + $suppliers + $credits + $expenses + 2;

		($items==1?$items_module = 1:$items_module = 0);
		($customers==1?$customers_module = 2:$customers_module = 0);
		($sales==1?$sales_module = 3:$sales_module = 0);
		($receiving==1?$receiving_module = 4:$receiving_module = 0);
		($users==1?$users_module = 5:$users_module = 0);
		($reports==1?$reports_module = 6:$reports_module = 0);
		($suppliers==1?$suppliers_module = 7:$suppliers_module = 0);
		($credits==1?$credits_module = 8:$credits_module = 0);
		($expenses==1?$expenses_module = 9:$expenses_module = 0);
		$list_modules = array($items_module,$customers_module,$sales_module,$receiving_module,$users_module,$reports_module,$suppliers_module,$credits_module,$expenses_module,10,11);
		// print_r($list_modules);
		$list_modules = array_unique($list_modules);
		sort($list_modules);
		// array_push($list_modules,10,11);
		// $list_modules = array_flip($list_modules);
		if($total_modules<11){
			array_shift($list_modules);
		}
		// print_r($list_modules);
		// $total_modules = 5;
	}
	if($type=='admin'){
		$logged=2;
	}else{
		$logged=1;
	}
}else{
	$logged=0;
}
}else{
	$logged=0;
	$type='';
}

//auto backup if it has already a backup each day
$filename_of_db = DB_NAME."_(".date("m-d-Y").").sql";
if(!file_exists("auto_backup/".$filename_of_db)){
	$auto_backup = new Auto_backup;
	$auto_backup->export_tables("localhost",DB_USER,DB_PASSWORD,DB_NAME);
}




class Template
{

	public function header($module,$badge,$display){
		$display_string = " ";
		$no_display_string = "";
		($badge==0?$badge="":false);
		switch ($module) {
			case '1':
				# code...
				$string_to_display = "Items";
				($display==1?$display_string=$string_to_display:$display_string=$string_to_display[0]);
				($display==1?false:$no_display_string="data-balloon='".$string_to_display."' data-balloon-pos='down'");
				return "<li $no_display_string id='$string_to_display'><a href='item' tabindex='-1'><span class = 'glyphicon glyphicon-briefcase'></span> $display_string <span class = 'badge'>$badge</span></a></li>";
				
				break;
			case '2':
				# code...

					$string_to_display = "Customers";
				($display==1?$display_string=$string_to_display:$display_string=$string_to_display[0]);
				($display==1?false:$no_display_string="data-balloon='".$string_to_display."' data-balloon-pos='down'");
				return "<li $no_display_string id='$string_to_display'><a href='customer' tabindex='-1'><span class = 'glyphicon glyphicon-user'></span> $display_string </a></li>";
				break;
			case '3':
				# code...
				$string_to_display = "Sales";
				($display==1?$display_string=$string_to_display:$display_string=$string_to_display[0]);
				($display==1?false:$no_display_string="data-balloon='".$string_to_display."' data-balloon-pos='down'");
				return "<li $no_display_string id='$string_to_display'><a href='sales' tabindex='-1'><span class = 'glyphicon glyphicon-shopping-cart'></span> $display_string <kbd>F2</kbd> <span class = 'badge'>$badge</span> </a></li>";
				break;							
			case '4':
				# code...
				$string_to_display = "Receiving";
				($display==1?$display_string=$string_to_display:$display_string=$string_to_display[0]);
				($display==1?false:$no_display_string="data-balloon='".$string_to_display."' data-balloon-pos='down'");
				return "<li $no_display_string id='$string_to_display'><a href='receiving' tabindex='-1'><span class = 'glyphicon glyphicon-download-alt'></span> $display_string </a></li>";
				break;
			case '5':
				# code...
				$string_to_display = "Users";
				($display==1?$display_string=$string_to_display:$display_string=$string_to_display[0]);
				($display==1?false:$no_display_string="data-balloon='".$string_to_display."' data-balloon-pos='down'");
				return "<li $no_display_string id='$string_to_display'><a href='users' tabindex='-1'><span class = 'glyphicon glyphicon-user'></span> $display_string </a></li>";
				break;
			case '6':
				# code...
				$string_to_display = "Reports";
				($display==1?$display_string=$string_to_display:$display_string=$string_to_display[0]);
				($display==1?false:$no_display_string="data-balloon='".$string_to_display."' data-balloon-pos='down'");
				return "<li $no_display_string id='$string_to_display'><a href='reports' tabindex='-1'><span class = 'glyphicon glyphicon-stats'></span> $display_string <span class = 'badge'>$badge</span></a></li>";
				break;
			case '7':
				# code...
				$string_to_display = "Suppliers";
				($display==1?$display_string=$string_to_display:$display_string=$string_to_display[0]);
				($display==1?false:$no_display_string="data-balloon='".$string_to_display."' data-balloon-pos='down'");
				return "<li $no_display_string id='$string_to_display'><a href='suppliers' tabindex='-1'><span class='glyphicon glyphicon-phone'></span> $display_string </a></li>";
				break;
			case '8':
				# code...
				$string_to_display = "Credits";
				($display==1?$display_string=$string_to_display:$display_string=$string_to_display[0]);
				($display==1?false:$no_display_string="data-balloon='".$string_to_display."' data-balloon-pos='down'");
				return "<li $no_display_string id='$string_to_display'><a href='credits' tabindex='-1'><span class = 'glyphicon glyphicon-copyright-mark'></span> $display_string <span class='badge'>$badge</span></a></li>";
				break;				
			case '9':
				# code...
				$string_to_display = "Expenses";
				($display==1?$display_string=$string_to_display:$display_string=$string_to_display[0]);
				($display==1?false:$no_display_string="data-balloon='".$string_to_display."' data-balloon-pos='down'");
				return "<li $no_display_string id='$string_to_display'><a href='expenses' tabindex='-1'><span class='glyphicon glyphicon-usd'></span> $display_string </a></li>";
				break;							
			default:
				# code...
				return "";
				break;
		}
	}	
}


class Auto_backup
{
	public function export_tables($host,$user,$pass,$name,  $tables=false, $backup_name=false )
	{
	$link = mysqli_connect($host,$user,$pass,$name);
	// Check connection
	if (mysqli_connect_errno())   {   echo "Failed to connect to MySQL: " . mysqli_connect_error();   }

	mysqli_select_db($link,$name);
	mysqli_query($link,"SET NAMES 'utf8'");

	//get all of the tables
	if($tables === false)
	{
	    $tables = array();
	    $result = mysqli_query($link,'SHOW TABLES');
	    while($row = mysqli_fetch_row($result))
	    {
	        $tables[] = $row[0];
	    }
	}
	else
	{
	    $tables = is_array($tables) ? $tables : explode(',',$tables);
	}
	$return='';
	//cycle through
	foreach($tables as $table)
	{
	    $result = mysqli_query($link,'SELECT * FROM '.$table);
	    $num_fields = mysqli_num_fields($result);

	    $row2 = mysqli_fetch_row(mysqli_query($link, 'SHOW CREATE TABLE '.$table));
	    $return.= "\n\n".$row2[1].";\n\n";

	    for ($i = 0; $i < $num_fields; $i++) 
	    {
	        $st_counter= 0;
	        while($row = mysqli_fetch_row($result))
	        {
	            //create new command if when starts and after 100 command cycle
	            if ($st_counter%100 == 0 || $st_counter == 0 )  {
	                $return.= "\nINSERT INTO ".$table." VALUES";
	            }


	            $return.="\n(";
	            for($j=0; $j<$num_fields; $j++) 
	            {
	                $row[$j] = addslashes($row[$j]);
	                $row[$j] = str_replace("\n","\\n",$row[$j]);
	                if (isset($row[$j])) { $return.= '"'.$row[$j].'"' ; } else { $return.= '""'; }
	                if ($j<($num_fields-1)) { $return.= ','; }
	            }
	            $return.=")";


	            //create new command if when starts and after 100 command cycle (but detect this 1 cycle earlier !)
	            if ( ($st_counter+1)%100 == 0  && $st_counter != 0 )    {   $return.= ";";  }
	            else                                                {   $return.= ",";  }
	            //+++++++
	            $st_counter = $st_counter +1 ;
	        }
	        //as we cant detect WHILE loop end, so, just detect, if last command ends with comma(,) then replace it with semicolon(;)
	        if (substr($return, -1) == ',') {$return = substr($return, 0, -1). ';'; }
	    }
	    $return.="\n\n\n";
	}

	//save file
	$backup_name = $backup_name ? $backup_name : $name."_(".date("m-d-Y").")v".APP_VERSION.".sql";
	// file_put_contents("backups/".$backup_name,$return);
	file_put_contents("auto_backup/".$backup_name,$return);
	// if(file_exists("E:/auto_backup/")){
	// 	file_put_contents("E:/POS_auto_backup/".$backup_name,$return);
	// }else{
	// 	mkdir("E:/POS_auto_backup/");
	// 	file_put_contents("E:/POS_auto_backup/".$backup_name,$return);
	// }
	// file_put_contents("D:/auto_backup/".$backup_name,$return);
	// die('SUCCESS. Download BACKUP file: <a target="_blank" href="'.$backup_name.'">'.$backup_name.'</a> <br/><br/>After download, <a target="_blank" href="?delete_filee='.$backup_name.'">Delete it!</a> ');
	// header("location:backups/".$backup_name);
	}

/*	if (!empty($_GET['delete_filee'])){ chdir(dirname(__file__));       
	if  (unlink($_GET['delete_filee'])) {die('file_deleted');} 
	else                                {die("file doesnt exist");}
	}*/

}



?>