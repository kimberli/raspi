#!/bin/bash

#
#/etc/init.d/pianobar
#

NAME=pianobar
OPTIONS=""
PID=$(pidof $NAME)
VOLUME=`cat /var/www/pianobar/volume`
STATE=`cat /var/www/pianobar/state`
fold="/var/www/pianobar"
stl="$fold/stationlist"
ctlf="$fold/ctl"
nowplaying="$fold/nowplaying"
volumefile="$fold/volume"
statefile="$fold/state"

function start {
        if [ "$PID" = "" ] ; then
		echo "Starting $NAME..."
		echo "0" > "$volumefile"
		pianobar
		echo "  Done."
	else
		echo "$NAME is already running as $PID."
	fi
}
function startbg {
	if [ "$PID" = "" ] ; then
		echo "Starting $NAME..."
		echo "0" > "$volumefile"
		nohup pianobar 2>&1 &
		echo "  Done."
	else
		echo "$NAME is already running as $PID."
	fi
}

function stop {
	if [ "$PID" != "" ] ; then
		echo "Stopping $NAME..."
		kill $PID
		TIMEOUT=30
		START=$( date +%s)
		while [ $(( $( date +%s) - ${START} )) -lt ${TIMEOUT} ]; do
			PID=$(pidof $NAME)
			if [ "$PID" == "" ]; then break
			else sleep 2
			fi
		done
 		if [ "$PID" != "" ] ; then
			echo "Error: $NAME would not stop"
		else
			echo "None" > "$nowplaying"
			echo "  Done."
		fi
	else
		echo "$NAME is not running."
	fi
}

function pause {
	if [ "$PID" != "" ] ; then
		echo 'p' > "$ctlf"
		if [ "$STATE" = "play" ] ; then
			echo "pause" > "$statefile"
			echo "Music paused."
		elif [ "$STATE" = "pause" ] ; then
			echo "play" > "$statefile"
			echo "Music resumed."
		fi
	fi
}

function skip {
	if [ "$PID" != "" ] ; then
		echo 'n' > "$ctlf"
		echo "Song skipped.<br>Please wait..."
	fi
}

function like {
	if [ "$PID" != "" ] ; then
		echo '+' > "$ctlf"
		echo "Song liked."
	fi
}

function volup {
	if [ "$PID" != "" ] ; then
		echo ')' > "$ctlf"
		echo `expr $VOLUME + 1` > "$volumefile"
		echo "Volume increased."
	fi
}

function voldown {
	if [ "$PID" != "" ] ; then
		echo '(' > "$ctlf"
		echo `expr $VOLUME - 1` > "$volumefile"
		echo "Volume decreased."
	fi
}

function status {
	if [ "$PID" = "" ]; then
		echo "$NAME is not running"
	else
		echo "$NAME is running with pid $PID"
		echo "Current Volume: $VOLUME"
		cat "$nowplaying"
	fi
}

if [ "$1" = "start" ]; then
	start
elif [ "$1" = "startbg" ]; then
	startbg
elif [ "$1" = "stop" ]; then
	stop
elif [ "$1" = "status" ]; then
	status
elif [ "$1" = "pause" ]; then
	pause
elif [ "$1" = "skip" ]; then
	skip
elif [ "$1" = "like" ]; then
	like
elif [ "$1" = "volup" ]; then
	volup
elif [ "$1" = "voldown" ]; then
	voldown
else
	echo " * Usage: ./pandora.sh [start|status|stop|pause|skip|volup|voldown]";
fi
