#!/bin/bash 

db="qhojo_staging";
username="qhostaging";
password="m2KIw4QbVt7r71O";

export TZ=":America/New_York"
directory="backups";
timestamp=$(date +"%Y_%m_%d_%H%M%S");
sqlfile="backup_${db}_$(date +"%Y_%m_%d_%H%M%S").sql";
tarfile="${directory}/${db}_$(date +"%Y_%m_%d_%H%M%S").tar.gz";
email="support@qhojo.com";

#BACKUP
mysqldump -u $username -p$password $db > $sqlfile

if [[ $? -ne 0 ]] ; then
	echo "Error code 1" | mutt -s "BACKUP FAILED - ${db} - ${timestamp}" -- $email;
    exit 1;
fi

tar -zcvf $tarfile ../../uploads/ $sqlfile;

if [[ $? -ne 0 ]] ; then
	echo "Error code 2" |  mutt -s "BACKUP FAILED - ${db} - ${timestamp}" -- $email;
    exit 2;
fi

echo "Successfully exported $db to $tarfile";
rm $sqlfile;

echo "" | mutt -s "BACKUP SUCCEEDED - ${db} - ${timestamp}" -a $tarfile -- $email;

exit 0;

