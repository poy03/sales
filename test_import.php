<!DOCTYPE html>
<html lang="en">
<form action="test.php"  enctype='multipart/form-data' method="post">
	<input type="file" name='sql' accept=".sql">
	<input type="submit" name='submit'>
</form>
</html>
<?php
	if(isset($_POST['submit'])){
		$sql = $_FILES['sql'];
		var_dump($sql);
	}
?>