<!DOCTYPE html>
<html>
  <head>
    <title>EZTC - Easy Timeclock</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/select2.css" rel="stylesheet">
    <link href="css/main.css" rel="stylesheet">
    
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
        // Sets up the select boxes on the top of the main page
        $("#sel-users, #sel-projects").select2({width: '250px'})
          .on('change', function(e){          
            $(this).parents('form').first().submit();
          }); 
        
        // Adds clicks to the project string rows
        $('.recentprojects tr td').css('cursor','pointer').click(function(){       
          window.location = $(this).parent().find('a').attr('href');
        })
        .mouseenter(function(){$(this).siblings().andSelf().css('text-decoration', 'underline')})
        .mouseleave(function(){$(this).siblings().andSelf().css('text-decoration', 'none')});
        
      });
    </script>
  </head>
  <body>
    <!-- Static navbar -->
    <div class="navbar navbar-default navbar-static-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="/">EZTC<span>\ˈē-zē tē sē\</span></a>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="/">Home</a></li>
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
        
        
        <form class="form-horizontal" role="form" action="details.php" method="GET">
          <div class="form-group">
            <label class="col-sm-4 control-label nopadding">View details for employee:</label>
            <div class="col-sm-8">
              <select id="sel-users" name="u" data-placeholder="Select an Employee">
                <option selected="selected"></option>
                <option value="Dennis">Skinner, Dennis</option>
                <option value="Kris">Howard, Kris</option>
                <option value="Shane">White, Shane</option>
              </select>
            </div>
          </div>
        </form>
        
        <form class="form-horizontal" role="form" action="details.php" method="GET">
          <div class="form-group">
            <label class="col-sm-4 control-label nopadding">View details for project:</label>
            <div class="col-sm-8">
              <select id="sel-projects" name="p" data-placeholder="Select a project">
                <option selected="selected"></option>
                <option value="Dennis">Skinner, Dennis</option>
                <option value="Kris">Howard, Kris</option>
                <option value="Shane">White, Shane</option>
              </select>
            </div>
          </div>
        </form>
      </div>

    </div> <!-- /Jumbo -->