#!/bin/bash
NAME=heyu
OPTIONS=""
USAGE=" * Usage: $NAME [LIGHT#] [on|off]"
PID=$(pidof $NAME)
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
if [ "$1" = "2" ]; then
        light2 $2
else
        echo "$USAGE"
fi
