<?php
  // Page variable must be set
  if(!isset($page))
    $page = '';

  require_once('config.inc.php');
?>
<!DOCTYPE html>
<html>
  <head>
    <title>EZTC - Easy Timeclock</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
    <!-- Bootstrap -->
    <link href="<?php echo $GlobalRoot; ?>css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo $GlobalRoot; ?>css/select2.css" rel="stylesheet">
    <link href="<?php echo $GlobalRoot; ?>css/main.css" rel="stylesheet">
    
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://code.jquery.com/jquery.js"></script>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
    <script type="text/javascript">
      $(document).ready(function() { 
        // Sets up the select boxes on the top of the main page and handles their submit
        $("#sel-users, #sel-projects").select2({width: '250px'})
          .on('change', function(e){                   
            var value = $(this).val();
            
            if(value.length < 1)
              return;
            
            var url = $(this).attr('name');
            
            window.location = '<?php echo $GlobalRoot; ?>' + url + '/'+value;
          });       
      });
    </script>
  </head>
  <body>
    <!-- Static navbar -->
    <div class="navbar navbar-default navbar-static-top" role="navigation" autocomplete="off">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="<?php echo $GlobalRoot; ?>">EZTC<span>\ˈē-zē tē sē\</span></a>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li <?php if($page == 'home') echo 'class="active"'; ?>><a href="<?php echo $GlobalRoot; ?>">Home</a></li>
            <?php if($page == 'userdetails') { ?>
            <li class="active"><a href="#">User Details</a></li>
            <?php } ?>
            <?php if($page == 'projectdetails') { ?>
            <li class="active"><a href="#">Project Details</a></li>
            <?php } ?>
            
            
            <!-- <li><a href="#about">About</a></li>
            <li><a href="#contact">Contact</a></li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown <b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li><a href="#">Action</a></li>
                <li><a href="#">Another action</a></li>
                <li><a href="#">Something else here</a></li>
                <li class="divider"></li>
                <li class="dropdown-header">Nav header</li>
                <li><a href="#">Separated link</a></li>
                <li><a href="#">One more separated link</a></li>
              </ul>
            </li> -->
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </div>
    
    <div class="container">

      <!-- Main component for a primary marketing message or call to action -->
      <div class="jumbotron">
        <h1>Easy Timeclock</h1>
        
        <?php      
          $result = $mysqli->query("SELECT `Employee`.`Name` as Name FROM `Employee` Order By Name") or die("User query failed");
        ?>       
        
        <form class="form-horizontal" role="form">
          <div class="form-group">
            <label class="col-sm-4 control-label nopadding">View details for employee:</label>
            <div class="col-sm-8">
              <select id="sel-users" name="user" data-placeholder="Select an Employee">
                <option selected="selected"></option>
                <?php 
                  while($row = $result->fetch_assoc()){ echo '<option value="'.$row['Name'].'">'.$row['Name']."</option>\n"; }
                  $result->close();
                ?>
              </select>
            </div>
          </div>
        </form>
        
        <?php      
          $result = $mysqli->query("SELECT `Project`.`Code` as Name FROM `Project` Order By Name") or die("Project query failed");
        ?>      
        
        <form class="form-horizontal" role="form">
          <div class="form-group">
            <label class="col-sm-4 control-label nopadding">View details for project:</label>
            <div class="col-sm-8">
              <select id="sel-projects" name="project" data-placeholder="Select a project">
                <option selected="selected"></option>
                <?php 
                  while($row = $result->fetch_assoc()){ echo '<option value="'.$row['Name'].'">'.$row['Name']."</option>\n"; }
                  $result->close();
                ?>
              </select>
            </div>
          </div>
        </form>
      </div>

    </div> <!-- /Jumbo -->