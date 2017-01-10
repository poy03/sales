<?php
ob_start();
session_start();

#$connect = mysql_connect("localhost","qfcdavao_admin","_39a11nwpm");
#mysql_select_db("qfcdavao_dbinventory");

include 'db.php';

?>

<?php
if($_POST)
{
$delete=$_POST['delete'];
$id=$_POST['id'];
if(isset($delete)){
	unlink("backups/".$id);
}
}
?>
