<?php
	exec("/var/www/code/command.sh leave",$output);
	foreach ($output as $item) {
		echo $item . "<br>";
	}
	echo "<script>setTimeout(function(){window.location.href='../index.php'},2000);</script>";
?>