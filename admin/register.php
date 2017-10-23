<?php
	require_once('../functions.php');
	if(loggedin())
		header("Location: index.php");
	else if(isset($_POST['action'])) {
		$username = mysql_real_escape_string($_POST['username']);
		if($_POST['action']=='register') {
			$email = mysql_real_escape_string($_POST['email']);
			$id_ev = $_POST['id_ev'];
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
					$sql="INSERT INTO `users` ( `username` , `salt` , `hash` , `email`, `id_ev` ) VALUES ('".$username."', '$salt', '$hash', '".$email."', '$id_ev')";
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
			<div id="myTabContent" class="tab-content">
                <h2>Registrasi</h2>
                <hr/>
				<div class="tab-pane active" id="register">
					<form method="post" action="register.php" class="bs-docs-example form-horizontal">
						<input type="hidden" name="action" value="register"/>
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
                            <label for="inputIcon">Password</label>
							<div class="controls">
								<div class="input-prepend"> <span class="add-on">
									<i class="icon-envelope"></i></span>
									<input type="text" name="password" class="form-control">
									</input>
								</div>
							</div>
                            <br/>
							<label class="control-label" for="inputIcon">Email</label>
							<div class="controls">
								<div class="input-prepend"> <span class="add-on"><i class="icon-envelope"></i></span>
									<input type="text" name="email" class="form-control" />
								</div>
							</div>
                            <br/>
							<label class="control-label" for="inputIcon">Event</label>
							<div class="controls">
								<div class="input-prepend"> <span class="add-on"><i class="icon-envelope"></i></span>
                                    <select name="id_ev" class="form-control">
                                        <?php 
				                            connectdb();
                                            $query = "SELECT title, ev_id FROM event";
                                            $result = mysql_query($query);
                                            while ($row = mysql_fetch_array($result)) {
                                        ?>
                                        <option value="<?php echo $row['ev_id']?>"><?php echo $row['title']?></option>   
                                        <?php 
                                            }
                                        ?>
                                    </select>
								</div>
							</div>
						</div>
						<br/>
						<input class="btn btn-info btn-block" type="submit" name="submit" value="Simpan"/>
					</form>
				</div>
			</div>
		</div>
		<?php
		include('../copyright.php');
		?>
</div>
<?php
	include('footer.php');
?>
