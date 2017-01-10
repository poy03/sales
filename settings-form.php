<?php
ob_start();
session_start();
$accountID=@$_SESSION['accountID'];
$page=@$_GET['page'];
$cat=@$_GET['cat'];
$keyword=@$_GET['keyword'];
$search=@$_GET['search'];
			$by=@$_GET['by'];
			$order=@$_GET['order'];

#$connect = mysql_connect("localhost","qfcdavao_admin","_39a11nwpm");
#mysql_select_db("qfcdavao_dbinventory");

include 'db.php';


?>
<?php
if(isset($_POST["save"])){
	$app_name = mysql_real_escape_string(trim(htmlspecialchars(($_POST["app_name"]))));
	$app_company_name = mysql_real_escape_string(trim(htmlspecialchars(($_POST["app_company_name"]))));
	$address = mysql_real_escape_string(trim(htmlspecialchars(($_POST["address"]))));
	$contact_number = mysql_real_escape_string(trim(htmlspecialchars(($_POST["contact_number"]))));
	$type_payment = mysql_real_escape_string(trim(htmlspecialchars(($_POST["type_payment"]))));
	$themes = mysql_real_escape_string(trim(htmlspecialchars(($_POST["themes"]))));
	$maximum_items_displayed = mysql_real_escape_string(trim(htmlspecialchars(($_POST["maximum_items_displayed"]))));

	$has_credit = 0;
	$has_cash = 0;
	$type_payment_array = explode(",", $type_payment);
	foreach ($type_payment_array as $type_payment_each) {
		$type_payment_each = trim($type_payment_each);

		
		if(preg_match("/\bcredit\b/i", strtolower($type_payment_each))){
			$has_credit = 1;
			$type_payment_each = "CREDIT";
		}

		if(preg_match("/\bcash\b/i", strtolower($type_payment_each))){
			$has_cash = 1;
			$type_payment_each = "CASH";
		}

		$new_type_payment_array[] = strtoupper($type_payment_each);
	}
	$new_type_payment_array = array_unique($new_type_payment_array);
	$type_payment = implode(",", $new_type_payment_array);
	if($has_cash==0){
		$type_payment.=",CASH";
	}
	if($has_credit==0){
		$type_payment.=",CREDIT";
	}

	$file = $_FILES["logo"];
	// var_dump($file);
	echo "<br>";
	echo "<br>";
	if(isset($file)&&(	$file["type"]=='image/png' || $file["type"]=='image/jpeg' || $file["type"]=='image/pjpeg' || $file["type"]=='image/gif')){
		unlink($logo);
		move_uploaded_file ($file['tmp_name'],$file['name']);
		mysql_query("UPDATE app_config SET
		app_name='$app_name',
		app_company_name='$app_company_name',
		address='$address',
		contact_number='$contact_number',
		maximum_items_displayed='$maximum_items_displayed',
		logo='".$file['name']."',
		type_payment='$type_payment' WHERE id='1'");
		mysql_query("UPDATE tbl_users SET themes = '$themes' WHERE accountID='$accountID'");
	}else{
		mysql_query("UPDATE app_config SET
		app_name='$app_name',
		app_company_name='$app_company_name',
		address='$address',
		contact_number='$contact_number',
		maximum_items_displayed='$maximum_items_displayed',
		type_payment='$type_payment' WHERE id='1'");
		mysql_query("UPDATE tbl_users SET themes = '$themes' WHERE accountID='$accountID'");
	}
	// header("location:settings");
}
?>