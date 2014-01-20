function startTime()
{
	today = new Date();
	hour = today.getHours();
	min = today.getMinutes();
	sec = today.getSeconds();
	ampm = hour >= 12 ? 'pm' : 'am';
	hour = hour % 12;
	// add a zero in front of numbers<10
	min=checkTime(min);
	sec=checkTime(sec);
	document.getElementById('time').innerHTML=hour+':'+min+':'+sec+' '+ampm;
	t=setTimeout(function(){startTime()},500);
}

function checkTime(i)
{
if (i<10)
  {
  i='0' + i;
  }
return i;
}