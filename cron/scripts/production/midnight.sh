curl -c cookies2.txt -d "email=support@qhojo.com&password=tucrERefApaS7ast" https://qhojo.com/user/signin/null/1;

curl -b cookies2.txt https://qhojo.com/transaction/remindstart;
curl -b cookies2.txt https://qhojo.com/transaction/remindend;
curl -b cookies2.txt https://qhojo.com/transaction/updatelatecounts;
curl -b cookies2.txt https://qhojo.com/transaction/checkforlate;
curl -b cookies2.txt https://qhojo.com/transaction/expireoldreservations;

curl -b cookies2.txt https://qhojo.com/user/signout;

rm cookies2.txt;
