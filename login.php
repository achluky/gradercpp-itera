<?php
	require_once('functions.php');
	if(loggedin())
		header("Location: index.php");
	else if(isset($_POST['action'])) {
		$username = mysql_real_escape_string($_POST['username']);
		if($_POST['action']=='login') {
			if(trim($username) == "" or trim($_POST['password']) == "")
				header("Location: login.php?derror=1");
			else {
				connectdb();
				$query = "SELECT salt,hash FROM users WHERE username='".$username."'";
				$result = mysql_query($query);
				$fields = mysql_fetch_array($result);
				$currhash = crypt($_POST['password'], $fields['salt']);
				if($currhash == $fields['hash']) {
					$_SESSION['username'] = $username;
					header("Location: index.php");
				} else {
					header("Location: login.php?error=1");
				}
			}
		} else if($_POST['action']=='register') {
			$email = mysql_real_escape_string($_POST['email']);
			if(trim($username) == "" or trim($_POST['password']) == "" or trim($email) == "")
				header("Location: login.php?derror=1"); // empty entry
			else {
				connectdb();
				$query = "SELECT salt,hash FROM users WHERE username='".$username."'";
				$result = mysql_query($query);
				if(mysql_num_rows($result)!=0){
					header("Location: login.php?exists=1");
				} else {
					$salt = randomAlphaNum(5);
					$hash = crypt($_POST['password'], $salt);
					$sql="INSERT INTO `users` ( `username` , `salt` , `hash` , `email` ) VALUES ('".$username."', '$salt', '$hash', '".$email."')";
					mysql_query($sql);
					header("Location: login.php?registered=1");
				}
			}
		}
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<title><?php echo(getName()); ?> Login</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">
<link href="css/bootstrap.min.css" rel="stylesheet">
<style>
body {
	padding-top: 20px;
	padding-bottom: 20px;
}
.navbar {
  	margin-bottom: 20px;
}
</style>
</head>
<body>
<div class="container">
	<nav class="navbar navbar-inverse">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
		<a class="navbar-brand" href="#"><?php echo(getName()); ?></a> 
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
			<li class="active"><a id="Clock"></a><li>
            <li class="active"><a href="<?php echo $base_url;?>"><i class="glyphicon glyphicon-home">&nbsp;</i>Beranda</a></li>
          </ul>
        </div>
      </div>
    </nav>
	<?php
        if(isset($_GET['logout']))
          echo("<div class=\"alert alert-info\"><a class=\"close\" data-dismiss=\"alert\" href=\"#\">×</a>\n<i class=\"glyphicon glyphicon-info-sign\">&nbsp;</i> You have logged out successfully!\n</div>");
        else if(isset($_GET['error']))
          echo("<div class=\"alert alert-danger\"><a class=\"close\" data-dismiss=\"alert\" href=\"#\">×</a>\n<i class=\"glyphicon glyphicon-info-sign\">&nbsp;</i>Incorrect username or password!\n</div>");
        else if(isset($_GET['registered']))
          echo("<div class=\"alert alert-success\"><a class=\"close\" data-dismiss=\"alert\" href=\"#\">×</a>\n<i class=\"glyphicon glyphicon-info-sign\">&nbsp;</i>You have been registered successfully! Login to continue.\n</div>");
        else if(isset($_GET['exists']))
          echo("<div class=\"alert alert-danger\"><a class=\"close\" data-dismiss=\"alert\" href=\"#\">×</a>\n<i class=\"glyphicon glyphicon-info-sign\">&nbsp;</i>User already exists! Please select a different username.\n</div>");
        else if(isset($_GET['derror']))
          echo("<div class=\"alert alert-danger\"><a class=\"close\" data-dismiss=\"alert\" href=\"#\">×</a>\n<i class=\"glyphicon glyphicon-info-sign\">&nbsp;</i>Please enter all the details asked before you can continue!\n</div>");
    ?>
		<div class="well well-large">
			<!-- <ul class="nav nav-tabs"> -->
				<!-- <li class="active"><a href="#login" data-toggle="tab">Login</a></li> -->
				<!-- <li class=""><a href="#create" data-toggle="tab">Integrasi dengan SSO</a></li> -->
			<!-- </ul> -->
			<br/>
			<div id="myTabContent" class="tab-content">
				<div class="tab-pane active" id="login">
					<form method="post" action="login.php" class="bs-docs-example form-horizontal">
						<input type="hidden" name="action" value="login"/>
						<div class="control-group">
							<label for="inputIcon">Username</label>
							<div class="controls">
								<div class="input-prepend"> <span class="add-on">
									<i class="icon-envelope"></i></span>
									<input type="text" name="username" class="form-control">
									</input>
								</div>
							</div>
							<br/>
							<label class="control-label" for="inputIcon">Password</label>
							<div class="controls">
								<div class="input-prepend"> <span class="add-on"><i class="icon-envelope"></i></span>
									<input type="password" name="password" class="form-control" />
								</div>
							</div>
						</div>
						<br/>
						<input class="btn btn-info btn-block" type="submit" name="submit" value="Login"/>
					</form>
				</div>
				<!-- <div class="tab-pane" id="create">
					<a class="btn btn-danger btn-facebook">
							<i class="fa fa-facebook"> </i> Login dengan SSO ITERA
					</a>
				</div> -->
			</div>
		</div>
		<?php
		include('copyright.php');
		?>
</div>
<?php
	include('footer.php');
?>
