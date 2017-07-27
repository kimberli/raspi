<?php
	exec("/var/www/code/command.sh bed",$output);
	foreach ($output as $item) {
		echo $item . "<br>";
	}
?>