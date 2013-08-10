#!/bin/bash

VOLUME=`cat /var/www/pianobar/volume`

if [ "$VOLUME" -lt 10 ]
then
	sleep 2
	/var/www/code/pandora.sh volup
	exec /var/www/code/alarm.sh
fi
