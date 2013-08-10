<!DOCTYPE html>
<?php require 'functions.php'; ?>
<?php
	$title="Lighting";
	head();
	before_content($title);
?>
<div class='col one-third'>
	<h3>Controls</h3>
	<form method='post'>
		<p>Bedside light:</p>
		<button class='button half' name='light2on'>On</button>
		<button class='button half' name='light2off'>Off</button>
	</form>
</div>
<div class='col one-third'>
	<h3>Output</h3>
	<?php
		$DIR = "code";
		if (isset($_POST['light2on'])) {
			exec("$DIR/lights.sh 2 on",$output,$retval);
		}
		if (isset($_POST['light2off'])){
			exec("$DIR/lights.sh 2 off",$output,$retval);
		}
			else {
		}
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