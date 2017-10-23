<?php
		require_once('functions.php');
		include('dbinfo.php');
		connectdb();
		$attempts = 0;
		$query = "SELECT * FROM prefs";
        $result = mysql_query($query);
        $accept = mysql_fetch_array($result);
        $query = "SELECT `status` FROM `users` WHERE `username`='".$_SESSION['username']."'";
        $result = mysql_query($query);
        $status = mysql_fetch_array($result);
        // check if the user is banned or allowed to submit and SQL Injection checks
	
    	$soln = mysql_real_escape_string($_POST['soln']);
    	// $filename = mysql_real_escape_string($_POST['filename']);
    	$lang = mysql_real_escape_string($_POST['lang']);
    	//check if entries are empty
    	if(trim($soln) == "" or trim($lang) == "")
    		header("Location: solve.php?derror=1&id=".$_POST['id']);
    	else {
		if($_POST['ctype']=='new')
			$query = "INSERT INTO `solve` ( `problem_id` , `username`, `soln`, `lang`, `time`) VALUES ('".$_POST['id']."', '".$_SESSION['username']."', '".$soln."', '".$lang."', '".time()."')";
		else {
			$query = "UPDATE `solve` SET `time`='".time()."', `lang`='".$lang."', `attempts`=attempts+1, `soln`='".$soln."' WHERE (`username`='".$_SESSION['username']."' AND `problem_id`='".$_POST['id']."')";
		}
		mysql_query($query);
		switch($lang) {
		    case 'c': $ext='c'; break;
		    case 'cpp': $ext='cpp'; break;
		    case 'java': $ext='java'; break;
		    case 'python': $ext='py'; break;
		}
		
		$query = "SELECT `sl` FROM `testcase` WHERE `sl`='".$_POST['id']."'";
		$result = mysql_query($query);
		$num_rows = mysql_num_rows($result);
		$grader = '';
		$graderTrue = 0;
		$graderFalse = 0;
		$isGraded = false;
		for ($i = 0; $i < $num_rows; $i++) {
			try {
				$socket = @fsockopen($compilerhost, $compilerport);
				if($socket) {
					fwrite($socket, $_SESSION['username'].'_Solution.'.$ext."\n");
					$query = "SELECT `time` FROM `problems` WHERE `sl`='".$_POST['id']."'";
					$result = mysql_query($query);
					$fields = mysql_fetch_array($result);

					fwrite($socket, $fields['time']."\n");
					$soln = str_replace("\n", '$_n_$', treat($_POST['soln']));

					fwrite($socket, $soln."\n");
					$query = "SELECT `input`, `output` FROM `testcase` WHERE `sl`='".$_POST['id']."'";
					$result = mysql_query($query);
					$input = str_replace("\n", '$_n_$', treat(mysql_result($result, $i, 'input')));

					fwrite($socket, $input."\n");
					fwrite($socket, $lang."\n");

					$status = fgets($socket);
					$contents = "";

					while(!feof($socket))
						$contents = $contents.fgets($socket);
					
					if($status == 0) {
						$query = "UPDATE `solve` SET `status`=1 WHERE (`username`='".$_SESSION['username']."' AND `problem_id`='".$_POST['id']."')";
						mysql_query($query);
						$_SESSION['cerror'] = trim($contents);
						header("Location: solve.php?cerror=1&id=".$_POST['id']);
					} else if($status == 1) {
						if(trim($contents) == trim(treat(mysql_result($result, $i, 'output')))) {
							$grader .= 'P';
							$graderTrue += 1;
							$isGraded = true;
							$query = "SELECT `points` FROM `problems` WHERE `sl`=".$_POST['id'];
							$result = mysql_query($query);
							$row = mysql_fetch_array($result);
							$points = $row['points'];
							include('admin/formula.php');
							$query = "UPDATE `users` SET `score` = score + ".$score." WHERE `username` = '".$_SESSION['username']."'";
							mysql_query($query);
							$query = "UPDATE `solve` SET `score`=".$score.", `status`=2 WHERE (`username`='".$_SESSION['username']."' AND `problem_id`='".$_POST['id']."')";
							mysql_query($query);
						} else {
							$grader .= '-';
							$graderFalse += 1;
							$isGraded = true;
							$query = "UPDATE `solve` SET `status`=1 WHERE (`username`='".$_SESSION['username']."' AND `problem_id`='".$_POST['id']."')";
							mysql_query($query);
						}
					} else if($status == 2) {
						$query = "UPDATE `solve` SET `status`=1 WHERE (`username`='".$_SESSION['username']."' AND `problem_id`='".$_POST['id']."')";
						mysql_query($query);
						// update status
						$query_error = "UPDATE `solve` SET `error`='Runtime Error' WHERE (`username`='".$_SESSION['username']."' AND `problem_id`='".$_POST['id']."')";
						mysql_query($query_error);
						header("Location: solve.php?terror=1&id=".$_POST['id']);
					}
				} else{
					header("Location: solve.php?serror=1&id=".$_POST['id']);
				}
			} catch (Exception $e) {
				echo 'Caught exception: ',  $e->getMessage(), "\n";
			}
		}
		
		if ($graderFalse == $num_rows){
			// update status Wrong Answer 
			$query_error = "UPDATE `solve` SET `error`='Wrong Answer' WHERE (`username`='".$_SESSION['username']."' AND `problem_id`='".$_POST['id']."')";
			mysql_query($query_error);
		}

		if ($graderTrue == $num_rows){
			// update status succses -
			$query_error = "UPDATE `solve` SET `error`='-' WHERE (`username`='".$_SESSION['username']."' AND `problem_id`='".$_POST['id']."')";
			mysql_query($query_error);
		}

		if (!strpos($grader, '-') && $isGraded) {
			$query = "UPDATE `solve` SET `grader`='" .$grader ."' WHERE (`username`='".$_SESSION['username']."' AND `problem_id`='".$_POST['id']."')";
			mysql_query($query);
		}
		if ($isGraded) {
			header("Location: solve.php?id=" .$_POST['id'] ."&success=" .$grader);
		}
		
	}
?>
