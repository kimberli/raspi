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
			type:'POST',
			data:'action=status',
			success: function(text) {
				var status = text.split('<br>');
				if (status[0] == "pianobar is not running") {
					$('.song-info').html(status[0]);
					$('.stop').hide();
					$('.start').show();
				}
				else {
					$('.start').hide();
					$('.stop').show();
					$('.song-info').html('"' + status[4] + '" by ' + status[3] + "<br><em>" + status[8] + "</em>");
					$('.song-image').attr('src',status[7]);
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
	}
	recentChange = false;
}

stateInfo();
window.setInterval(stateInfo, 5000);

$('.station-select').change(function() {
	$.ajax({
		url:'../../commands/pandora.php',
		type:'POST',
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
		type:'POST',
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
		type:'POST',
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
		type:'POST',
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
		type:'POST',
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
		type:'POST',
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

$('.like').change(function() {
	$.ajax({
		url:'../../commands/pandora.php',
		type:'POST',
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

window.onload = function() { $('.loading-mask').remove(); };