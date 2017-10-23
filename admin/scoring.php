<?php
	require_once('../functions.php');
	if(!loggedin())
		header("Location: login.php");
	else if($_SESSION['username'] !== 'admin')
		header("Location: login.php");
	else
		include('header.php');
		connectdb();
    include('menu.php');
?>
      </ul>
    </div>
  </div>
</div>
</div>
<div class="container">
<?php
  if(isset($_GET['updated']))
    echo("<div class=\"alert alert-success\">\nScoring Formula Updated!\n</div>");
?>
<ul class="nav nav-tabs">
  <li><a href="index.php">Umum</a></li>
  <li><a href="problems.php">Soal</a></li>
  <li class="active"><a href="scoring.php">Penilaian</a></li>
</ul>
<div>
  <div>
    <br/>
    <b>You can use any of the following variables in your calculation:</b><br/>
    <code>$attempts</code> The number of attempts by the user for that problem.<br/>
    <code>$points</code> Maximum points alloted for that question.<br/>
    <code>$score</code> Score to be awarded for that problem.<br/>
    <span class="label label-info">The formula uses PHP styled syntax. Make sure you end each statement with a ;</span><br/><br/>
    
    <form method="post" action="update.php">
      <input type="hidden" name="action" value="updateformula"/>
      <textarea style="font-family: mono; height:200px;" class="form-control" name="formula" id="text"><?php
        $query = "SELECT formula FROM prefs";
        $result = mysql_query($query);
        $fields = mysql_fetch_array($result);
        echo($fields['formula']);
      ?></textarea><br/>
      <input class="btn btn-primary btn-large" type="submit" value="Update Formula"/>
    </form>
  </div>
</div>
<?php
include('../copyright.php');
?>    
</div>
<?php
include('footer.php');
?>