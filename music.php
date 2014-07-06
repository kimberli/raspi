<!DOCTYPE html>
<?php require 'functions.php'; ?>
<?php
	$title="Music";
	head();
	before_content($title);
	echo "\t<meta http-equiv='refresh' content='30'>\n";
	echo "</head>";
?>
<?php
	$DIR = "/var/www/code";
	if (isset($_COOKIE['loggedin'])) {
		if (isset($_POST['start'])) {
			exec("$DIR/pandora.sh startbg",$output,$retval);
			echo '<script>setTimeout(function(){parent.window.location.reload(true)},8000);</script>';
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
		if (isset($_POST['volset'])) {
			$TARGETVOL = $_POST['vol'];
			exec("$DIR/pandora.sh vol $TARGETVOL",$output,$retval);
		}
		if (isset($_POST['stop'])) {
			exec("$DIR/pandora.sh stop",$output,$retval);
		}
	}

	exec("$DIR/pandora.sh status 2>&1", $status, $retv);
	$VOLUME = $status[1];
	$STATE = $status[2];
?>
<div class='col one-third'>
	<h3>Controls</h3>
	<?php if (isset($_COOKIE['loggedin'])) { ?>
	<form method='post'>
		<input type='submit' class='default tiny' name='volset' value='Go'>
<?php
	if (sizeof($status) > 1) {
		echo "\t\t<button class='first button' name='stop'>Stop Pandora</button>\n";
	}
	else {
		echo "\t\t<button class='first button' name='start'>Start Pandora</button>\n";
	}
?>
		<div class='form-space'></div>
		<button class='button' name='pause'> <?php if (strcmp($STATE,"paused")==0) { echo "Resume Song"; } else { echo "Pause Song"; } ?></button>
		<div class='form-space'></div>
		<button class='button' name='skip'>Skip Song</button>
		<div class='form-space'></div>
		<button class='button' name='like'>Like Song</button>
		<div class='form-space'></div>
		<select name='station'>
		<?php
			$stationlist = array_slice($status,9);
			for ($i = 0; $i < sizeof($stationlist); $i++) {
				echo "\t<option value='".$i."'";
				if (strcmp(substr($stationlist[$i],3),$status[5])==0) {
					echo " selected";
				}
				echo ">".substr($stationlist[$i],3)."</option>\n\t\t";
			} 
		?></select>
		<div class='form-space'></div>
		<button class='button tiny' name='vup'>+</button>
		<button class='button tiny' name='vdown'>-</button>
		<input type='number' name='vol' min='-15' max='10' value='<?php echo $VOLUME; ?>'>
	</form>
	<?php } else { echo "<p>Please <a href='login.php'>log in</a>.</p>"; } ?>
</div>

<div class='col one-third'>
	<h3>Now Playing</h3>
	<p>
<?php
	if (sizeof($status) > 1) {
		echo "\t\"" . $status[4] . "\"" . "<br>\n";
		echo "\t" . $status[3] . "<br>\n";
		echo "\t<em>" . $status[8] . "</em><br><br>\n";
		echo "\t<img src='" . $status[7] . "' class='album'><br><br>\n";
		echo "\tCurrent Volume: " . $status[1] . "  (" . $status[2] . ")<br>\n";
	}
	else {
		echo $status[0];
	}
?>
	</p>
</div>

<div class='col one-third'>
	<h3>Output</h3>
	<p>
<?php
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
