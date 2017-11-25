#!/bin/bash
mkdir -p /logs/systemutils/logsbackup

/bin/bash /opt/systemutils/logbackup/logbackup.sh /opt/systemutils/logbackup/config.cfg >> /logs/systemutils/logsbackup/logsbackup_`date +"%d-%m-%Y"`.log  &

cd /logs/systemlogs

find /logs/systemutils -daystart  -mtime +7 -exec rm {} \;
