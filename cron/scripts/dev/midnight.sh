curl -c cookies2.txt -d "email=support@qhojo.com&password=tucrERefApaS7ast" http://localhost/user/signin/null/1;

curl -b cookies2.txt http://localhost/transaction/remindstart;
curl -b cookies2.txt http://localhost/transaction/remindend;
curl -b cookies2.txt http://localhost/transaction/updatelatecounts;
curl -b cookies2.txt http://localhost/transaction/checkforlate;
curl -b cookies2.txt http://localhost/transaction/expireoldreservations;

curl -b cookies2.txt http://localhost/user/signout;

rm cookies2.txt;
