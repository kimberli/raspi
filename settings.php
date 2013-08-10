<!DOCTYPE html>
<?php require 'functions.php'; ?>
<?php
	$title="Settings";
	head();
	before_content($title);
?>
<div class='col one-whole'>
	<p>This page is currently under construction!!!!!!</p>
	<h3>Current Tasks:</h3>
	<?php
		if (isset($_POST['submit'])) {
			$line = $_POST['addline'];
			exec("echo \"$line\" >> /etc/crontab");
		}
		else if (isset($_POST['deleteline'])) {
			exec("sed '$d' /etc/crontab > hi.html");
		}
		exec("cat /etc/crontab",$output);
		$size = sizeof($output);
		for ($i = 16; $i < $size; $i++) {
			echo $output[$i] . "</br>";
		}
	?>
	<form method="post">
		<p>
		Add a line:
		</p>
		<input type='text' name='addline'>
		<input type='submit' name='submit'>
		<div class='form-space'></div>
		<button class='button' name='deleteline'>Delete Last Line</button>
	</form>
</div>
<?php 
	after_content();
?>