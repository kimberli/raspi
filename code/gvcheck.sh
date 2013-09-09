#!/bin/bash

NAME=gtextcommand
DIR=/usr/bin

#checks google voice approx. every 4-6 seconds (every minute, as set in the Pi user's crontab)
for i in {1..12}
	do
		$DIR/gtextcommand
	done
