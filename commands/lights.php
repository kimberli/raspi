<?php
	$action = $_GET['action'];
	$state = " ".$_GET['state'];
	exec("/var/www/code/lights.sh $action$state",$output);
	foreach ($output as $item) {
		echo $item . "<br>";
	}
	//echo "<script>setTimeout(function(){window.location.href='../index.php'},2000);</script>";
?>