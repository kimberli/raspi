<?php

//call this function to set up the head tags
//don't forget to put a </head> after calling it
	function head() {
		echo "<head>\n";
		echo "\t<title>Kimberli's Raspberry Pi</title>\n";
		echo "\t<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />\n";
		echo "\t<meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1'>\n";
		echo "\t<meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=no'>\n";
		echo "\t<link rel='shortcut icon' href='resources/icons/favicon.ico'>\n";
		echo "\t<link rel='stylesheet' href='resources/css/style.css'>\n";
		echo "\t<link href='http://fonts.googleapis.com/css?family=Cherry+Swash:700' rel='stylesheet' type='text/css'>\n";
		echo "\t<link href='http://fonts.googleapis.com/css?family=Nunito:300' rel='stylesheet' type='text/css'>\n";
		echo "\t<script src='http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js'></script>\n";
		echo "\t<script>\n";
		echo "\t\t$(document).ready(function() {\n";
		echo "\t\t\t$('.menu-button').click(function(){\$('.menu-button,.menu').toggleClass('open');});\n";
		echo "\t\t});\n";
		echo "\t</script>\n";
	}

//call this function before using the classes "col" and "one-whole", "one-half", or "one-third"
//this function opens the "section page-text" div tag
//the parameter is the page's title
	function before_content($pagetitle) {
		echo "<body class='home-page'>\n";
		echo "\t<div class='wrapper'>\n";
		echo "\t\t<div class='section header-holder'>\n";
		echo "\t\t\t<div class='header'>\n";
		echo "\t\t\t\t<h1>Kimberli's Raspberry Pi</h1>\n";
		echo "\t\t\t</div>  <!-- header -->\n";
		echo "\t\t</div>  <!-- section header-holder -->\n\n";
		echo "\t\t<div class='section menu-holder'>\n";
		echo "\t\t\t<button class='menu-button'></button>\n";
		echo "\t\t\t<ul class='menu'>\n";
		echo "\t\t\t\t<li><a href='/'>Home</a></li>\n";
		echo "\t\t\t\t<li><a href='music.php'>Music</a></li>\n";
		echo "\t\t\t\t<li><a href='lighting.php'>Lighting</a></li>\n";
		echo "\t\t\t\t<li><a href='settings.php'>Settings</a></li>\n";
		echo "\t\t\t\t<li><a href='about.php'>About</a></li>\n";
		echo "\t\t\t</ul>\n";
		echo "\t\t</div>  <!-- section menu-holder -->\n\n";
		echo "\t\t<div class='section content-holder'>\n";
		echo "\t\t\t<div class='content'>\n";
		echo "\t\t\t\t<div class='section page-title-holder'>\n";
		echo "\t\t\t\t\t<h2 class='page-title'>" . $pagetitle . "</h2>\n";
		echo "\t\t\t\t</div>  <!-- section page-title-holder -->\n\n";
		echo "\t\t\t\t<div class='section page-text'>\n";
	}

//call this function after closing whatever div tags you opened in your content
//this function closes the "section page-text" div tag
	function after_content() {
		echo "\t\t\t\t</div>  <! -- section page-text -->\n";
		echo "\t\t\t</div>  <!-- content -->\n";
		echo "\t\t</div>  <!-- content-holder -->\n\n";
		echo "\t\t<div class='section footer-holder'>\n";
		echo "\t\t\t<div class='footer'>\n";
		echo "\t\t\t\t<br><br><em>Last refreshed on ";
		date_default_timezone_set('US/Pacific');
		echo date('l, F d, Y g:i:s a') . " PST </em>&nbsp;\n";
		if (isset($_COOKIE['loggedin'])) {
			echo "\t\t\t\t(<a href='logout.php'>Log out</a>)\n";
		}
		else {
			echo "\t\t\t\t(<a href='login.php'>Log in</a>)\n";
		}
		echo "\t\t\t</div>  <!-- footer-holder -->\n";
		echo "\t\t</div>  <!-- footer-holder -->\n";
		echo "\t</div>  <!-- wrapper -->\n";
		echo "</body>";
	}

?>
