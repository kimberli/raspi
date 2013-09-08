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
	exec("$DIR/lights.sh status",$stat,$retval);
?>
<div class='col one-third'>
	<h3>Controls</h3>
	<form method='post'>
		Desk light:
		<div class='form-space'></div>
		<button class='button half' name='light1on'>On</button>
		<button class='button half' name='light1off'>Off</button>
		<div class='form-space'></div>
		Bedside light:
		<div class='form-space'></div>
		<button class='button half' name='light2on'>On</button>
		<button class='button half' name='light2off'>Off</button>
	</form>
</div>
<div class='col one-third'>
	<h3>Status</h3>
	<?php
		echo "<p>";
		foreach ($stat as $it) {
			echo $it . '<br>';
		}
		echo "</p>\n";
	?>
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
