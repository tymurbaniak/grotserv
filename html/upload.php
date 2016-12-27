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
			<div class="col-md-3">
				<a href="index.php"><img src="assets/logo.png" alt="logo"></a>
			</div>
			<div class="col-md-6">
			<p>
			<?php
				$target_dir = "/var/www/html/uploads/";
				$name = "";
				$msettings = array();
				if(isset($_POST["submit"])) {
					if(is_uploaded_file($_FILES['bitmap']['tmp_name'])){
						echo "upload<br>";
						$name = makename(basename($_FILES["bitmap"]["name"]));
						$target_file = $target_dir . $name. ".png";
						$check = getimagesize($_FILES["bitmap"]["tmp_name"]);
						echo "File is an image - " . $check["mime"] . ".";
						$msettings = setconffile($name);
						if(move_uploaded_file($_FILES["bitmap"]["tmp_name"], $target_file)){
						echo "File uploaded!<br>";
						$output = exec("sudo -u www-data python3 /home/web/grot/run.py 2>&1");
						echo "<p>".$output."</p>";
						} else {
							echo "Upload errror<br>";
						}
					}else{
						if(isset($_POST["canvs_image"])){
							$data = substr($_POST["canvs_image"], 22, strlen($_POST["canvs_image"]) - 21);
							$name = makename(substr($_POST["canvs_image"], 100, 8));
							$target_file = $target_dir . $name. ".png";
							$msettings = setconffile($name);
							echo $target_file;
							$data = base64_decode($data);  
							$fp = fopen($target_file, 'w');  
							fwrite($fp, $data);  
							fclose($fp); 
							$output = exec("sudo -u www-data python3 /home/web/grot/run.py 2>&1");
							echo "<p>".$output."</p>";
						}
						
					}
					$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
				}
				function setconffile($mname){
						$disp ="";
						$stress ="";
						if(isset($_POST["disp"])){
							$disp = getoptions($_POST["disp"]);
						}
						if(isset($_POST["stress"])){
							$stress = getoptions($_POST["stress"]);
						}
						if(isset($_FILES["bitmap"]["name"])){
							$projectname = pathinfo($mname, PATHINFO_FILENAME);
						}
						if(isset($_POST["problem"])){
							$problem = $_POST["problem"];
						}
						if(isset($_POST["mat"])){
							$mat = $_POST["mat"];
						}
						if(isset($_POST["unit"])){
							$unit = $_POST["unit"];
						}
						if(isset($_POST["scale"])){
							$scale = $_POST["scale"];
						}
						if(isset($_POST["loadx"])){
							$loadx = $_POST["loadx"];
						}
						if(isset($_POST["loady"])){
							$loady = $_POST["loady"];
						}
						if(isset($_POST["loadcolor"])){
							$loadcolor = $_POST["loadcolor"];
						}
						if(isset($_POST["solver"])){
							$solver = $_POST["solver"];
						}
						if(isset($_POST["deformation"])){
							$deformation = $_POST["deformation"];
						}
						$settings = array(
						"project ".$projectname."\n",
						"problem ".$problem."\n",
						"bmp ".$projectname.".".pathinfo($mname,PATHINFO_EXTENSION)."\n",
						"mat ".$mat."\n",
						"unit ".$unit."\n",
						"scale ".$scale."\n",
						"thickness 2\n",
						"load x ".$loadx." y ".$loady." ".$loadcolor."\n",
						"solver ".$solver."\n",
						"disp".$disp."\n",
						"stress".$stress."\n",
						"deformed ".$deformation."\n",
						);
						mkdir("config/".$projectname, 600);
						file_put_contents("input.txt", $settings);
						file_put_contents("config/".$projectname."/config", json_encode($settings));
						
						return $settings;
				}
				function getoptions($array){
					$reval = "";
					foreach($array as $row){
						$reval .= " ".$row;
					}
					return $reval;
				}
				function makename($oldname){
					$buff = base64_encode($oldname.$_FILES["bitmap"]["tmp_name"].bin2hex(openssl_random_pseudo_bytes(4)));
					return substr($buff, 4, 6);
				}
				
			?>
			</p>
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
		<h1>Uploaded project: </h1>
			<hr><div class="row">
				<div class="col-md-3">
					<a href="<?php echo $target_file; ?>"><img src="<?php echo "uploads/".pathinfo($target_file, PATHINFO_BASENAME); ?>" width="50%" class="after" alt="<?php echo basename($target_file); ?> before computation"></a>
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
			$plots = glob("generated/".pathinfo($target_file, PATHINFO_FILENAME)."/*.{png}", GLOB_BRACE);
			foreach($plots as $plot){ ?>
				<a href="<?php echo $plot; ?>"><img src="<?php echo $plot; ?>" width="24%" class="after" alt="<?php echo basename($plot); ?> after computation"></a>
			<?php } ?>
			</div>
		</div>
      <hr>
      <footer>
        <p><a href="https://tutajrobert.github.io/grot/">GROT at github</a> | Author: <a href="https://github.com/tutajrobert">tutajrobert</a> | Pushed on the web by: <a href="http://tymur.pl">tymur.pl</a></p>
      </footer>
    </div> <!-- /container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script>window.jQuery || document.write("<script src="bootstrap/js/vendor/jquery.min.js"><\/script>")</script>
	<script src="bootstrap/js/bootstrap.min.js"></script>
    </body>
</html>
