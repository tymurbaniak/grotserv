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
	<link href="assets/css/drawing.css" rel="stylesheet">
	<script src='https://www.google.com/recaptcha/api.js'></script>
  </head>
  <body>
    <!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="jumbotron">
      <div class="container">
		<div class="row">
			<div class="col-md-9">
				<img src="assets/logo.png" alt="logo">
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
		<form action="upload.php" method="POST" enctype="multipart/form-data" id="projectform">
			<div class="col-md-4">
				<h2>Draw or upload</h2>
					<div class="tools">
						<?php
							$colors = array('#f00', '#ff0', '#0f0', '#0ff', '#00f', '#f0f', '#000', '#fff');
							foreach($colors as $color){
								echo "<a href='#canv' data-color='".$color."' style='background: ".$color.";'>&nbsp;</a>";
							}
							$sizes = array(3, 5, 10, 15);
							foreach($sizes as $size){
									echo "<a  class='size' href='#canv' data-size='".$size."' style='background: #ccc;'>".$size."</a>";
							}
						?>
					</div>
					<canvas id="canv" height="355" style="border: 1px solid #000; margin: 1px 0 0 0;"></canvas>
				<div class="form-group form-inline">
					<input type="hidden" name="canvs_image" id="canvs_image">
					<input type="file" name="bitmap" id="bitmap" class="btn btn-primary btn-lg btn-block">
					<input type="submit" value="Upload Bitmap" name="submit" class="btn btn-primary btn-lg btn-block">
				</div>
			</div>
			<div class="col-md-4">
			  <h2>Settings</h2>
			  <p>
				<div class="row form-group">
					<div class="col-md-6">
						<label for="problem">Problem:</label>
						<select class="form-control" name="problem" id="problem">
							  <option value="planestress">Planestress</option>
							  <option value="planestrain">Planestrain</option>
						</select>
					</div>
					<div class="col-md-6">
						<label for="mat">Material:</label>
						<select class="form-control" name="mat" id="mat">
							  <option value="steel">Steel</option>
							  <option value="alu">Alu</option>
							  <option value="titan">Titan</option>
						</select>
					</div>
				</div>
				<div class="row form-group">
					<div class="col-md-6">
						<label for="unit">Unit:</label>
						<select class="form-control" name="unit" id="unit">
							  <option value="nm">nm</option>
							  <option value="um">um</option>
							  <option value="mm">mm</option>
							  <option value="cm">cm</option>
							  <option value="dm">dm</option>
							  <option value="m">m</option>
							  <option value="km">km</option>
						</select>
					</div>
					<div class="col-md-6">
						<label for="scale">Scale:</label>
						<input type="number" class="form-control" placeholder="1" name="scale" value="1" id="scale" min="0">
					</div>
				</div>
				<div class="row form-group">
					<div class="col-md-6">
						<label for="solver">Solver: </label>
						<select class="form-control" name="solver" id="solver">
							  <option value="direct">Direct</option>
							  <option value="lsqs">Lsqs</option>
						</select>
					</div>
					<div class="col-md-6">
						<label for="deformation">Deformation scale: </label><input type="number" class="form-control" placeholder="1" value="2" name="deformation" id="deformation">
					</div>
				</div>
				<label for="loadgroup">Load:</label>
				<div class="row form-group" id="loadgroup">
					<div class="col-md-3">
						<label for="loadx">X: </label><input type="number" class="form-control" value="0" name="loadx" id="loadx">
					</div>
					<div class="col-md-3">
						<label for="loady">Y: </label><input type="number" class="form-control" value="-100" name="loady" id="loady">
					</div>
					<div class="col-md-6">
						<label for="loadcolor">Color: </label>
						<select class="form-control" name="loadcolor" id="loadcolor">
							  <option value="magenta">magenta</option>
							  <option value="bronze">bronze</option>
							  <option value="black">black</option>
						</select>
					</div>
				</div>
				<div class="form-group">
					<div class="row">
						<div class="col-md-8">
							<label>Stress: </label>
						</div>
						<div class="col-md-4">
							<label>Displacement: </label>
						</div>
					</div>
					<div class="row">
						<div class="col-md-9">
							<label><input type="checkbox" value="eps_x" name="stress[]">eps x </label>
							<label><input type="checkbox" value="sig_x" name="stress[]">sig x </label>
							<label><input type="checkbox" value="sign_huber" name="stress[]">sign huber</label>
							<label><input type="checkbox" value="huber" name="stress[]">huber </label>
						</div>
						<div class="col-md-3">
							<label><input type="checkbox" name="disp[]" value="x">X </label>
							<label><input type="checkbox" name="disp[]" value="y">Y </label>
						</div>
					</div>
					<div class="row">
						<div class="col-md-9">
							<label><input type="checkbox" value="eps_y" name="stress[]">eps y </label>
							<label><input type="checkbox" value="sig_y" name="stress[]">sig y </label>
							<label><input type="checkbox" value="gamma_xy" name="stress[]">gamma xy</label>
							<label><input type="checkbox" value="tau_xy" name="stress[]">tau xy</label>
						</div>
						<div class="col-md-3">
							<label><input type="checkbox" name="disp[]" value="mag">Magn.</label>
						</div>
					</div>
				</div>
				<div class="g-recaptcha" data-sitekey="6Le3LRIUAAAAANuzz6OuplVueBzpgxpMArKQqoXg"></div>
		   </div>
		</form>
        <div class="col-md-4">
          <h2><a href="gallery.php">Gallery &raquo;</a></h2>
			<?php
				$displayed = array();
				$projects = glob('uploads/*.{jpg,png,gif,bmp}', GLOB_BRACE);
				foreach($projects as $file){
					$plots = glob('generated/'.pathinfo($file, PATHINFO_FILENAME).'/*.{png}', GLOB_BRACE);
					foreach($plots as $plot){
						array_push($displayed, $plot);
					}
				}
				$numbers = range(0, count($displayed)-1);
				shuffle($numbers);
				$randomized = array_slice($numbers, 0, 6);
				foreach($randomized as $number){ ?>
					<a href="<?php echo $displayed[$number]; ?>"><img src="<?php echo $displayed[$number]; ?>" width="49%" class="after" alt="<?php echo basename($displayed[$number]); ?> after computation"></a>
				<?php }
			?>
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
    <script>window.jQuery || document.write('<script src="bootstrap/js/jquery.min.js"><\/script>');</script>
	<script src="bootstrap/js/bootstrap.min.js"></script>
	<script src="assets/js/drawing.js"></script>
	<script type="text/javascript">
	  $(function() {
		$.each([], function() {
		  $('#colors_demo .tools').append("<a href='#canv' data-color='" + this + "' style='width: 10px; background: " + this + ";'></a> ");
		});
		$.each([3, 5, 10, 15], function() {
		  $('#colors_demo .tools').append("<a href='#canv' data-size='" + this + "' style='background: #ccc'>" + this + "</a> ");
		});
		$('#canv').sketch();
	  });
	</script>
	<script type="text/javascript">
	$(function() {
		$('#projectform').submit(function() {
			var img = document.getElementById("canv");
			var ctx = img.getContext("2d");
			var scaled = document.createElement('canvas');
			scaled.width = 50;
			scaled.height = 50;
			var scaledctx = scaled.getContext("2d");
			scaledctx.drawImage(img, 0, 0, img.width, img.height,     // source rectangle
						0, 0, 50, 50); // destination rectangle
			var dataURL = canvasToImage("white", scaledctx, scaled);
			document.getElementById('canvs_image').value = dataURL;
			//chyba musi byÄ‡ create!
			return true; 
		});
		function canvasToImage(backgroundColor ,context, canvas){
			//cache height and width        
			var w = canvas.width;
			var h = canvas.height;
			var data;
			if(backgroundColor)
			{
				data = context.getImageData(0, 0, w, h);
				
				var compositeOperation = context.globalCompositeOperation;
				context.globalCompositeOperation = "destination-over";
				context.fillStyle = backgroundColor;
				context.fillRect(0,0,w,h);
			}
			//get the image data from the canvas
			var imageData = canvas.toDataURL("image/png");
			if(backgroundColor)
			{
				context.clearRect (0,0,w,h);
				context.putImageData(data, 0,0); 			
				context.globalCompositeOperation = compositeOperation;
			}
			return imageData;
		}
	});
	</script>
    </body>
</html>
