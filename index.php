<?php
	require_once('functions.php');
	if(!loggedin())
		header("Location: login.php");
	else
		include('header.php');
		connectdb();
		include('breadcrumb.php');
		include('menu.php');
?>
	</nav>
</div>
<div class="container">
	<?php
		$query = "SELECT * FROM prefs";
        $result = mysql_query($query);
        $accept = mysql_fetch_array($result);
	if($accept['start']>time()){
	  	echo("<div class=\"alert alert-error\">\nThe contest has not started yet.\n</div>");
	}else{
    	echo "<div class='well'>
    			<i class=\"glyphicon glyphicon-tasks\">&nbsp;</i> Below is a list of available problems for you to solve.
    		  </div>";
      	echo "<ul class=\"list-unstyled\">";
        	$query = "SELECT * FROM problems ORDER BY addtime"; // DESC
          	$result = mysql_query($query);
          	if(mysql_num_rows($result)==0){
				echo("<li>None</li>\n");
          	}else {
				echo "<table class=\" table table-condensed \">
					<thead>
						<th>Soal</th>
						<th>Nilai</th>
					</thead>
					<tbody>
						<tr>";
				//<th>Due Date</th>
				while($row = mysql_fetch_array($result)) {
					$sql = "SELECT `status`, `grader` FROM `solve` WHERE (`username`='".$_SESSION['username']."' AND `problem_id`='".$row['sl']."')";
					$res = mysql_query($sql);
					$tag = "";
					if(mysql_num_rows($res) !== 0) {
						$r = mysql_fetch_array($res);
						if($r['status'] == 1)
							$tag = " <span class=\"label label-warning\">Attempted</span>";
						else if($r['status'] == 2)
							$tag = " <span class=\"label label-success\">Solved</span>";
					}
					if (mysql_num_rows($res) !== 0) {
						$grader = mysql_result($res, 0, 'grader');
						if ($grader === '')
							$percent = 0;
						else
							$percent = substr_count($grader, 'P') / strlen($grader) * 100;	
					} else {
						$percent = 0;
					}
					if(isset($_GET['id']) and $_GET['id']==$row['sl']) {
						$selected = $row;
						echo("<td >
								<li class=\"active\"><a href=\"#\">".$row['name'].$tag."</a></li>
							  </td>\n");
	          	    } else{
	          	        echo("<td>
	          	        		<li><a href=\"index.php?id=".$row['sl']."\">".$row['name'].$tag."</a></li>
	          	        	  </td>\n");
	          	    }
	          	    	// echo "<td>".date("d-m-Y H:i",$row['addtime'])."</td>";
	          	    	// echo "<td></td>";
						echo "<td>
								".$percent."
								
								<!-- <div class=\"progress\">
								<div class=\"progress-bar\" role=\"progressbar\" aria-valuenow=\"60\" aria-valuemin=\"0\" aria-valuemax=\"100\" style=\"width: " .$percent ."%;\"><span class=\"sr-only\">" .$percent ." Complete</span>
								</div>
								</div> -->
							  </td>";
					echo "</tr><tr>";
				}
				echo "</tr></tbody>
				</table>";
			}
	}
	?>
	</ul>
	<?php
        // if any problem is selected then list its details parsed by Markdown
      	if(isset($_GET['id'])) {
      		include('markdown.php');
		$out = Markdown($selected['text']);
		echo("<hr/>\n<h1>".$selected['name']."</h1>\n");
		echo "<div class=\"well\">";
		echo($out);
		echo "</div>";
      ?>
	<br/>
	<form action="solve.php" method="get">
		<input type="hidden" name="id" value="<?php echo($selected['sl']);?>"/>
		<?php
        // number of people who have solved the problem
        $query = "SELECT * FROM solve WHERE(status=2 AND problem_id='".$selected['sl']."')";
        $result = mysql_query($query);
        $num = mysql_num_rows($result);
      ?>
		<input class="btn btn-primary btn-large" type="submit" value="Solve"/>
		<span class="badge badge-info"><?php echo($num);?></span> user have solved the problem.
	</form>
	<?php
	}
	include('copyright.php');
	?>

</div>

<!-- /container -->

<?php
	include('footer.php');
?>
<script src="clock.js"></script>