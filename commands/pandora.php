<?php
	$action = $_POST['action'];
	$stationnum = " ".$_POST['num'];
	$volume = " ".$_POST['vol'];
	exec("/var/www/code/pandora.sh $action$stationnum$volume",$output);
	foreach ($output as $item) {
		echo $item . "<br>";
	}
	//echo "<script>setTimeout(function(){window.location.href='../index.php'},2000);</script>";
?>