<?php
	$filearray = file("input.txt");
	echo "<br><br>";
	var_dump($filearray);
	$lastfifteenlines = array_slice($filearray,0,12);
	echo "<br><br>";
	var_dump($lastfifteenlines);
	file_put_contents("input.txt", $lastfifteenlines);
?>