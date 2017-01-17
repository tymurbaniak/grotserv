<?php declare(strict_types=1); ?>
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
				require_once "recaptchalib.php";
				$siteKey = "XXXXXXXXXXXXXXXXXXXXXXXX";
				$secret = "XXXXXXXXXXXXXXXXXXXXXX";
				include 'bmp_3.php';
				// reCAPTCHA supported 40+ languages listed here: https://developers.google.com/recaptcha/docs/language
				$lang = "pl";
				// The response from reCAPTCHA
				$resp = null;
				// The error code from reCAPTCHA, if any
				$error = null;
				$reCaptcha = new ReCaptcha($secret);
				// Was there a reCAPTCHA response?
				if ($_POST["g-recaptcha-response"]) {
					$resp = $reCaptcha->verifyResponse(
						$_SERVER["REMOTE_ADDR"],
						$_POST["g-recaptcha-response"]
					);
				}
				
				function setconffile($mname){
							$disp ="";
							$stress ="";
							if(isset($_POST["disp"])){
								$disp = xssafe(getoptions($_POST["disp"]));
							}
							if(isset($_POST["stress"])){
								$stress = xssafe(getoptions($_POST["stress"]));
							}
							if(isset($_FILES["bitmap"]["name"])){
								$projectname = xssafe(pathinfo($mname, PATHINFO_FILENAME));
							}
							if(isset($_POST["problem"])){
								$problem = xssafe($_POST["problem"]);
							}
							if(isset($_POST["mat"])){
								$mat = xssafe($_POST["mat"]);
							}
							if(isset($_POST["unit"])){
								$unit = xssafe($_POST["unit"]);
							}
							if(isset($_POST["scale"])){
								$scale = xssafe($_POST["scale"]);
							}
							if(isset($_POST["loadx"])){
								$loadx = xssafe($_POST["loadx"]);
							}
							if(isset($_POST["loady"])){
								$loady = xssafe($_POST["loady"]);
							}
							if(isset($_POST["loadcolor"])){
								$loadcolor = xssafe($_POST["loadcolor"]);
							}
							if(isset($_POST["solver"])){
								$solver = xssafe($_POST["solver"]);
							}
							if(isset($_POST["deformation"])){
								$deformation = xssafe($_POST["deformation"]);
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
							mkdir("config/".$projectname, 0755);
							file_put_contents("input.txt", $settings);
							file_put_contents("config/".$projectname."/config", json_encode($settings));
							return $settings;
					}
					function checkimage($image){
						$extension = pathinfo(basename($image), PATHINFO_EXTENSION);
						switch ($extension) { 
							case "jpg" :
								$im = @imagecreatefromjpeg($image);
								break;
							case "png" :
								$im = @imagecreatefrompng($image);
								break;
							case "bmp" :
								$im = @imagecreatefrombmp($image);
								break;
							case "gif" :
								$im = @imagecreatefromgif($image);
								break;
							default :
								$extension = "Invalid image";
						}
						if(!$im) $extension = "Invalid image";
						imagedestroy($im);
						return $extension;
					}
					function getoptions($array){
						$reval = "";
						foreach($array as $row){
							$reval .= " ".$row;
						}
						return $reval;
					}
					function makename($oldname){
						$buff = bin2hex(openssl_random_pseudo_bytes(4));
						return $buff;
					}
					function xssafe($data,$encoding='UTF-8'){
					return htmlspecialchars($data,ENT_QUOTES | ENT_HTML401,$encoding);
					}
					
				if ($resp != null && $resp->success) {
					$target_dir = "/var/www/html/uploads/";
					$name = "";
					$msettings = array();
					if(isset($_POST["submit"])) {
						if(is_uploaded_file($_FILES['bitmap']['tmp_name'])){
							echo "upload<br>";
							$name = makename(basename($_FILES["bitmap"]["name"]));
							$target_file = $target_dir . $name. ".".pathinfo($_FILES['bitmap']['name'], PATHINFO_EXTENSION);
							//$check = getimagesize($_FILES["bitmap"]["tmp_name"]);
							//echo "File is an image - " . $check["mime"] . ".";
							$msettings = setconffile($name.".".pathinfo($_FILES['bitmap']['name'], PATHINFO_EXTENSION));
							if(move_uploaded_file($_FILES["bitmap"]["tmp_name"], $target_file)){
								if(checkimage($target_file) != "Invalid image"){
									echo "File uploaded!<br>";
									$output = exec("sudo -u www-data python3 /home/web/grot/run.py 2>&1");
									echo "<p>".$output."</p>";
								}else{
									echo "Image is corrupted";
									unlink($target_file);
								}
							} else {
								echo "Upload error<br>";
							}
						}else{
							if(isset($_POST["canvs_image"])){
								$data = substr($_POST["canvs_image"], 22, strlen($_POST["canvs_image"]) - 21);
								$name = makename(substr($_POST["canvs_image"], 100, 8));
								$target_file = $target_dir . $name.".png";
								$msettings = setconffile($name.".png");
								$data = base64_decode($data); 
								file_put_contents($target_file, $data); 
								//$fp = fopen($target_file, 'w');  
								//fwrite($fp, $data);  
								//fclose($fp); 
								$check = checkimage($target_file);
								if($check != "Invalid image"){
									$output = exec("sudo -u www-data python3 /home/web/grot/run.py 2>&1");
									echo "<p>".$output."</p>";
								}else{
									unlink($target_file);
									echo "Something went wrong, try again!";
								}
							}
							
						}
						$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
					}
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
	  <?php 
		  if ($resp != null && $resp->success) {
			echo "<h1>Uploaded project: </h1>";
		  }else{
			  echo "<h1>You have to fill captcha</h1>";
		  }
		?>
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
	</div>
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script>window.jQuery || document.write("<script src="bootstrap/js/vendor/jquery.min.js"><\/script>")</script>
	<script src="bootstrap/js/bootstrap.min.js"></script>
    </body>
</html>
