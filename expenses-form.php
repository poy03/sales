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



include 'db.php';

	
?>
<?php
if(isset($_POST["add"])){

}
if(isset($_POST["save"])){
	$description = $_POST["description"];
	$amount = $_POST["amount"];
	$comments = $_POST["comments"];
	$i = 0;
	foreach ($description as $expenses) {
		if($expenses!=""){
			$expenses = mysql_real_escape_string(htmlspecialchars(trim($expenses)));
			$comments[$i] = mysql_real_escape_string(htmlspecialchars(trim($comments[$i])));
			$amount[$i] = mysql_real_escape_string(htmlspecialchars(trim($amount[$i])));
			$date_of_expense = strtotime(date("m/d/Y"));
			$time_expended = strtotime(date("h:i:s A"));
			mysql_query("INSERT INTO tbl_orders_expenses (date_of_expense,time_expended,expenses,comments,accountID,description) VALUES ('$date_of_expense','$time_expended','$amount[$i]','$comments[$i]','$accountID','$expenses')");
		}
		$i++;
	}
}

?>