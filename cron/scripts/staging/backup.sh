#!/bin/bash 

db="qhojo_staging";
username="stagingroot";
password="zarava6ruThe";

export TZ=":America/New_York"
directory="/home/staging/public/backup";
timestamp=$(date +"%Y_%m_%d_%H%M%S");
sqlfile="backup_${db}_$(date +"%Y_%m_%d_%H%M%S").sql";
tarfile="${directory}/${db}_$(date +"%Y_%m_%d_%H%M%S").tar.gz";
email="support@qhojo.com";

#BACKUP (don't know why these won't take the variables defined above. Hardcoded for now)
mysqldump --single-transaction -ustagingroot -p'zarava6ruThe' $db > $sqlfile

if [ $? -ne 0 ] ; then
	echo "Error code 1" | mutt -s "BACKUP FAILED - ${db} - ${timestamp}" -- $email;
    exit 1;
fi

# Needed because the user producing this SQL is different than the one that will use it for a restore
sed -i '/^\/\*\!50013 DEFINER/d' $sqlfile

tar -zcvf $tarfile /home/staging/public/qhojo/public_html/uploads/ $sqlfile;

if [ $? -ne 0 ] ; then
	echo "Error code 2" |  mutt -s "BACKUP FAILED - ${db} - ${timestamp}" -- $email;
    exit 2;
fi

echo "Successfully exported $db to $tarfile";
rm $sqlfile;

echo "" | mutt -s "BACKUP SUCCEEDED - ${db} - ${timestamp}" -a $tarfile -- $email;

exit 0;

