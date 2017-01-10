<?php
ob_start();
session_start();
$accountID=@$_SESSION['accountID'];
$selected=@$_SESSION['selectcredit'];
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

?>
<?php
if(isset($_POST["save"])){
	$payment = $_POST["payment"];
	$soaID = @$_POST["soaID"];
	$amount = $_POST["amount"];
	$type_payment_post = $_POST["type_payment"];
	$customerID = $_POST["customerID"];
	$paymentID = implode(",",$_POST["paymentID"]);
	$comments = $_POST["comments"];
	$timezone  = 0; //(GMT -5:00) EST (U.S. & Canada) 
	$datenow=gmdate("m/d/Y", time() + 3600*($timezone+date("I")));
	$timenow=gmdate("h:i:s A", time() + 3600*($timezone+date("I")));
	
	mysql_query("INSERT INTO tbl_credits VALUES ('','$accountID','$customerID','$amount','$datenow','$payment','$paymentID','$type_payment_post','$comments')");
	foreach($_POST["paymentID"] as $paymentID){
		mysql_query("UPDATE tbl_payments SET type_payment='$type_payment_post', date='$datenow', time='$timenow' WHERE paymentID='$paymentID'");
		echo "<br>";
	}
	$credit_query = mysql_query("SELECT * FROM tbl_credits ORDER BY creditID DESC LIMIT 0,1");
	while($credit_row=mysql_fetch_assoc($credit_query)){
		$creditID = $credit_row["creditID"];
	}
	if(isset($soaID)){
		mysql_query("UPDATE tbl_soa SET creditID='$creditID', paid='1' WHERE soaID='$soaID'");
		echo "<br>";
	}
// echo "$soa";
	header("location:credits-ar?id=".$creditID);
}

if(isset($_POST["delete"])){
	unset($_SESSION['selectcredit']);
	header("location:credits");
}

?>