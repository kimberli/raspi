<!DOCTYPE html>
<?php require 'functions.php'; ?>
<?php
	$title="Music";
	head();
	before_content($title);
?>
<div class='col one-third'>
<?php
	$DIR = "/var/www/code";
	if (isset($_POST['start'])) {
		exec("$DIR/pandora.sh startbg",$output,$retval);
		echo '<script>setTimeout(function(){parent.window.location.reload(true)},7000);</script>';
	}
	if (isset($_POST['pause'])){
		exec("$DIR/pandora.sh pause",$output,$retval);
	}
	if (isset($_POST['skip'])) {
		exec("$DIR/pandora.sh skip",$output,$retval);
		echo '<script>setTimeout(function(){parent.window.location.reload(true)},3000);</script>';
	}
	if (isset($_POST['like'])) {
		exec("$DIR/pandora.sh like",$output,$retval);
	}
	if (isset($_POST['vup'])) {
		exec("$DIR/pandora.sh volup",$output,$retval);
	}
	if (isset($_POST['vdown'])) {
		exec("$DIR/pandora.sh voldown",$output,$retval);
	}
	if (isset($_POST['stop'])) {
		exec("$DIR/pandora.sh stop",$output,$retval);
	}
	exec("$DIR/pandora.sh status 2>&1", $status, $retv);

	echo "\t<h3>Controls</h3>\n";
	echo "\t<form method='post'>\n";
	if (sizeof($status) > 1) {
		echo "\t\t<button class='button' name='stop'>Stop Pandora</button>\n";
	}
	else {
		echo "\t\t<button class='button' name='start'>Start Pandora</button>\n";
	}
	echo "\t\t<div class='form-space'></div>\n";
	echo "\t\t<button class='button' name='pause'>Pause/Play</button>\n";
	echo "\t\t<div class='form-space'></div>\n";
	echo "\t\t<button class='button' name='skip'>Skip Song</button>\n";
	echo "\t\t<div class='form-space'></div>\n";
	echo "\t\t<button class='button' name='like'>Like Song</button>\n";
	echo "\t\t<div class='form-space'></div>\n";
	echo "\t\t<button class='button half' name='vup'>+</button>\n";
	echo "\t\t<button class='button half' name='vdown'>-</button>\n";
	echo "\t</form>\n";
	echo "</div>\n\n";

	echo "<div class='col one-third'>\n";
	echo "\t<h3>Now Playing</h3>\n";
	echo "\t<p>\n";

	if (sizeof($status) > 1) {
		echo "\"" . $status[4] . "\"" . "<br>\n";
		echo "\t" . $status[3] . "<br>\n";
		echo "\t<em>" . $status[8] . "</em><br><br>\n";
		echo "\t<img src='" . $status[7] . "' class='album'><br><br>\n";
		echo "\t" . $status[1] . "  (" . $status[2] . ")<br>\n";
	}
	else {
		echo $status[0];
	}
	echo "\t</p>\n";
	echo "</div>\n";
	echo "<div class='col one-third'>\n";
	echo "\t<h3>Output</h3>\n";
	echo "\t<p>\n";
	echo "\t";
	foreach ($output as $item) {
		echo $item . '<br>';
	}
	echo "\n\t</p>\n";
	echo "\n";
?>
</div>
<?php 
	after_content();
?>
