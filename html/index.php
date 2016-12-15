<html>
<head>
<title>Try GROT online!</title>
<link rel="stylesheet" href="css/normalize.css">
<link rel="stylesheet" href="css/skeleton.css">
</head>
<center>
<body>
<div class="container">
<form action="upload.php" method="POST" enctype="multipart/form-data">
<h1>GROT online</h1>
<input type="file" name="bitmap" id="bitmap" class="button-primary">
<input type="submit" value="Upload Bitmap" name="submit" class="button-primary">
</form>
</center>
<?php
	$projects = glob('uploads/*.{jpg,png,gif,bmp}', GLOB_BRACE);
	$i = 0;
	foreach($projects as $file){?>
		<div class="row pro-nr-<?php $i++; echo $i;?>">
			<div class="wrapper">
				<div class="four columns">
					<h1><?php echo basename($file); ?></h1>
					<img src="uploads/<?php echo basename($file); ?>" width="100%" class="before" alt="<?php echo basename($file) ?> before computation">
					<p><?php echo basename($file);?> before computation</p>
				</div>
				<div class="eight columns">
					<?php
						$plots = glob('generated/'.pathinfo($file, PATHINFO_FILENAME).'/*.{png}', GLOB_BRACE);
						$j = 0;
						foreach($plots as $plot){?>
								<a href="<?php echo $plot; ?>"><img src="<?php echo $plot;?>" width="30%" class="after" alt="<?php echo basename($plot); ?> after computation"></a>
						<?php } ?>
				</div>
			</div>
		</div><hr>
	<?php } ?>
</div>
</body>
</html>
