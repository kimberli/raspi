#!/bin/bash

USAGE=" * Usage: ./cronedit.sh [list|write|remove|findremove] [options]"
IFS=$'\r\n' jobs=($(crontab -l))
file=/home/pi/cron.txt

#param: none
function list {
	if [ "$1" != "" ] ; then
		echo "${jobs[$1]}"
	else
		( IFS=$'\n'; echo "${jobs[*]}" )
	fi
}

#param: 
#$1 is line to write into crontab
#  (multiple lines can be separated by semicolons)
function write {
	( IFS=$'\n'; echo "${jobs[*]}" > $file)
	lines=$(echo $1 | tr ';' '\n')
	( IFS=$'\n'; echo "${lines[*]}" >> $file)
	crontab $file
	rm $file
}

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

#param:
#$1 is string to search for (e.g. "#comment")
#$2 is number of lines to remove (including comment line)
function findremove {
    i=0;
	if [ "$2" -ge 1 ] ; then
		for str in "${jobs[@]}"; do
			if [ "$str" = "$1" ]; then
				echo $i
				for (( c=0; c<$2; c++ ))
					do
						unset jobs[$(($i + $c))]
					done
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
	echo "  [findremove]: find and remove a line (or multiple lines following one line)"
	echo "    first param: line to search for (e.g. '#comment')"
	echo "    second param: number of lines to delete (including comment line)"
fi