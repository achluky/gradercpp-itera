<?php
    require_once('../functions.php');
    if(!loggedin())
        header("Location: login.php");
    else if($_SESSION['username'] !== 'admin')
        header("Location: login.php");
    else
        include('header.php');
        connectdb();
        date_default_timezone_set('UTC');
        require_once('menu.php');
?>
</ul>
</div>
</div>
</div>
</div>
<div class="container">
  <?php
    if(isset($_GET['changed']))
      echo("<div class=\"alert alert-info\">\n<i class=\"glyphicon glyphicon-info-sign\">&nbsp;</i>Settings Saved!\n</div>");
    else if(isset($_GET['passerror']))
      echo("<div class=\"alert alert-danger\">\n<i class=\"glyphicon glyphicon-info-sign\">&nbsp;</i>The old password is incorrect!\n</div>");
    else if(isset($_GET['derror']))
      echo("<div class=\"alert alert-danger\">\n<i class=\"glyphicon glyphicon-info-sign\">&nbsp;</i>Please enter all the details asked before you can continue!\n</div>");
  ?>
  <ul class="nav nav-tabs">
    <li class="active"><a href="#">Umum</a></li>
    <li><a href="problems.php">Soal</a></li>
    <li><a href="scoring.php">Penilaian</a></li>
  </ul>
  <div>
    <div>
        <br/>
        <div class="alert alert-info"><i class="glyphicon glyphicon-info-sign">&nbsp;</i>Below is a list of already added problems. Click on a problem to edit it.</div>
          <?php
              $query = "SELECT name, start, end, c, cpp, java, python FROM prefs";
              $result = mysql_query($query);
              $fields = mysql_fetch_array($result);
          ?>
          <h3>List Event</h3>
          <?php
              $query = "SELECT ev_id, title, start, end, c, cpp, java, python FROM event LIMIT 10";
              $result = mysql_query($query);
          ?>
          <ol>
          <?php 
	          	while($row = mysql_fetch_array($result)) {
          ?>
          <li>
                <a href="event.php?ev=<?php echo $row['ev_id']?>"><?php echo $row['title']; ?></a> 
                <i class="glyphicon glyphicon-time">&nbsp;</i><?php echo date("d-m-Y H:i", $row['start'])?>
                <i class="glyphicon glyphicon-play">&nbsp;</i><?php echo date("d-m-Y H:i", $row['end'])?>
          </li>
          <?php 
              }
          ?>
          </ol>
          <p><a href="" data-toggle="modal" data-target="#addEvent"><i class="glyphicon glyphicon-plus">&nbsp;</i>add event</a></p>
          <form method="post" action="update.php" class="bs-docs-example form-horizontal">
            <div id="addEvent" class="modal fade" role="dialog">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Add Event</h4>
                  </div>
                  <div class="modal-body">
                    <input type="hidden" name="action" value="settings" />
                    <input name="name" type="text" placeholder="Name of event" value="<?php echo($fields['name']);?>" class="form-control" required />
                    <br/>
                    <div class="input-group">
                      <span class="input-group-addon" id="sizing-addon1"><i class="glyphicon glyphicon-calendar"> </i></span>
                      <input type="text" id="start" placeholder="Start Time Event" name="start"  class="form-control form_datetime">
                    </div>
                    <br/>
                    <div class="input-group">
                      <span class="input-group-addon" id="sizing-addon1"><i class="glyphicon glyphicon-calendar"> </i></span>
                      <input type="text" id="start" placeholder="End Time Event" name="end" class="form-control form_datetime">
                    </div>
                    <h4>Languages</h4>
                    <div class="checkbox">
                      <label>
                        <input name="cpp" type="checkbox" <?php if($fields['cpp']==1) echo("checked=\"true\"");?>/>
                        C++
                      </label>
                    </div>
                    <div class="checkbox">
                      <label>
                        <input name="c" type="checkbox" <?php if($fields['c']==1) echo("checked=\"true\"");?>/>
                        C
                      </label>
                    </div><div class="checkbox">
                      <label>
                        <input name="java" type="checkbox" <?php if($fields['java']==1) echo("checked=\"true\"");?>/>
                        Java
                      </label>
                    </div>
                    <div class="checkbox">
                      <label>
                        <input name="python" type="checkbox" <?php if($fields['python']==1) echo("checked=\"true\"");?>/>
                        Python
                      </label>
                    </div>
                    <br/>

                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <input class="btn btn-primary" type="submit" name="submit" value="Save"/>
                  </div>
                </div>

              </div>
            </div>
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
