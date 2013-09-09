<!DOCTYPE html>
<?php require 'functions.php'; ?>
<?php
	$title="Log In";
	head();
	before_content($title);
	echo "</head>";
?>
<?php
	$correctusername = "user"; #enter your username here
	$correctpassword = "pass"; #enter your password here
	if (isset($_POST['submit'])) {
		$user = $_POST['username'];
		$pass = $_POST['password'];
		if (strcmp($user,$correctusername)==0 && strcmp($pass,$correctpassword)==0) {
			setcookie("loggedin", "user", time()+604800);
			$message = "You have logged in!";
		}
		else {
			$message = "Incorrect username/password combination.";
		}
	}
?>
<div class='col one-whole'>
	<?php echo "<p>" . $message . "</p>"; ?>
	<?php if (isset($_COOKIE['loggedin'])) { ?>
		<p>You are already logged in. (<a href='logout.php'>Log out</a>)</p>
	<?php } else if (strcmp($message,"You have logged in!")!=0) { ?>
	<form method='post'>
		Username: <input type='text' name='username'>
		<div class='form-space'></div>
		Password: <input type='password' name='password'>
		<div class='form-space'></div>
		<button class='button' name='submit'>Submit</button>
	</form>
	<?php } 
		else { 
			echo "<p>Redirecting you to the main page...</p>"; 
			echo "<script>setTimeout(function(){window.location.href='index.php'},1500);</script>";
		} ?>
</div>

<?php 
	after_content();
?>

