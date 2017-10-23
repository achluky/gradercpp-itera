<div id="navbar" class="collapse navbar-collapse">
  <ul class="nav navbar-nav">
		<li class="active">
			<a id="Clock"></a>
		<li>
		<li <?php echo ($menu=='index')? "class='active'" : ""; ?> >
			<a href="index.php"><i class="glyphicon glyphicon-tasks">&nbsp;</i> Problems</a>
		</li>
		<li <?php echo ($menu=='submissions')? "class='active'" : ""; ?>>
			<a href="submissions.php"><i class="glyphicon glyphicon-import">&nbsp;</i> Submissions</a>
		</li>
		<li <?php echo ($menu=='scoreboard')? "class='active'" : ""; ?>>
			<a href="scoreboard.php"><i class="glyphicon glyphicon-usd">&nbsp;</i> Scoreboard</a>
		</li>
		<li <?php echo ($menu=='account')? "class='active'" : ""; ?>>
			<a href="account.php"><i class="glyphicon glyphicon-user">&nbsp;</i> Account</a>
		</li>
		<li>
			<a href="logout.php"><i class="glyphicon glyphicon-log-in">&nbsp;</i> Logout</a>
		</li>
		<li class="active" style="float:right">
			<a id="Username"><?php echo $_SESSION['username'] . "/" .getScore(); ?>
		</a>
	</ul>
</div>