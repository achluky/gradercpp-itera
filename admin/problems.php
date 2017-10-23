<?php
	require_once('../functions.php');
	if(!loggedin())
		header("Location: login.php");
	else if($_SESSION['username'] !== 'admin')
		header("Location: login.php");
	else
		include('header.php');
		connectdb();
        require_once('menu.php');
?>
</ul>
</div>
</div>
</div>
</div>
<div class="container">
	<?php
        if(isset($_GET['added']))
          echo("<div class=\"alert alert-success\">\n<i class=\"glyphicon glyphicon-info-sign\">&nbsp;</i>Soal Berhasil ditambahkan!\n</div>");
        else if(isset($_GET['deleted']))
          echo("<div class=\"alert alert-danger\">\n<i class=\"glyphicon glyphicon-info-sign\">&nbsp;</i>Problem deleted!\n</div>");
        else if(isset($_GET['updated']))
          echo("<div class=\"alert alert-success\">\n<i class=\"glyphicon glyphicon-info-sign\">&nbsp;</i>Problem updated!\n</div>");
        else if(isset($_GET['derror']))
          echo("<div class=\"alert alert-danger\">\n<i class=\"glyphicon glyphicon-info-sign\">&nbsp;</i>Please enter all the details asked before you can continue!\n</div>");
      ?>
	<ul class="nav nav-tabs">
		<li><a href="index.php">Umum</a></li>
		<li class="active"><a href="#">Kumpulan Soal</a></li>
		<li><a href="scoring.php">Penilaian</a></li>
	</ul>
	<div>
		<div> 
			<br/>
			<div class="alert alert-info"><i class="glyphicon glyphicon-info-sign">&nbsp;</i>Below is a list of already added problems. Click on a problem to edit it.</div>
			<?php
			if(!isset($_GET['action']) and !isset($_GET['id']))
				echo("<a href=\"#\" data-toggle=\"modal\" data-target=\"#addProblem\"><i class=\"glyphicon glyphicon-plus\">&nbsp;</i>Add problem</a>\n");
			else
				echo("<a href=\"problems.php\" class=\" btn btn-primary \"><i class=\"glyphicon glyphicon-chevron-left\">&nbsp;</i>Kembali</a>\n");
			?>
			<?php
			if(isset($_GET['action']) and $_GET['action']=='edit' and isset($_GET['id']))
			{
				$query = "SELECT * FROM problems WHERE sl=".$_GET['id']." ORDER BY addtime";
				$result = mysql_query($query);
				$row = mysql_fetch_array($result);
				$selected = $row;
			?>
				<h1><small>Edit a Problem</small></h1>
				<form method="post" action="update.php">
						<input type="hidden" name="action" value="editproblem" id="action"/>
						<input type="hidden" name="id" id="id" value="<?php echo($selected['sl']);?>"/>
						<ul class="nav nav-tabs">
							<li class="active"><a href="#tab1" data-toggle="tab">Problem</a></li>
							<li><a href="#tab2" data-toggle="tab">Test Case</a></li>
						</ul>
						<div class="tab-content">
							<div class="tab-pane active" id="tab1"> 
								<br/>
								<div class="form-group">
								<label for="exampleInputEmail1">Problem Title:</label>
								<input type="text" class="form-control" id="title" placeholder="title" name="title" value="<?php echo($selected['name']);?>"/>
								</div>
								<div class="form-group">
								<label for="exampleInputEmail1">Maximum Points:</label>
								<input type="text" class="form-control" id="points" placeholder="points" name="points" value="<?php echo($selected['points']);?>">
								</div>
								<div class="form-group">
								<label for="exampleInputEmail1">Time Limit:</label>
								<input type="text" class="form-control" id="time" placeholder="time (ms)" name="time" value="<?php echo($selected['time']); ?>">
								</div>
								<div class="form-group">
								<label for="exampleInputEmail1">Event:</label>
								<?php
									$query = "SELECT * FROM event ORDER BY ev_id";
									$result = mysql_query($query);
									echo '<select class="form-control" name="event">';
									if(mysql_num_rows($result)==0)
										echo "<option value=''>No Event</option>";
									else 
									{
										while($row = mysql_fetch_array($result)) 
										{
								?>
												<option value="<?php echo $row['ev_id']?>" <?php echo ($selected['id_ev']==$row['ev_id'])?"selected":""; ?> ><?php echo $row['title']?></option>
								<?php 
										}
									}
									echo '</select>';
								?>
								</div>
								<div class="form-group">
								<label for="exampleInputEmail1">Detailed problem <span class="label label-info">Markdown formatting supported</span> :</label>
								<textarea style="height:200px;" class="form-control" name="problem" id="text"><?php echo str_replace("<br>", "", $selected['text']);?></textarea>
								</div>
							</div>

							<div class="tab-pane" id="tab2">
								<br/>
								<table class="table table-bordered" id="edit-problem-testcase">
									<thead>
										<tr>
											<th>Sample Input</th>
											<th>Sample Output</th>
										</tr>
									</thead>
									<tbody id="edit-problem-testcase-body">
										<?php
										$query = "SELECT `input`, `output` FROM `testcase` WHERE `sl`=" .$selected['sl'];
										$result = mysql_query($query);
										$num = mysql_num_rows($result);
										for ($i = 0; $i < $num; $i++) {
											echo "<tr>";
											echo "<td><textarea style=\"font-family: mono;\" class=\"form-control\" rows=\"10\" name=\"input" .$i ."\">" .mysql_result($result, $i, 'input') ."</textarea></td>";
											echo "<td><textarea style=\"font-family: mono;\" class=\"form-control\" rows=\"10\" name=\"output" .$i ."\">" .mysql_result($result, $i, 'output') ."</textarea></td>";
											echo "</tr>";
										}
									?>
									</tbody>
								</table>
								<input class="btn btn-success" type="button" value="Add Testcase" onclick="edit_problem_add_testcase()"/>
								<br>
								<br>
								<input type="hidden" name="total-testcase-edit" id="total-testcase-edit" value="">
								<script>    
									function edit_problem_add_testcase() 
									{
									var tbody = document.getElementById('edit-problem-testcase-body');
									var tr = document.createElement('tr');
									var count = $('#edit-problem-testcase >tbody >tr').length;
									var element = document.getElementById('total-testcase-edit');
									element.setAttribute('value', count);
									var td1 = document.createElement('td');
									var txta1 = document.createElement('textarea');
									txta1.setAttribute('style', 'font-family: mono;');
									txta1.setAttribute('class', 'form-control');
									txta1.setAttribute('rows', '10');
									txta1.setAttribute('name', 'input' + count);
									td1.appendChild(txta1);
									
									var td2 = document.createElement('td');
									var txta2 = document.createElement('textarea');
									txta2.setAttribute('style', 'font-family: mono;');
									txta2.setAttribute('class', 'form-control');
									txta2.setAttribute('rows', '10');
									txta2.setAttribute('name', 'output' + count);
									td2.appendChild(txta2);
									
									tr.appendChild(td1);
									tr.appendChild(td2);
									tbody.appendChild(tr);
									};
								</script>
							</div>
						</div>
						<input class="btn btn-primary btn-large" type="submit" value="Update Problem"/>
						<input class="btn btn-large" type="button" value="Preview" onclick="$('#preview').load('preview.php', {action: 'preview', title: $('#title').val(), text: $('#text').val()});"/>
						<input class="btn btn-danger btn-large" type="button" value="Delete Problem" onclick="window.location='update.php?action=delete&id='+$('#id').val();"/>
				</form>
				<div id="preview"></div>
			<?php
			} else {
			?>
			<ol>
				<?php
					$query = "SELECT * FROM problems LEFT JOIN event ON problems.id_ev = event.ev_id ORDER BY addtime";
					$result = mysql_query($query);
					if(mysql_num_rows($result)==0)
						echo("<li>None</li>\n");
					else {
	          	    	while($row = mysql_fetch_array($result)) 
						{
						  	echo("<li>
						  	<a href=\"problems.php?action=edit&id=".$row['sl']."\">".$row['name']."</a> <div class='pull-right'> Event : ".$row['title']."</div>
						  	</li>\n");
	          			}
	          	  	}
	          	?>
			</ol>
			<?php 
			}
			?>

			<div id="preview"></div>
			<div id="addProblem" class="modal fade" role="dialog">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-body">
				   	<form method="post" action="update.php" >
						<input type="hidden" name="action" value="addproblem"/>
						<ul class="nav nav-tabs">
							<li class="active"><a href="#tab1" data-toggle="tab">Problem</a></li>
							<li><a href="#tab2" data-toggle="tab">Test Case</a></li>
						</ul>
						<div class="tab-content">
							<div class="tab-pane active" id="tab1"> 
								<br/>
								<div class="form-group">
								<label for="exampleInputEmail1">Problem Title:</label>
								<input type="text" class="form-control" id="title" placeholder="title" name="title" />
								</div>
								<div class="form-group">
								<label for="exampleInputEmail1">Maximum Points:</label>
								<input type="text" class="form-control" id="points" placeholder="points" name="points">
								</div>
								<div class="form-group">
								<label for="exampleInputEmail1">Event:</label>
								<?php
									$query = "SELECT * FROM event ORDER BY ev_id";
									$result = mysql_query($query);
									echo '<select class="form-control" name="event">';
									if(mysql_num_rows($result)==0)
										echo "<option value=''>No Event</option>";
									else 
									{
										while($row = mysql_fetch_array($result)) 
										{
								?>
												<option value="<?php echo $row['ev_id']?>"><?php echo $row['title']?></option>
								<?php 
										}
									}
									echo '</select>';
								?>
								</div>
								<div class="form-group">
								<label for="exampleInputEmail1">Time Limit:</label>
								<input type="text" class="form-control" id="time" placeholder="time (ms)" name="time">
								</div>
								<div class="form-group">
								<label for="exampleInputEmail1">Due Date:</label>
								<input type="text" id="duedate" placeholder="due date" name="duedate" value="<?php echo date("d-m-Y h:i"); ?>" readonly class="form-control form_datetime">
								</div>
								<div class="form-group">
								<label for="exampleInputEmail1">Detailed problem <span class="label label-info">Markdown formatting supported</span> :</label>
								<textarea style="height:200px;" class="form-control" name="problem" id="text"></textarea>
								</div>
							</div>
							<div class="tab-pane" id="tab2">
								<br/>
								<table class="table table-bordered" id="new-problem-testcase">
									<thead>
										<tr>
											<th>Sample Input</th>
											<th>Sample Output</th>
										</tr>
									</thead>
									<tbody id="new-problem-testcase-body">
										<tr>
											<td><textarea style="font-family: mono;" class="form-control" name="input0" rows="10"></textarea></td>
											<td><textarea style="font-family: mono;" class="form-control" name="output0" rows="10"></textarea></td>
										</tr>
									</tbody>
								</table>
								<input class="btn btn-success" type="button" value="Add Testcase" onclick="new_problem_add_testcase()"/>
								<br>
								<br>
								<input type="hidden" name="total-testcase-new" id="total-testcase-new" value="">
								<script>    
									function new_problem_add_testcase() 
									{
									var tbody = document.getElementById('new-problem-testcase-body');
									var tr = document.createElement('tr');
									var count = $('#new-problem-testcase >tbody >tr').length;
									var element = document.getElementById('total-testcase-new');
									element.setAttribute('value', count);
									
									var td1 = document.createElement('td');
									var txta1 = document.createElement('textarea');
									txta1.setAttribute('style', 'font-family: mono;');
									txta1.setAttribute('class', 'form-control');
									txta1.setAttribute('rows', '10');
									txta1.setAttribute('name', 'input' + count);
									td1.appendChild(txta1);
									
									var td2 = document.createElement('td');
									var txta2 = document.createElement('textarea');
									txta2.setAttribute('style', 'font-family: mono;');
									txta2.setAttribute('class', 'form-control');
									txta2.setAttribute('rows', '10');
									txta2.setAttribute('name', 'output' + count);
									td2.appendChild(txta2);
									
									tr.appendChild(td1);
									tr.appendChild(td2);
									tbody.appendChild(tr);
									};
								</script>
							</div>
						</div>
						<input class="btn btn-primary btn-large" type="submit" value="Add Problem"/>
						<input class="btn btn-large" type="button" value="Preview" onclick="$('#preview').load('preview.php', {action: 'preview', title: $('#title').val(), text: $('#text').val()});"/>
                    	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</form>
                  </div>
                </div>
              </div>
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
