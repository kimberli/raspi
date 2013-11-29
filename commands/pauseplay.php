<?php
	exec("/var/www/code/pandora.sh status 2>&1", $status, $retv);
	if (sizeof($status) > 1) {
		exec("/var/www/code/pandora.sh pause",$output);
	}
	else {
		exec("/var/www/code/pandora.sh start",$output);
	}
	foreach ($output as $item) {
		echo $item . "<br>";
	}
	echo "<script>setTimeout(function(){window.location.href='../index.php'},2000);</script>";
?>