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
<link rel="stylesheet" href="css/codemirror.css">
<div class="container">
<?php
    if(isset($_GET['success'])){
      if (strpos($_GET['success'], '-') === false){
        echo("<div class=\"alert alert-success\">\nCongratulations! You have solved the problem successfully.\n</div>");
      } else {
        echo("<div class=\"alert alert-danger\">\n<i class='glyphicon glyphicon-info-sign'></i> Grader server successfully compile your program! But, wrong answer your program in our Test Case .\n</div>");
      }
    }
?>
<?php
    if(isset($_GET['terror']))
      echo("<div class=\"alert alert-danger\">\n<i class=\"glyphicon glyphicon-info-sign\">&nbsp;</i>Your program exceeded the time limit. Maybe you should improve your algorithm.\n</div>");
    if(isset($_GET['cerror']))
      echo("<div class=\"alert alert-danger\">\n<strong><i class=\"glyphicon glyphicon-info-sign\">&nbsp;</i>The following errors occured:</strong><br/>\n<pre>\n".$_SESSION['cerror']."\n</pre>\n</div>");
    else if(isset($_GET['oerror']))
      echo("<div class=\"alert alert-danger\">\n<i class=\"glyphicon glyphicon-info-sign\">&nbsp;</i>Your program output did not match the solution for the problem. Please check your program and try again.\n</div>");
    else if(isset($_GET['lerror']))
      echo("<div class=\"alert alert-danger\">\n<i class=\"glyphicon glyphicon-info-sign\">&nbsp;</i>You did not use one of the allowed languages. Please use a language that is allowed.\n</div>");
    else if(isset($_GET['serror']))
      echo("<div class=\"alert alert-danger\">\n<i class=\"glyphicon glyphicon-info-sign\">&nbsp;</i>Could not connect to the compiler server. Please contact the admin to solve the problem.\n</div>");
    else if(isset($_GET['derror']))
      echo("<div class=\"alert alert-danger\">\n<i class=\"glyphicon glyphicon-info-sign\">&nbsp;</i>Please enter all the details asked before you can continue!\n</div>");
    $query = "SELECT * FROM prefs";
    $result = mysql_query($query);
    $accept = mysql_fetch_array($result);
    $query = "SELECT status FROM users WHERE username='".$_SESSION['username']."'";
    $result = mysql_query($query);
    $status = mysql_fetch_array($result);
    
    // if($accept['end'] < time())
    //   echo("<div class=\"alert alert-danger\">\n<i class=\"glyphicon glyphicon-info-sign\">&nbsp;</i>Submissions are closed now!\n</div>");
    // if($status['status'] == 0)
    //   echo("<div class=\"alert alert-danger\">\n<i class=\"glyphicon glyphicon-info-sign\">&nbsp;</i>You have been banned. You cannot submit a solution.\n</div>");
    // if($accept['start']>time())
    //   header('location:index.php');
    
?>
  <h3>Submit Solution</h3>
  <?php
  echo "<hr/><div class=\"well well-large\">";
      if(isset($_GET['id']) and is_numeric($_GET['id'])) {
        $query = "SELECT * FROM problems WHERE sl='".$_GET['id']."'";
          $result = mysql_query($query);
          $row = mysql_fetch_array($result);
        include('markdown.php');
  $out = Markdown($row['text']);
  echo "<h1>".$row['name']."</h1>" ;
  echo($out);
  echo "</div>";
  ?>
	<br/>
	<span class="label label-danger">Time Limit: <?php echo($row['time']/1000); ?> seconds</span>
	<hr/>
	<?php
        if(is_numeric($_GET['id'])) {
          $query = "SELECT * FROM solve WHERE (problem_id='".$_GET['id']."' AND username='".$_SESSION['username']."')";
          $result = mysql_query($query);
          $num = mysql_num_rows($result);
          $fields = mysql_fetch_array($result);
        }
  ?>
  <!-- start form -->
	<form method="post" action="eval.php" class="bs-docs-example form-horizontal">
		<?php 
        if($num == 0)
          echo('<input type="hidden" name="ctype" value="new"/>');
        else
          echo('<input type="hidden" name="ctype" value="change"/>');
    ?>
		<input type="hidden" name="id" value="<?php if(is_numeric($_GET['id'])) echo($_GET['id']);?>"/>
		<input type="hidden" name="lang" id="hlang" value="<?php if($num == 0) echo('c'); else echo($fields['lang']);?>"/>
		<div class="btn-group">
			<div id="blank"></div>
      <a id="lang" class="btn dropdown-toggle" data-toggle="dropdown" href="#">Language:
			<?php
          if($num == 0) echo('C');
          else if($fields['lang']=='c') echo('C');
          else if($fields['lang']=='cpp') echo('C++');
          else if($fields['lang']=='java') echo('Java');
        ?>
			<span class="caret"></span>
      </a>
			<ul class="dropdown-menu">
				<li><a href="#" onclick="changeLang('C');changeSyntax('C');">C</a></li>
				<li><a href="#" onclick="changeLang('C++');changeSyntax('C++');">C++</a></li>
				<li><a href="#" onclick="changeLang('Java');changeSyntax('Java');">Java</a></li>
			</ul>
		</div>
		<br/>
		Type your program below:<br/>
		<br/>
		<textarea style="font-family: mono; height:400px;" class="span9" name="soln" id="text" class="form-control"><?php if(!($num == 0)) echo($fields['soln']);?></textarea>
		<br/>
		<input type="submit" value="Run" class="btn btn-primary btn-large"/>
		<span class="label label-info">You are allowed to use any of the following languages:
		<?php $txt="";
        if($accept['c'] == 1) $txt = "C, ";
        if($accept['cpp'] == 1) $txt = $txt."C++, ";
        if($accept['java'] == 1) $txt = $txt."Java, ";
        $final = substr($txt, 0, strlen($txt) - 2);
        echo($final."</span>\n");
      ?>
	</form>
  <!-- end form  -->
	<?php
  	}  
    include('copyright.php');
  ?>
</div>

<script language="javascript">
      function changeLang(lang) {
        $('#lang').remove();
        $('#blank').after('<a id="lang" class="btn dropdown-toggle" data-toggle="dropdown" href="#">Language: ' + lang + ' <span class="caret"></span></a>');
        if(lang == 'C')
          $('#hlang').val('c');
        else if(lang== 'C++')
          $('#hlang').val('cpp');
        else if(lang== 'Java')
          $('#hlang').val('java');
      }
</script> 
<script src="js/codemirror.js"></script> 
<script src="js/clike.js"></script> 
<script src="js/python.js"></script> 
<script src="js/matchbrackets.js"></script> 
<script src="js/closebrackets.js"></script> 
<script>
	var editor = CodeMirror.fromTextArea(document.getElementById("text"), {
      lineNumbers: true,
      matchBrackets: true,
	    indentUnit: 4,
	    autoCloseBrackets: true,
      mode: "text/x-csrc"
  });
  function changeSyntax(lang) {
  	if(lang=='C')
  	  editor.setOption("mode", "text/x-csrc");
  	else if(lang=='C++')
  	  editor.setOption("mode", "text/x-c++src");
  	else if(lang=='Java')
  	  editor.setOption("mode", "text/x-java");
  }
  var num="<?php echo $num;?>";
  var lang="<?php echo $fields['lang'];?>";
  if(num == 0) changeSyntax('C');
  else if(lang=='c') changeSyntax('C');
  else if(lang=='cpp') changeSyntax('C++');
  else if(lang=='java') changeSyntax('Java');
</script>
<?php	include('footer.php');?>
