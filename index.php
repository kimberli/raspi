<!DOCTYPE html>
<?php require 'functions.php'; ?>
<?php
	$title="Home";
	head();
	before_content($title);
	echo "</head>";
?>
<?php
	$DIR="/var/www/code";
	if (isset($_POST['restart'])) {
		exec("sudo shutdown -r now",$output,$retval);
	}
	else if (isset($_POST['allon'])) {
		exec("$DIR/pandora.sh startbg",$output,$retval);
		exec("$DIR/lights.sh 1 on",$output,$retval);
		exec("$DIR/lights.sh 2 on",$output,$retval);
	}
	else if (isset($_POST['alloff'])) {
		exec("$DIR/pandora.sh stop",$output,$retval);
		exec("$DIR/lights.sh 1 off",$output,$retval);
		exec("$DIR/lights.sh 2 off",$output,$retval);
	}
	exec("$DIR/pandora.sh status 2>&1", $mstatus, $retv);
	exec("$DIR/lights.sh status",$lstatus,$retval);
?>
<div class='col one-whole'>
	<p>Welcome to the homepage of Kimberli's Raspberry Pi!</p>
</div>
<?php if (isset($_COOKIE['loggedin'])) { ?>
<div class='col one-third'>
	<h3>Summary</h3>
	<p><?php
		if (sizeof($mstatus) > 1) {
			echo "Music is " . $mstatus[2];
		}
		else {
			echo "Music is not playing";
		}
	?></p>
	<p>Light 1 is o<?php echo substr($lstatus[0],10); ?></p>
	<p>Light 2 is o<?php echo substr($lstatus[1],10); ?></p>
</div>
<div class='col one-third'>
	<h3>Raspberry Pi</h3>
	<p>Temperature: <?php 
		exec("/opt/vc/bin/vcgencmd measure_temp",$temp,$retv);
		echo substr($temp[0],5);
	?></p>
	<form method='post'>
		<button class='button' name='restart'>Restart Pi</button>
	</form>
</div>
<div class='col one-third'>
	<h3>Commands</h3>
	<form method='post'>
		<button class='button' name='allon'>All On</button>
		<div class='form-space'></div>
		<button class='button' name='alloff'>All Off</button>
	</form>
</div>
<?php } ?>
<?php 
	after_content();
?>