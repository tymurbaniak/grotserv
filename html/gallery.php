<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="">
    <title>Try GROT online!</title>
    <!-- Bootstrap core CSS -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="assets/font-awesome-4.7.0/css/font-awesome.min.css">
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <!-- Custom styles for this template -->
    <link href="assets/css/jumbotron.css" rel="stylesheet">
	<!--[if lte IE 8]><script type="text/javascript" src="assets/js/excanvas.js"></script><![endif]-->
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="jumbotron">
      <div class="container">
		<div class="row">
			<div class="col-md-9">
				<a href="index.php"><img src="assets/logo.png" alt="logo"></a>
			</div>
			<div class="col-md-3">
				<a href="help.html"><i class="fa fa-question-circle fa-3x fa-pull-right"></i></a>
				<a href="https://tutajrobert.github.io/grot/"><i class="fa fa-github fa-3x fa-pull-right"></i></a>
			</div>
		</div>
      </div>
    </div>
    <div class="container">
      <!-- Example row of columns -->
      <div class="row">
			<?php
				$displayed = array();
				$projects = glob('uploads/*.{jpg,png,gif,bmp}', GLOB_BRACE); ?>
				<h1>Gallery contains <?php echo count($projects); ?> projects</h1>
				<?php
				foreach($projects as $file){
					array_push($displayed, $file); 
					$msettings = json_decode(file_get_contents("config/".pathinfo($file, PATHINFO_FILENAME)."/config")); ?>
					<hr><div class="row">
						<div class="col-md-3">
							<a href="<?php echo $file; ?>"><img src="<?php echo $file; ?>" width="50%" class="after" alt="<?php echo basename($file); ?> after computation"></a>
							<h4>Computed with configuration:</h4>
							<p>
								Problem: <?php echo substr($msettings[1], 8, strlen($msettings[1])); ?> <br>
								Material: <?php echo substr($msettings[3], 4, strlen($msettings[3])); ?> <br>
								Unit: <?php echo substr($msettings[4], 5, strlen($msettings[4])); ?> <br>
								Scale: <?php echo substr($msettings[5], 6, strlen($msettings[5])); ?> <br>
								Load: <?php echo substr($msettings[7], 5, strlen($msettings[7])); ?> <br>
								Solver: <?php echo substr($msettings[8], 7, strlen($msettings[8])); ?> <br>
								Displacement: <?php echo substr($msettings[9], 5, strlen($msettings[7])); ?> <br>
								Stress: <?php echo substr($msettings[10], 7, strlen($msettings[10])); ?> <br>
								Deformation scale: <?php echo substr($msettings[11], 9, strlen($msettings[11])); ?> <br>
							</p>
						</div>
						<div class="col-md-9">
					<?php
					$plots = glob('generated/'.pathinfo($file, PATHINFO_FILENAME).'/*.{png}', GLOB_BRACE);
					foreach($plots as $plot){ ?>
						<a href="<?php echo $plot; ?>"><img src="<?php echo $plot; ?>" width="24%" class="after" alt="<?php echo basename($plot); ?> after computation"></a>
					<?php } ?>
					</div>
				</div>
				<?php } ?> 
				<!-- TODO: pagination -->
      <hr>
      <footer>
        <p><a href="https://tutajrobert.github.io/grot/">GROT at github</a> | Author: <a href="https://github.com/tutajrobert">tutajrobert</a> | Pushed on the web by: <a href="http://tymur.pl">tymur.pl</a></p>
      </footer>
    </div> <!-- /container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="bootstrap/js/jquery.min.js"><\/script>')</script>
	<script src="bootstrap/js/bootstrap.min.js"></script>
    </body>
</html>
