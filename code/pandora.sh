#!/bin/bash

#
#/etc/init.d/pianobar
#

NAME=pianobar
OPTIONS=""
PID=$(pidof $NAME)
USAGE=" * Usage: ./pandora.sh [start|status|stop|pause|skip|volup|voldown]"
VOLUME=`cat /var/www/pianobar/volume`
STATE=`cat /var/www/pianobar/state`
DIR=/usr/local/bin
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
		echo "play" > "$statefile"
		"$DIR"/pianobar
		echo "  Done."
	else
		echo "$NAME is already running as $PID."
	fi
}

function startbg {
	if [ "$PID" = "" ] ; then
		echo "Starting $NAME..."
		echo "0" > "$volumefile"
		echo "play" > "$statefile"
		"$DIR"/pianobar > /dev/null 2>&1 &
		echo "Please wait..."
	else
		echo "$NAME is already running as $PID."
	fi
}

function alarm {
	startbg
	sleep 20
	for i in {1..10}
		do 
			VOLUME=`cat /var/www/pianobar/volume`
			echo ')' > "$ctlf"
			echo `expr $VOLUME + 1` > "$volumefile"
			echo "Volume increased."
			sleep 2
		done
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
			echo "" > "$nowplaying"
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
		echo "Song skipped."
		echo "Please wait..."
	fi
}

function like {
	if [ "$PID" != "" ] ; then
		echo '+' > "$ctlf"
		echo "Song liked."
	fi
}

function vol {
	VOLUME=`cat /var/www/pianobar/volume`
	if [ "$1" -lt "$VOLUME" ] ; then
		voldown
		vol $1
	elif [ "$1" -gt "$VOLUME" ] ; then
		volup
		vol $1
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
		echo "$VOLUME"
		if [ "$STATE" = "play" ]; then
			echo "playing"
		else
			echo "paused"
		fi
		cat "$nowplaying"
	fi
}

if [ "$1" = "start" ]; then
	start
elif [ "$1" = "startbg" ]; then
	startbg
elif [ "$1" = "alarm" ]; then
	alarm
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
elif [ "$1" = "vol" ]; then
	vol $2
elif [ "$1" = "volup" ]; then
	volup
elif [ "$1" = "voldown" ]; then
	voldown
else
	echo "$USAGE";
fi
