<?php
	$action = $_POST['action'];
	$stationnum = $_POST['num'];
	exec("/var/www/code/pandora.sh $action $stationnum",$output);
	foreach ($output as $item) {
		echo $item . "<br>";
	}
	//echo "<script>setTimeout(function(){window.location.href='../index.php'},2000);</script>";
?>