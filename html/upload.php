<html>
<head>
</head>
<body>
<center><?php
$target_dir = "/var/www/html/uploads/";
$target_file = $target_dir . basename($_FILES["bitmap"]["name"]);
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["bitmap"]["tmp_name"]);
    if($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
	if(move_uploaded_file($_FILES["bitmap"]["tmp_name"], $target_file)){
		echo "File uploaded!<br>";
	}else{
		echo "Upload errror<br>";
	}
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
}
$lines = array();
foreach(file('input.txt') as $line){
	if(preg_match('/project /',$line)){
		 $line = "project ".pathinfo($target_file, PATHINFO_FILENAME)."\n";
	}
	if(preg_match('/bmp /',$line)){
		 $line = "bmp ".pathinfo($target_file, PATHINFO_FILENAME).".bmp\n";
	}
	array_push($lines, $line);
}
file_put_contents('input.txt', $lines);
$output = exec('sudo -u www-data python3 /home/web/grot/run.py 2>&1');
echo "<h2>".$output."</h2>";
?>
<br><a href="http://193.70.112.207">Return</a>
</center>
</body>
</html>
