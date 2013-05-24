#!/bin/bash 

db="qhojo_production";
username="backupuser";
password="3W16aba38yP2fxr";

export TZ=":America/New_York"
directory="backups";
timestamp=$(date +"%Y_%m_%d_%H%M%S");
sqlfile="backup_${db}_$(date +"%Y_%m_%d_%H%M%S").sql";
tarfile="${directory}/${db}_$(date +"%Y_%m_%d_%H%M%S").tar.gz";
email="support@qhojo.com";

#BACKUP
mysqldump --single-transaction -u $username -p$password $db > $sqlfile

if [[ $? -ne 0 ]] ; then
	echo "Error code 1" | mutt -s "BACKUP FAILED - ${db} - ${timestamp}" -- $email;
    exit 1;
fi

# Needed because the user producing this SQL is different than the one that will use it for a restore
sed -i '/^\/\*\!50013 DEFINER/d' $sqlfile

tar -zcvf $tarfile ../../uploads/ $sqlfile;

if [[ $? -ne 0 ]] ; then
	echo "Error code 2" |  mutt -s "BACKUP FAILED - ${db} - ${timestamp}" -- $email;
    exit 2;
fi

echo "Successfully exported $db to $tarfile";
rm $sqlfile;

echo "" | mutt -s "BACKUP SUCCEEDED - ${db} - ${timestamp}" -a $tarfile -- $email;

exit 0;

