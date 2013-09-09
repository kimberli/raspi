<!DOCTYPE html>
<?php require 'functions.php'; ?>
<?php
	$title="Log Out";
	head();
	before_content($title);
	echo "</head>";
?>
<?php
	setcookie("loggedin", "user", time()-3600);
	echo "<script>setTimeout(function(){window.location.href='index.php'},1500);</script>";
?>
<div class='col one-whole'>
	<p>You have logged out.</p>
	<p>Redirecting you to the main page...</p>
</div>

<?php 
	after_content();
?>

