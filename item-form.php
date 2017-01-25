<?php
ob_start();
session_start();
$accountID=@$_SESSION['accountID'];

include 'db.php';

if($_POST){
	$x = array();
	$x = @$_POST["select"];
	$_SESSION["selectitem"]=$x;

	if($_SESSION["selectitem"]!=NULL){
		if($_POST["type"]=="delete"){
			$data = array("type"=>"item-delete");
		}else{
			$data = array("type"=>"item-edit");
		}

		echo json_encode($data);
	}
}

?>