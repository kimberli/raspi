#!/bin/bash
NAME=heyu
OPTIONS=""
USAGE=" * Usage: $NAME [LIGHT#] [on|off]"
PID=$(pidof $NAME)
function light1 {
	if [ "$1" = "on" ] ; then
		heyu fon K1
		echo "Light 1 turned on."
	elif [ "$1" = "off" ] ; then
		heyu foff K1
		echo "Light 1 turned off."
	else
		echo "$USAGE"
	fi
}
function light2 {
	if [ "$1" = "on" ] ; then
		heyu fon K2
		echo "Light 2 turned on."
	elif [ "$1" = "off" ] ; then
		heyu foff K2
		echo "Light 2 turned off."
	else
		echo "$USAGE"
	fi
}
if [ "$1" = "1" ]; then
       light1 $2
elif [ "$1" = "2" ]; then
	light2 $2
else
        echo "$USAGE"
fi
