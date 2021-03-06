<!DOCTYPE html>
<?php
	include 'config.php';
	$DIR = '/var/www/code';

	//login check
	if (isset($_POST['login'])) {
		$user = $_POST['username'];
		$pass = $_POST['password'];
		if (strcmp($user,$correctusername)==0 && strcmp($pass,$correctpassword)==0) {
			setcookie("loggedin", "user", time()+86400*31);
			$message = "You have logged in!";
			header('Location: index.php');
		}
		else {
			$message = "Incorrect username/password combination.";
		}
	}
?>
<html lang='en'>
	<head>
		<title>Kimberli's Raspberry Pi</title>
		<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
		<meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1'>
		<meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=no'>
		<link rel='shortcut icon' href='resources/icons/favicon.ico'>
		<link rel='stylesheet' href='resources/css/style2.css'>
		<script src='http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js'></script>
	</head>
	
	<div class='preloaded-images'>
	   <img src='resources/css/bg-large-focus.jpg' height='1' width='1'/>
	   <img src='resources/css/bg-large-unfocus.jpg' height='1' width='1'/>
	   <img src='resources/css/bg-small-focus.jpg' height='1' width='1'/>
	   <img src='resources/css/bg-small-unfocus.jpg' height='1' width='1'/>
	</div>
	<body id='home-page'>
		<div class='loading-mask'></div>
		<header>
			<div class='content page-header'>
				<h1 class='page-title'>Welcome Home.</h1>
			</div>
		</header>
		<div class='page-body'>
		<main>
			<div class='content bvc'>
				<div class='bevel tl tr'></div>
				<div class='page-main'>
					<div class='top-bar' id='module-status'>
						<div class='top-bar-item'>
							<?php echo date('l, F d') . "\n"; ?>
							<div class="sep">&hearts;</div>
						</div>
						<div class='top-bar-item weather'>
							<strong>Now: </strong>
							<?php 
								$xml=simplexml_load_file("http://api.wunderground.com/api/$w_api/conditions/astronomy/forecast/q/$w_state/$w_city.xml");
								echo $xml->current_observation->temp_f . " &deg;F ";
								echo $xml->current_observation->weather;
								echo " <img src='resources/icons/weather/" . $xml->current_observation->icon . ".svg' height='16'>\n";
							?>
							<div class="sep">&hearts;</div>
						</div>
						<div class='top-bar-item weather'>
							<strong>Tomorrow: </strong>
							<?php 
								echo $xml->forecast->simpleforecast->forecastdays->forecastday[1]->high->fahrenheit . " &deg;F / ";
								echo $xml->forecast->simpleforecast->forecastdays->forecastday[1]->low->fahrenheit . " &deg;F ";
								echo $xml->forecast->simpleforecast->forecastdays->forecastday[1]->conditions;
								echo " <img src='resources/icons/weather/" . $xml->forecast->simpleforecast->forecastdays->forecastday[1]->icon . ".svg' height='16'>\n";
							?>
							<div class="sep">&hearts;</div>
						</div>
						<div class='top-bar-item' id='time'>
							<?php
								date_default_timezone_set('US/Pacific');
								echo date('g:i:s a') . "\n";
							?>
						</div>
					</div>
					<div class='modules'>
						<?php 	if (isset($_COOKIE['loggedin'])) { /*if logged in*/ ?><div class='module' id='module-music'>
							<h3>Music</h3>
							<button class='start music-control'>Start</button>
							<button class='stop music-control'>Stop</button>
							<button class='play-pause music-control'>Pause</button>
							<button class='skip music-control'>Skip</button>
							<button class='like music-control'>Like</button>
							<input type='number' class='volume' min='-15' max='10'><br>
							<select class='station-select'>
							</select><br>
							<span class='song-info'></span><br><br>
							<img class='song-image album'></img>
						</div>
						<div class='module' id='module-lighting'>
							<h3>Lighting</h3>
							<button class='light1-on light-control'>On</button>
							<button class='light1-off light-control'>Off</button><br>
							<button class='light2-on light-control'>On</button>
							<button class='light2-off light-control'>Off</button>
						</div>
						<div class='module' id='module-tasks'>
							<h3>Tasks</h3>
						</div>
						<div class='module' id='module-todo'>
							<h3>To-Do</h3>
						</div>
					<?php } else { /*if not logged in*/?><div class='module' id='module-login'>
							<h2>Please log in.</h2>
							<div class='error-message'><?php echo $message; ?></div>
							<form method='post'>
								<input type='text' name='username' placeholder='Username'>
								<div class='form-space'></div>
								<input type='password' name='password' placeholder='Password'>
								<div class='form-space'></div>
								<button class='button-submit' name='login'>Log In</button>
								<div class='form-space'></div>
							</form>
						</div>
					<?php } ?></div>
				</div>
				<div class='bevel bl br'></div>
			</div>
		</main>
		<footer>
			<div class='content page-footer'>
				Created by Kimberli Zhong. View this project on GitHub at <a href='https://www.github.com/kimberli/raspi' target='_blank'>www.github.com/kimberli/raspi</a>.
			</div>
		</footer>
		</div>
		<script src='resources/js/scripts.js'></script>
	</body>
</html>
