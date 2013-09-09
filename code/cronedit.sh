#!/bin/bash

USAGE=" * Usage: ./cronedit.sh [list|write|remove|findremove] [options]"
DIR=/var/www/code
IFS=$'\r\n' jobs=($(crontab -l))
file=/home/pi/cron.txt

#** list - lists all cron jobs for pi user
#param: none
function list {
	if [ "$1" != "" ] ; then
		echo "${jobs[$1]}"
	else
		( IFS=$'\n'; echo "${jobs[*]}" )
	fi
}

#** write - writes line(s) into crontab
#param: 
#$1 is line to write into crontab (multiple lines can be separated by semicolons)
function write {
	( IFS=$'\n'; echo "${jobs[*]}" > $file)
	lines=$(echo $1 | tr ';' '\n')
	( IFS=$'\n'; echo "${lines[*]}" >> $file)
	if (crontab $file) ; then
		echo "Write successful!"
	fi
	rm $file
}

#** remove - removes line(s) from crontab
#param: 
#$1 is index (or comma-separated list of indices) to remove from crontab
function remove {
	nums=$(echo $1 | sed -e 's/, \+/,/g')
	nums=$(echo $nums | tr ',' '\n')
	for i in ${nums[@]}
		do 
			if [ "$i" -gt 1 ] ; then
				unset jobs[$i]
			fi
		done
	( IFS=$'\n'; echo "${jobs[*]}" > $file )
	crontab $file
	rm $file
}

#** writedelay - writes tasks that will occur at a delayed time and schedules to delete them afterward
#param: 
#$1 is comment name (without # sign)
#$2 is script 
#$3 is delay time (in minutes)
function writedelay {
	hour=`date '+%H'`
	min=`date '+%M'`
	lmin=$(($min + $3)) #delayed time
	lhr=$hour
	if [ "$lmin" -ge 60 ] ; then #making sure time is base 60
		lmin=$(($lmin - 60))
		lhr=$(($lhr + 1))
	fi
	lmin2=$(($lmin + 1))
	lhr2=$lhr
	if [ "$lmin2" -ge 60 ] ; then
		lmin2=$(($lmin2 - 60))
		lhr2=$(($lhr2 + 1))
	fi
	if [ "$lmin" -lt 10 ] ; then #prepending a 0 if necessary
		lmin="0$lmin"
	fi
	if [ "$lmin2" -lt 10 ] ; then #prepending a 0 if necessary
		lmin2="0$lmin2"
	fi
	write "#$1;$lmin $lhr * * * $2;$lmin2 $lhr2 * * * $DIR/cronedit.sh findremove '#$1' 3"
}

#** findremove - deletes a specified number of lines after a certain string
#param:
#$1 is string to search for (e.g. "#comment")
#$2 is number of lines to remove (including comment line)
function findremove {
    i=0;
	if [ "$2" -ge 1 ] ; then
		for str in "${jobs[@]}"; do
			if [ "$str" = "$1" ]; then
				echo "Index: $i"
				for (( c=0; c<$2; c++ ))
					do
						unset jobs[$(($i + $c))]
					done
				echo "Deleted $2 line(s)"
				( IFS=$'\n'; echo "${jobs[*]}" > $file )
				crontab $file
				rm $file
				return
			else
				((i++))
			fi
		done
	fi
    echo "-1"
}

if [ "$1" = "list" ]; then
	list $2
elif [ "$1" = "write" ]; then
	write $2
elif [ "$1" = "remove" ]; then
	remove $2
elif [ "$1" = "writedelay" ]; then
	writedelay $2 $3 $4
elif [ "$1" = "findremove" ]; then
	findremove $2 $3
else
	echo "$USAGE"
	echo "List and edit crontab (of the pi user)"
	echo ""
	echo "  [list]: list all cron jobs of pi user"
	echo "  [write]: write cron job(s) to pi user's crontab"
	echo "    first param: line(s) to write in quotation marks (multiple lines can be separated by semicolons"
	echo "  [remove]: remove cron job(s) from pi user's crontab"
	echo "    first param: comma-separated list of indices"
	echo "  [writedelay]: write three cron lines with times delayed from the current time"
	echo "    first param: comment line (which findremove will search for later)"
	echo "    second param: script to be executed at delayed time"
	echo "    third param: delay time (in minutes)"
	echo "  [findremove]: find and remove a line (or multiple lines following one line)"
	echo "    first param: line to search for (e.g. '#comment')"
	echo "    second param: number of lines to delete (including comment line)"
fi