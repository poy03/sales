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
$sql_res=mysql_query("SELECT DISTINCT category FROM tbl_items WHERE category LIKE '%$q%' ORDER BY category");
while($row=mysql_fetch_array($sql_res))
{

$category=$row['category'];
$b_category='<strong>'.$q.'</strong>';

$final_category = str_ireplace($q, $b_category, $category);

?>
<div class="show" align="left" href='<?php echo "$category"; ?>'><?php echo "$final_category";?></div>
<?php
}
echo "<div class='cusclose' align='left'>Close</div>";
}
?>
