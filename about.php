<!DOCTYPE html>
<?php require 'functions.php'; ?>
<?php
	$title="About";
	head();
	before_content($title);
	echo "</head>";
?>
<div class='col one-whole'>
	<p>This server is running on a Raspberry Pi! </p>
	<p>See the source code for this project online at <a target='blank' href='https://github.com/kimberli/raspi'>github.com/kimberli/raspi</a>!</p>
</div>
<?php 
	after_content();
?>