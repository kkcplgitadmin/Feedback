#!/bin/sh
# Filename:logbackup
# Author:Sravan Malpani

myfunction(){

for i in $source ; do

	file_count=`find $source -daystart -maxdepth 1 -type f -mtime +$bkpdays |wc -l`
	echo -e "Find command for check is: find $source -daystart -maxdepth 1 -type f -mtime +$bkpdays|wc -l"
	echo -e "file count is:"$file_count
	if [ $file_count = 0 ]
	then 
		echo -e "`date +"%Y-%m-%d %T"` :No files available for zipping...\n"
	else
		while [ $file_count -gt 0 ]
		do
			echo -e "************************************ Logs backup older than "$bkpdays" days started on `date +"%Y-%m-%d %T"` ************************************ \n" 
			new_file_count=`find $source -daystart -maxdepth 1 -type f -mtime $bkpdays|wc -l`
			
			echo -e "`date +"%Y-%m-%d %T"` :Find command executed is: find $source -daystart -maxdepth 1 -type f -mtime $bkpdays |sort \n"
			echo -e "`date +"%Y-%m-%d %T"` :Total Files available for Zipping are:"$new_file_count
			
			datenow=`date --date="-$bkpdays day" +%F`
			if [ $new_file_count = 0 ]
			then
				echo -e "`date +"%Y-%m-%d %T"`:No files available for zipping for " $datenow ",skipping for next date."
				bkpdays=`expr $bkpdays + 1`			
				file_count=`expr $file_count - $new_file_count`

			else  		
				datenow=`date --date="-$bkpdays day" +%F`
				echo -e "`date +"%Y-%m-%d %T"` :Files avaiable for zipping for the date" $datenow "is:" $new_file_count "\n"
				
				find $source -daystart -maxdepth 1 -type f  -mtime $bkpdays|sort
				mkdir -p $destination/backup_$datenow 
				find $source -daystart -maxdepth 1 -type f -mtime $bkpdays -exec mv {}  $destination/backup_$datenow \;
				echo -e "\n`date +"%Y-%m-%d %T"` :Zipping the log files for the date" $datenow  "\n"
				cd $destination
				newzipname=`echo $zipname'_'$datenow.tar.gz`
				tar -cvzf $newzipname backup_$datenow >/dev/null 2>&1
				rm -rf $destination/backup_$datenow
				echo -e "`date +"%Y-%m-%d %T"` :Backup done, saved as $zipname'_'$datenow.tar.gz in path :$destination \n"
				echo -e "************************************* Logs backup older than "$bkpdays" days ended on `date +"%Y-%m-%d %T"` ************************************* \n"		 
				bkpdays=`expr $bkpdays + 1`			
				file_count=`expr $file_count - $new_file_count`

			fi
		done	
	fi
done

}

var=`cat $1`

for i in $var
do
	OLDIFS=$IFS
	IFS=","
	read -a array <<< "$(printf "%s" "$i")"
	IFS=$OLDIFS
	source=${array[0]}
	destination=${array[1]}
	zipname=${array[2]} 
	bkpdays=${array[3]}
	#bkpdays=$2
	
	echo "Source Path:" $source 
	echo "Destination Path:" $destination 	
	echo "Zip Name:" $zipname
	echo "Bkp days:"$bkpdays
	myfunction $source $destination $zipname $bkpdays

done
exit;
