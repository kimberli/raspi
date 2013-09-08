#!/bin/bash

NAME=heyu
OPTIONS=""
USAGE=" * Usage: ./lights.sh [LIGHT#] [on|off]"
PID=$(pidof $NAME)
DIR=/usr/local/bin
fold="/var/www/heyu"
file1="$fold/light1"
file2="$fold/light2"
function light1 {
	if [ "$1" = "on" ] ; then
		"$DIR"/heyu fon K1
		echo "On" > "$file1"
		echo "Light 1 turned on."
	elif [ "$1" = "off" ] ; then
		"$DIR"/heyu foff K1
		echo "Off" > "$file1"
		echo "Light 1 turned off."
	else
		echo "$USAGE"
	fi
}
function light2 {
	if [ "$1" = "on" ] ; then
		"$DIR"/heyu fon K2
		echo "On" > "$file2"
		echo "Light 2 turned on."
	elif [ "$1" = "off" ] ; then
		"$DIR"/heyu foff K2
		echo "Off" > "$file2"
		echo "Light 2 turned off."
	else
		echo "$USAGE"
	fi
}
function status {
	echo "Light 1: `cat $file1`"
	echo "Light 2: `cat $file2`"
}

if [ "$1" = "1" ]; then
       light1 $2
elif [ "$1" = "2" ]; then
	light2 $2
elif [ "$1" = "status" ]; then 
	status
else
        echo "$USAGE"
fi
