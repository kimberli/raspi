#!/bin/bash

NAME=heyu
OPTIONS=""
USAGE=" * Usage: ./lights.sh [LIGHT#] [on|off|(null)]"
PID=$(pidof $NAME)
DIR=/usr/local/bin
fold="/var/www/heyu"
file1="$fold/light1"
file2="$fold/light2"

#** light 1 - turns light 1 on, off, or toggle
#param:
#$1 can be on, off, or null (will toggle if null)
function light1 {
	if [ "$1" = "on" ] ; then
		"$DIR"/heyu fon K1
		echo "On" > "$file1"
		echo "Light 1 turned on."
	elif [ "$1" = "off" ] ; then
		"$DIR"/heyu foff K1
		echo "Off" > "$file1"
		echo "Light 1 turned off."
	elif [ "$1" = "" ] ; then
		state=`cat $file1`
		if [ "$state" = "On" ] ; then
			light1 off
		elif [ "$state" = "Off" ] ; then
			light1 on
		fi
	else
		echo "$USAGE"
	fi
}

#** light 2 - turns light 2 on, off, or toggle
#param:
#$1 can be on, off, or null (will toggle if null)
function light2 {
	if [ "$1" = "on" ] ; then
		"$DIR"/heyu fon K2
		echo "On" > "$file2"
		echo "Light 2 turned on."
	elif [ "$1" = "off" ] ; then
		"$DIR"/heyu foff K2
		echo "Off" > "$file2"
		echo "Light 2 turned off."
	elif [ "$1" = "" ] ; then
		state=`cat $file2`
		if [ "$state" = "On" ] ; then
			light2 off
		elif [ "$state" = "Off" ] ; then
			light2 on
		fi
	else
		echo "$USAGE"
	fi
}

#** status - tells state of lights 1 and 2
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
