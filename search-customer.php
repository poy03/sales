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
$q=$_POST['search'];
$sql_res=mysql_query("SELECT * FROM tbl_customer WHERE companyname LIKE '%$q%' AND deleted = 0 ORDER BY companyname");
while($row=mysql_fetch_array($sql_res))
{
$customerID=$row['customerID'];
$contactperson=$row['contactperson'];
$companyname=$row['companyname'];
$b_companyname='<strong>'.$q.'</strong>';

$final_companyname = str_ireplace($q, $b_companyname, $companyname);

?>
<div class="show" align="left" my='<?php echo "$customerID"; ?>' href='<?php echo "$companyname"; ?>'><?php echo "$final_companyname<br><i style='font-size:75%;'>$contactperson</i>";?></div>
<?php
}
echo "<div class='cusclose' align='left'>Close</div>";
}
?>
