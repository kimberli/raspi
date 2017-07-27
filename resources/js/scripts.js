function startTime() {
	today = new Date();
	hour = today.getHours();
	min = today.getMinutes();
	sec = today.getSeconds();
	ampm = hour >= 12 ? 'pm' : 'am';
	hour = hour % 12;
	// add a zero in front of numbers<10
	min = checkTime(min);
	sec = checkTime(sec);
	document.getElementById('time').innerHTML = hour + ':' + min + ':' + sec + ' ' + ampm;
	t = setTimeout(function(){startTime()},500);
}

function checkTime(i) {
	if (i < 10)
	{
	  i ='0' + i;
	}
	return i;
}

var recentChange = false;

function stateInfo() {
	if (recentChange == false) {
		$.ajax({
			url:'../../commands/pandora.php',
			type:'GET',
			data:'action=status',
			success: function(text) {
				var status = text.split('<br>');
				if (status[0] == "pianobar is not running") {
					$('.song-info').html(status[0]);
					$('.stop').hide();
					$('.start').show();
					$('.song-image').hide();
					$('.station-select').hide();
					$('.volume').val("");
				}
				else {
					$('.start').hide();
					$('.stop').show();
					$('.song-info').html('"' + status[4] + '" by ' + status[3] + "<br><em>" + status[8] + "</em>");
					$('.song-image').attr('src',status[7]);
					$('.song-image').show();
					$('.station-select').show();
					$('.volume').val(status[1]);
					if (status[2] == 'playing') {
						$('.play-pause').html('Pause');
					}
					else {
						$('.play-pause').html('Play');
					}
					var options;
					for ( i = 9; i < status.length - 1; i++) {
						options += '<option value="' + String(i-9) + '"';
						if (status[i].substring(3) == status[5]) {
							options += ' selected';
						}
						options += '>' + status[i].substring(3) + '</option>';
					}
					$('.station-select').html(options);
				}
			}
		});
		$.ajax({
			url:'../../commands/lights.php',
			type:'GET',
			data:'action=status',
			success: function(text) {
				var status = text.split('<br>');
				if (status[0]=="Light 1: Off") {
					$('.light1-off').removeClass("active");
					$('.light1-on').addClass("active");
				}
				else {
					$('.light1-on').removeClass("active");
					$('.light1-off').addClass("active");
				}
				if (status[1]=="Light 2: Off") {
					$('.light2-off').removeClass("active");
					$('.light2-on').addClass("active");
				}
				else {
					$('.light2-on').removeClass("active");
					$('.light2-off').addClass("active");
				}
			}
		});
	}
	recentChange = false;
}

stateInfo();
window.setInterval(stateInfo, 5000);

$('.station-select').change(function() {
	$.ajax({
		url:'../../commands/pandora.php',
		type:'GET',
		data:'action=station&num='+$(this).val(),
		cache: false,
		success: function(text) {
			$('.output').html(text);
		},
		error: function() {
			$('.output').text("Failed to execute");
		},
	});
	recentChange = true;
});

$('.start').click(function() {
	$.ajax({
		url:'../../commands/pandora.php',
		type:'GET',
		data:'action=startbg',
		cache: false,
		success: function(text) {
			$('.output').html(text);
		},
		error: function() {
			$('.output').text("Failed to execute");
		},
	});
	recentChange = true;
});

$('.stop').click(function() {
	$.ajax({
		url:'../../commands/pandora.php',
		type:'GET',
		data:'action=stop',
		cache: false,
		success: function(text) {
			$('.output').html(text);
		},
		error: function() {
			$('.output').text("Failed to execute");
		},
	});
});

$('.play-pause').click(function() {
	$.ajax({
		url:'../../commands/pandora.php',
		type:'GET',
		data:'action=pause',
		cache: false,
		success: function(text) {
			$('.output').html(text);
		},
		error: function() {
			$('.output').text("Failed to execute");
		},
	});
	recentChange = true;
});

$('.skip').click(function() {
	$.ajax({
		url:'../../commands/pandora.php',
		type:'GET',
		data:'action=skip',
		cache: false,
		success: function(text) {
			$('.output').html(text);
		},
		error: function() {
			$('.output').text("Failed to execute");
		},
	});
	recentChange = true;
});

$('.volume').change(function() {
	$.ajax({
		url:'../../commands/pandora.php',
		type:'GET',
		data:'action=vol&vol='+$(this).val(),
		cache: false,
		success: function(text) {
			$('.output').html(text);
		},
		error: function() {
			$('.output').text("Failed to execute");
		},
	});
	recentChange = true;
});

$('.like').click(function() {
	$.ajax({
		url:'../../commands/pandora.php',
		type:'GET',
		data:'action=like',
		cache: false,
		success: function(text) {
			$('.output').html(text);
		},
		error: function() {
			$('.output').text("Failed to execute");
		},
	});
	recentChange = true;
});

$('.light-control').click(function() {
	if ($(this).hasClass("light1-on")) {
		$.ajax({
			url:'../../commands/lights.php',
			type:'GET',
			data:'action=1&state=on',
			cache: false,
			success: function(text) {
				$('.output').html(text);
			},
			error: function() {
				$('.output').text("Failed to execute");
			},
		});
	}
	else if ($(this).hasClass("light1-off")) {
		$.ajax({
			url:'../../commands/lights.php',
			type:'GET',
			data:'action=1&state=off',
			cache: false,
			success: function(text) {
				$('.output').html(text);
			},
			error: function() {
				$('.output').text("Failed to execute");
			},
		});
	}
	else if ($(this).hasClass("light2-on")) {
		$.ajax({
			url:'../../commands/lights.php',
			type:'GET',
			data:'action=2&state=on',
			cache: false,
			success: function(text) {
				$('.output').html(text);
			},
			error: function() {
				$('.output').text("Failed to execute");
			},
		});
	}
	else if ($(this).hasClass("light2-off")) {
		$.ajax({
			url:'../../commands/lights.php',
			type:'GET',
			data:'action=2&state=off',
			cache: false,
			success: function(text) {
				$('.output').html(text);
			},
			error: function() {
				$('.output').text("Failed to execute");
			},
		});
	}
	recentChange = true;
});

window.onload = function() { startTime(); $('.loading-mask').remove(); };