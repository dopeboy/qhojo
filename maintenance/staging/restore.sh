#!/bin/bash 

db="qhojo_staging";
username="restorestaging";
password="abcdefghj";

if [ $# -ne 1 ]
then
  echo "Missing argument. Need a SQL file to restore from"
  exit 1
fi

mysql -u $username -p$password $db < $1
