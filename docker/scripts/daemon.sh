# /bin/bash

echo "Starting intruder alert daemon..."

nextRun=$(bc <<< "`date '+%s'` - 600")

while true
do
    now=`date '+%s'`
	dif=$(bc <<< "$now - $nextRun")

	if [ $dif -ge 600 ]; then
		php cron.php
		if [ $? -ne 0 ]; then
			exit 1
		fi

		nextRun=$(date '+%s')
	fi

	sleep 2
done
