<?php
	require_once('../functions.php');
	if(loggedin() and $_SESSION['username'] == 'admin')
		header("Location: index.php");
	else if(isset($_POST['password'])) {
		if(trim($_POST['password']) == "")
			header("Location: login.php?derror=1"); // empty entry
		else {
			// code to login the user and start a session
			connectdb();
			$query = "SELECT salt,hash FROM users WHERE username='admin'";
			$result = mysql_query($query);
			$fields = mysql_fetch_array($result);
			$currhash = crypt($_POST['password'], $fields['salt']);
			if($currhash == $fields['hash']) {
				$_SESSION['username'] = "admin";
				header("Location: index.php");
			} else
				header("Location: login.php?error=1");
		}
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<title><?= getName() ?> : Admin</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">
<link href="../css/bootstrap.min.css" rel="stylesheet">
<style>
body {
	padding-top: 20px;
}
.footer {
	text-align: center;
	font-size: 11px;
}
</style>
<link href="../css/bootstrap-responsive.css" rel="stylesheet">
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
	            <li class="active"><a href="<?php echo $base_url;?>admin"><i class="glyphicon glyphicon-home">&nbsp;</i>Home</a></li>
	          </ul>
	        </div><!--/.nav-collapse -->
	      </div>
	    </nav>

	<?php
        if(isset($_GET['logout']))
          echo("<div class=\"alert alert-info\"><a class=\"close\" data-dismiss=\"alert\" href=\"#\">×</a>\nYou have logged out successfully!\n</div>");
        else if(isset($_GET['error']))
          echo("<div class=\"alert alert-danger\"><a class=\"close\" data-dismiss=\"alert\" href=\"#\">×</a>\nIncorrect Password!\n</div>");
        else if(isset($_GET['derror']))
          echo("<div class=\"alert alert-danger\"><a class=\"close\" data-dismiss=\"alert\" href=\"#\">×</a>\nPlease enter all the details asked before you can continue!\n</div>");
    ?>
    <div class="well well-large">
		<div id="myTabContent" class="tab-content">
			<div class="tab-pane active" id="login">
				<form method="post" action="login.php" class="bs-docs-example form-horizontal">
					<input type="hidden" name="action" value="login"/>
					<div class="control-group">
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
		</div>
	</div>
	<?php
	include('../copyright.php');
	?>
</div>
<!-- /container -->

<?php
	include('footer.php');
?>
