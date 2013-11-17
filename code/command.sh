#!/bin/bash

USAGE="./command.sh [bed|home]"
DIR=/var/www/code

month=`date '+%m'`
date=`date '+%d'`
hour=`date '+%H'`
min=`date '+%M'`
UTChour=`date -u '+%H'`
sunfile=/home/pi/sun.txt

lat=38.8977 #your latitude
long=-77.0366 #your longitude
awakehrstart=4 #earliest awake time
awakehrend=13 #latest awake time
awakelightdelay=10 #delay from awake until light off
awakemusicdelay=40 #delay from awake until music off
bedhrstart=20 #bedtime start hour
bedhrend=3 #bedtime end hour
bedlightdelay=5 #delay from bedtime until light off
bedmusicdelay=30 #delay from bedtime until music off
	
#** bed - tapping the NFC tag by the bed
function bed {
	if [[ "$hour" -ge "$awakehrstart" && "$hour" -le "$awakehrend" ]] ; then #awaketime
		if ($DIR/pandora.sh vol 0 > /dev/null 2>&1 &) ; then
			echo "Set volume to 0"
		else
			echo "Failed to set volume to 0"
		fi
		if ($DIR/cronedit.sh writedelay 'awakelight2off' '/var/www/code/lights.sh 2 off' $awakelightdelay > /dev/null 2>&1 &) ; then
			echo "Turning off light 2 in $awakelightdelay minutes"
		else
			echo "Failed to write light 2 off in $awakelightdelay minutes"
		fi
		if ($DIR/cronedit.sh writedelay 'awakepandoraoff' '/var/www/code/pandora.sh stop' $awakemusicdelay > /dev/null 2>&1 &) ; then
			echo "Turning off music in $awakemusicdelay minutes"
		else
			echo "Failed to write music off in $awakemusicdelay minutes"
		fi
	elif [[ "$hour" -ge "$bedhrstart" || "$hour" -le "$bedhrend" ]] ; then #bedtime
		if ($DIR/lights.sh 2 on > /dev/null 2>&1 &) ; then
			echo "Turned light 2 on"
		else
			echo "Failed to turn light 2 on"
		fi
		if ($DIR/lights.sh 1 off > /dev/null 2>&1 &) ; then
			echo "Turned light 1 off"
		else
			echo "Failed to turn light 1 off"
		fi
		if ($DIR/pandora.sh vol -5 > /dev/null 2>&1 &) ; then
			echo "Set volume to -5"
		else
			echo "Failed to set volume to -5"
		fi
		if ($DIR/cronedit.sh writedelay 'bedlight2off' '/var/www/code/lights.sh 2 off' $bedlightdelay > /dev/null 2>&1 &) ; then
			echo "Turning off light 2 in $bedlightdelay minutes"
		else 
			echo "Failed to write light 2 off in $bedlightdelay minutes"
		fi
		if ($DIR/cronedit.sh writedelay 'bedpandoraoff' '/var/www/code/pandora.sh stop' $bedmusicdelay > /dev/null 2>&1 &) ; then
			echo "Turning off music in $bedmusicdelay minutes"
		else 
			echo "Failed to write music off in $bedmusicdelay minutes"
		fi
	else
		echo "What are you getting in bed for???"
	fi
}

#** enter - tapping the NFC tag when entering the house
function enter {
	sun #function getting sunrise/set times
	if ($DIR/pandora.sh start > /dev/null 2>&1 &) ; then
			echo "Turned music on"
		else
			echo "Failed to turn music on"
		fi
	if [[ "$hour" -le "$risehr" || ( "$hour" -eq "$risehr" && "$min" -le "$risemin" ) ]] ; then
		if ($DIR/lights.sh 1 on > /dev/null 2>&1 &) ; then
			echo "Turned light 1 on"
		else
			echo "Failed to turn light 1 on"
		fi
		echo "(Before sunrise)"
	elif [[ "$hour" -gt "$sethr" || ( "$hour" -eq "$sethr" && "$min" -ge "$setmin" ) ]] ; then
		if ($DIR/lights.sh 1 on > /dev/null 2>&1 &) ; then
			echo "Turned light 1 on"
		else
			echo "Failed to turn light 1 on"
		fi
		echo "(After sunset)"
	else
		setmin2=$(($setmin + 1)) #finding time 1 minute after sunset
		sethr2=$sethr
		if [ "$setmin2" -ge 60 ] ; then
			setmin2=$(($setmin2 - 60))
			sethr2=$(($sethr2 + 1))
		fi
		if [ "$setmin" -lt 10 ] ; then #prepending a 0 if necessary
			setmin="0$setmin"
		fi
		if [ "$setmin2" -lt 10 ] ; then #prepending a 0 if necessary
			setmin2="0$setmin2"
		fi
		if ($DIR/cronedit.sh write "#light1enter;$setmin $sethr * * * $DIR/lights.sh 1 on;$(($setmin2)) $sethr2 * * * $DIR/cronedit.sh findremove '#light1enter' 3") ; then
			echo "Turning light 1 on at $sethr:$setmin"
		else
			echo "Failed to write light 1 on at $sethr:$setmin"
		fi
		echo "(Daytime)"
	fi
}

#** leave - tapping the NFC tag when leaving the house
function leave {
	if ($DIR/lights.sh 1 off > /dev/null 2>&1 &) ; then
		echo "Turned light 1 off"
	else
		echo "Failed to turn light 1 off"
	fi
	if ($DIR/lights.sh 2 off > /dev/null 2>&1 &) ; then 
		echo "Turned light 2 off"
	else
		echo "Failed to turn light 2 off"
	fi
	if ($DIR/pandora.sh stop > /dev/null 2>&1 &) ; then
		echo "Turned music off"
	else
		echo "Failed to turn music off"
	fi
	if ($DIR/cronedit.sh findremove "#light1enter" 3 > /dev/null 2>&1 &) ; then
		echo "Removed light 1 on task"
	else
		echo "Failed to remove light 1 on task"
	fi
}

#** sun - sets the sunrise/set variables to the appropriate time, as pulled from earthtools.org
function sun {
	#echo "Current time: $hour:$min"
	if [ "$UTChour" -gt "$hour" ] ; then 
		diff=$(($hour - $UTChour))
	else
		UTChour=$(($UTChour + 24))
		diff=$(($hour - $UTChour))
	fi
	wget http://www.earthtools.org/sun/$lat/$long/$date/$month/$diff/0 -O $sunfile  > /dev/null 2>&1 &
	sleep 1
	risetime=$(grep -m 1 "<sunrise>" $sunfile | sed 's/<.*>\(.*\)<\/.*>/\1/')
	risehr=${risetime:4:2}
	risemin=${risetime:7:2}
	risemin=`echo $risemin|sed 's/^0*//'`
	echo "Sunrise: $risehr:$risemin"
	sleep 0.75
	settime=$(grep -m 1 "<sunset>" $sunfile | sed 's/<.*>\(.*\)<\/.*>/\1/')
	sethr=${settime:4:2}
	setmin=${settime:7:2}
	setmin=${setmin#0}
	if [ "$setmin" -ge 45 ] ; then #turning lights on 45 min before sunset
		setmin=$(($setmin - 45))
	else
		sethr=$(($sethr - 1))
		setmin=$(($setmin + 15))
	fi
	if [ "$setmin" -lt 10 ] ; then #prepending a 0 if necessary
		setmin="0$setmin"
	fi
	echo "Sunset: $sethr:$setmin"
	rm $sunfile
}

if [ "$1" = "bed" ] ; then
	bed
elif [ "$1" = "enter" ] ; then
	enter
elif [ "$1" = "leave" ] ; then
	leave
elif [ "$1" = "sun" ] ; then
	sun
else 
	echo "$USAGE"
fi
