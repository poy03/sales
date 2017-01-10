<!DOCTYPE html>
<html lang="en">
<head>
  <title>Bootstrap Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="style.css">
  <script src="jquery.min.js"></script>
  <script src="main.js"></script>
  <script src="js/bootstrap.min.js"></script>
</head>
<body>
<div class="container-fluid">

  <div class="row bottom">
    <div class="col-md-3 col-md-push-4">
	<form class="form-signin login"  action='index' method='post'>
		<input name='username' type="text" id="inputEmail" class="form-control" placeholder="Username" required autofocus>
		<input name='password' type="password" id="inputPassword" class="form-control" placeholder="Password" required>
		<input type='submit' class="btn btn-lg btn-primary btn-block btn-signin" value='Sign in' name='submit'>
	</form>
    </div>
  </div>
</div>

</body>
</html>
