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
        
        <p><a href="" data-toggle="modal" data-target="#addEvent"><i class="glyphicon glyphicon-plus">&nbsp;</i>add event</a></p>
        <?php
        if(isset($_GET['ev']) and $_GET['ev'])
        {
            $query = "SELECT * FROM event WHERE ev_id=".$_GET['ev']."";
            $result = mysql_query($query);
            $row = mysql_fetch_array($result);
            $selected = $row;
        ?>

        <form method="post" action="update.php" class="bs-docs-example form-horizontal">
                <input type="hidden" name="action" value="settingsedit" />
                <input type="hidden" name="ev" value="<?php echo $_GET['ev']?>" />
                <input type="text" name="title"  placeholder="Name of event" value="<?php echo $selected['title']?>" class="form-control" required />
                <br/>
                <div class="input-group">
                    <span class="input-group-addon" id="sizing-addon1"><i class="glyphicon glyphicon-calendar"> </i></span>
                    <input type="text" id="start" placeholder="Start Time Event" name="start" value="<?php echo date("d-m-Y H:i", $selected['start'])?>" class="form-control form_datetime">
                </div>
                <br/>
                <div class="input-group">
                    <span class="input-group-addon" id="sizing-addon1"><i class="glyphicon glyphicon-calendar"> </i></span>
                    <input type="text" id="end" placeholder="End Time Event" name="end" value="<?php echo date("d-m-Y H:i", $selected['end']) ?>" class="form-control form_datetime">
                </div>
                <h4>Languages</h4>
                <div class="checkbox">
                    <label>
                    <input name="cpp" type="checkbox" <?php if($selected['cpp']==1) echo("checked=\"true\"");?>/>
                    C++
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                    <input name="c" type="checkbox" <?php if($selected['c']==1) echo("checked=\"true\"");?>/>
                    C
                    </label>
                </div><div class="checkbox">
                    <label>
                    <input name="java" type="checkbox" <?php if($selected['java']==1) echo("checked=\"true\"");?> />
                    Java
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                    <input name="python" type="checkbox" <?php if($selected['python']==1) echo("checked=\"true\"");?>/> 
                    Python
                    </label>
                </div>
                <br/>
                <a href="<?php echo $base_url;?>admin/index.php"><button type="button" class="btn btn-default"><span class="glyphicon glyphicon-chevron-left"></span>Kembali</button></a>
                <input class="btn btn-primary" type="submit" name="submit" value="Save"/>
        </form>

        <?php 
        }
        ?>

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
                <input name="name" type="text" placeholder="Name of event" class="form-control" required />
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
                    <input name="cpp" type="checkbox"/>
                    C++
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                    <input name="c" type="checkbox" />
                    C
                    </label>
                </div><div class="checkbox">
                    <label>
                    <input name="java" type="checkbox" />
                    Java
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                    <input name="python" type="checkbox" /> 
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
