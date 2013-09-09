<!DOCTYPE html>
<?php require 'functions.php'; ?>
<?php
	$title="Settings";
	head();
	before_content($title);
	echo "</head>";
?>
<?php
	$DIR="/var/www/code";
	exec("$DIR/cronedit.sh list",$cronjobs);
	$emailaddr = $_POST['emailaddress'];
	
	if (isset($_COOKIE['loggedin'])) {
		//Adding a new task
		if (isset($_POST['submit'])) {
			$min = $_POST['min'];
			$hour = $_POST['hour'];
			$ampm = $_POST['ampm'];
			$dom = $_POST['dom'];
			$month = $_POST['month'];
			$dow = $_POST['dow'];
			if (strcmp($ampm,"pm")==0) {
				if ($hour != 12 ) {
					$hour += 12;
				}
			}
			else {
				if ($hour == 12) {
					$hour = 0;
				}
			}
			//Task so far with all time details
			$line = "$min $hour $dom $month $dow ";

			$category = $_POST['category']; //Checking whether music-related or lighting-related
			//If task is music-related
			if (strcmp($category,"Music")==0) { 
				$musictask = $_POST['mtask'];
				if (strcmp($musictask,"Volume")==0) { //if user wanted to change volume
					$volset = $_POST['volset'];
					$line .= "/var/www/code/pandora.sh vol $volset";
				}
				else {
					$line .= $musictask;
				}
			}
			//If task is lighting-related
			else if (strcmp($category,"Lighting")==0) {
				$light = $_POST['light'];
				$state = $_POST['state'];
				$line .= "/var/www/code/lights.sh $light $state";
			}
			//If task is email-related
			else if (strcmp($category,"Email")==0) {
				$subj = $_POST['subject'];
				$body = $_POST['body'];
				$line .= "echo \"$body\" | mail -s \"$subj\" $emailaddr";
			}
			//If task is a comment
			else if (strcmp($category,"Comment")==0) {
				$comt = $_POST['comment'];
				$line = "#$comt";
			}
			else {
				$quit=true;
			}
			if ($quit == false) {
				exec("$DIR/cronedit.sh write \"$line\"",$output); //write file to cron
			}
		}
		//deleting tasks
		else if (isset($_POST['deleteline'])) {
			$dline = $_POST['linenum'];
			exec("$DIR/cronedit.sh remove \"$dline\"",$output);
		}
	}
?>

<div class='col one-third'>	
	<?php if (isset($_COOKIE['loggedin'])) { ?>
	<h3>Add a Task</h3>
	<form method='post'>
		<div style='display: inline;' title='hh:mm'>Time: <select name='hour'>
			<option value='*'>*</option>
			<?php
				for ($k=1; $k<13; $k++) { //populate with numbers from 1 to 12
					echo "<option value='" . $k . "'>" . $k . "</option>";
				}
			?>
		</select> :
		<select name='min'>
			<option value='*'>*</option>
			<option value='00'>00</option>
			<option value='05'>05</option>
			<?php
				for ($k=10; $k<60; $k+=5) { //populate with multiples of 5 from 0-55
					echo "<option value='" . $k . "'>" . $k . "</option>";
				}
			?>
		</select></div>
		<select name='ampm'>
			<option value="am">am</option>
			<option value="pm">pm</option>
		</select>
		<div class='form-space'></div>
		<div style='display: inline;' title='Day of the week'>DOW:
		<select name='dow'>
			<option value='*'>* All</option><option value='0'>Sunday</option><option value='1'>Monday</option><option value='2'>Tuesday</option><option value='3'>Wednesday</option><option value='4'>Thursday</option><option value='5'>Friday</option><option value='6'>Saturday</option><option value='1-5'>Weekdays</option><option value='0,6'>Weekends</option>
		</select></div>
		<div class='form-space'></div>
		<div style='display: inline;' title='Day of the month'>DOM:
		<select name='dom'>
			<option value='*'>* All</option>
			<?php
				for ($k=1; $k<32; $k++) {
					echo "<option value='" . $k . "'>" . $k . "</option>";
				}
			?>
			</select></div>
		<div class='form-space'></div>
		Month:
		<select name='month'>
			<option value='*'>* All</option><option value='1'>January</option><option value='2'>February</option><option value='3'>March</option><option value='4'>April</option><option value='5'>May</option><option value='6'>June</option><option value='7'>July</option><option value='8'>August</option><option value='9'>September</option><option value='10'>October</option>option value='11'>November</option><option value='12'>December</option>
		</select>
		<div class='form-space'></div>
		Category:
		<select name='category' id='dd'>
			<option></option>
			<option value='Music'>Music</option>
			<option value='Lighting'>Lighting</option>
			<option value='Email'>Email</option>
			<option value='Comment'>Comment</option>
		</select>
		<div class='form-space'></div>
		<div id='Music'>
			&nbsp;&nbsp;Task:
			<select name='mtask' id='ddd'>
				<option value='/var/www/code/pandora.sh alarm'>Set Alarm</option>
				<option value='/var/www/code/pandora.sh start'>Start Pandora</option>
				<option value='/var/www/code/pandora.sh stop'>Stop Pandora</option>
				<option value='/var/www/code/pandora.sh pause'>Pause Song</option>
				<option value='Volume'>Volume</option>
			</select>
			<div id='Volume'>
				<div class='form-space'></div>
				&nbsp;&nbsp;&nbsp;&nbsp;Set Volume:
				<input name='volset' type='number' min='-15' max='10'>
			</div>
		</div>
		<div id='Lighting'>
			&nbsp;&nbsp;Light:
			<select name='light'>
				<option value='1'>1</option>
				<option value='2'>2</option>
			</select>
			<select name='state'>
				<option value='on'>On</option>
				<option value='off'>Off</option>
			</select>
		</div>
		<div id='Email'>
			&nbsp;&nbsp;To:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<input type='email' name='emailaddress'>
			<div class='form-space'></div>
			&nbsp;&nbsp;Subject:
			<input type='text' name='subject' maxlength='40'>
			<div class='form-space'></div>
			&nbsp;&nbsp;Body:
			<textarea name='body' rows='3'></textarea>
		</div>
		<div id='Comment'>
			&nbsp;&nbsp;Comment:
			<input type='text' name='comment' maxlength='40'>
		</div>
		<div class='form-space'></div>
		<input type='submit' name='submit'>
		</form> 
		
		<form method='post'>
			<h3>Remove a Task</h3>
			<div style='display: inline;' title='Multiple tasks can be comma-separated'>
			<input type='text' name='linenum'>
			<button class='button' name='deleteline'>Delete Task</button>
			</div>
		</form>
	<?php } else { echo "<p>Please <a href='login.php'>log in</a>.</p>"; } ?>
</div>

<div class='col two-thirds'>
<h3>Current Tasks</h3>
<?php 
	exec("$DIR/cronedit.sh list",$cronjobsnew);
	
	$tasks = array();	
	for ($i = 0; $i < sizeof($cronjobsnew); $i++) {
		if (strcmp($cronjobsnew[$i],"MAILTO=\"\"") != 0) {
			$tasks[$i] = str_getcsv($cronjobsnew[$i], " ", "\"");
			if (strcmp($tasks[$i][5],"echo")==0) {
			}
			else {
				$tasks[$i][5] .= " " . $tasks[$i][6];
				unset($tasks[$i][6]);
				$tasks[$i] = array_values($tasks[$i]);
			}
		}
	}
	$tasks = array_values($tasks); //restore array indices
?>
	<table class='table'>
	<tr class='table-head'>
	<th>#</th><th>Task</th><th>Time</th><th>DOW</th><th>DOM</th><th>Month</th>
	</tr>
	<?php
		$DIR="/var/www/code";
		for ($i = 0; $i < sizeof($tasks); $i++ ) {
			echo "<tr>";
			echo "<td>" . ($i+1) . "</td><td>";
			//Tasks
			$scripts = array(
				0 => "$DIR/pandora.sh alarm",
				1 => "$DIR/pandora.sh start",
				2 => "$DIR/pandora.sh stop",
				3 => "$DIR/pandora.sh pause",
				4 => "$DIR/pandora.sh vol",
				5 => "$DIR/lights.sh 1",
				6 => "$DIR/lights.sh 2",
				7 => "$DIR/gvcheck.sh",
				8 => "$DIR/cronedit.sh findremove",
			);
			$text = array(
				0 => "Alarm",
				1 => "Start Pandora",
				2 => "Stop Pandora",
				3 => "Pause Song",
				4 => "Set volume to ",
				5 => "Turn light 1 ",
				6 => "Turn light 2 ",
				7 => "Check Google Voice commands",
				8 => "Cron remove " . $tasks[$i][7] . " lines after ",
			);
			$tasks[$i][5] = str_replace($scripts,$text,$tasks[$i][5]);
			$time = true;
			#if task is email
			if (strcmp($tasks[$i][8],"mail")==0) {
				echo "Email (subject: \"" . $tasks[$i][10] ."\")";
			}
			#if task is comment
			else if (strpos($tasks[$i][0],"#") !== false || strcmp($tasks[$i][0],"") == 0) {
				echo "<em>";
				foreach ($tasks[$i] as $item) {
					echo $item . " ";
				}
				echo "</em>";
				$time = false;
			}
			else {
				echo $tasks[$i][5] . " " . $tasks[$i][6];
			}
			echo "</td><td>";
			//Time
			if ($time == true ) {
				if (strcmp($tasks[$i][1],"*")==0 ) {
					echo $tasks[$i][1] . ":" . $tasks[$i][0];
				}
				else if ($tasks[$i][1] > 0 && $tasks[$i][1] < 12 ) {
					echo $tasks[$i][1] . ":" . $tasks[$i][0] . " am";
				}
				else if ($tasks[$i][1] > 12) {
					echo ($tasks[$i][1]-12) . ":" . $tasks[$i][0] . " pm";
				}
				else if ($tasks[$i][1] == 0 ) {
					echo "12:" . $tasks[$i][0] . " am";
				}
				else if ($tasks[$i][1] == 12 ) {
					echo "12:" . $tasks[$i][0] . " pm";
				}
				echo "</td><td>";
				//Day of week
				if (strcmp($tasks[$i][4],"*")==0 ) {
					echo $tasks[$i][4];
				}
				else {
					$num = array("0","1","2","3","4","5","6");
					$days = array("Sun","M","T","W","Th","F","Sat");
					$tasks[$i][4] = str_replace($num,$days,$tasks[$i][4]);
					echo $tasks[$i][4];
				}
				echo "</td><td>";
				//Day of month
				echo $tasks[$i][2];
				echo "</td><td>";
				//Month
				if (strcmp($tasks[$i][3],"*")==0 ) {
					echo $tasks[$i][3];
				}
				else {
					$num = array("1","2","3","4","5","6","7","8","9","10","11","12");
					$months = array("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");
					$tasks[$i][3] = str_replace($num,$months,$tasks[$i][3]);
					echo $tasks[$i][3];
				}
			}
			else {
				echo "</td><td></td><td></td><td>";
			}
			echo "</td></tr>";
		}
	?>
	</table>
	
</div>

<script>
	$("#dd").change(function(){
		var selected= $("#dd option:selected").text();
		$('#Music').hide(); 
		$('#Lighting').hide();
		$('#Email').hide();
		$('#Comment').hide();
		$('#'+ selected).show(); 
		
	});
	
	$("#ddd").change(function(){
		var selected= $("#ddd option:selected").text();
		$('#Volume').hide(); 
		$('#'+ selected).show(); 
		
	});

	$(document).ready(function(){
		$('#Music').hide();
		$('#Lighting').hide();
		$('#Email').hide();
		$('#Comment').hide();
		$('#Volume').hide();
	});
</script>
<?php 
	after_content();
?>
