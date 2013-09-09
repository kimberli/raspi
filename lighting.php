<!DOCTYPE html>
<?php require 'functions.php'; ?>
<?php
	$title="Lighting";
	head();
	before_content($title);
	echo "</head>";
?>
<?php
	$DIR = "code";
	if (isset($_COOKIE['loggedin'])) {
		if (isset($_POST['light1on'])) {
			exec("$DIR/lights.sh 1 on",$output,$retval);
		}
		if (isset($_POST['light1off'])){
			exec("$DIR/lights.sh 1 off",$output,$retval);
		}
		if (isset($_POST['light2on'])) {
			exec("$DIR/lights.sh 2 on",$output,$retval);
		}
		if (isset($_POST['light2off'])){
			exec("$DIR/lights.sh 2 off",$output,$retval);
		}
	}
	exec("$DIR/lights.sh status",$stat,$retval);
?>
<div class='col one-third'>
	<h3>Controls</h3>
	<?php if (!isset($_COOKIE['loggedin'])) { echo "<p>Please <a href='login.php'>log in</a>.</p>"; } ?>
	<form method='post'>
		<p>Desk light: <em><?php echo substr($stat[0],9); ?></em></p>
		<?php if (isset($_COOKIE['loggedin'])) { ?>
		<button class='button half' name='light1on'>On</button>
		<button class='button half' name='light1off'>Off</button>
		<div class='form-space'></div>
		<?php } ?>
		<p>Bedside light: <em><?php echo substr($stat[1],9); ?></em></p>
		<?php if (isset($_COOKIE['loggedin'])) { ?>
		<button class='button half' name='light2on'>On</button>
		<button class='button half' name='light2off'>Off</button>
		<?php } ?>
	</form>
</div>
<div class='col one-third'>
	<h3>Output</h3>
	<?php
		echo '<p>';
		foreach ($output as $item) {
			echo $item . '<br>';
		}
		echo "</p>\n";
	?>
</div>

<?php 
	after_content();
?>
