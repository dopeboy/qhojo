curl -c cookies1.txt -d "email=support@qhojo.com&password=tucrERefApaS7ast" https://qhojo.com/user/signin/null/1;

curl -b cookies1.txt https://qhojo.com/transaction/expired/;

curl -b cookies1.txt https://qhojo.com/user/signout;

rm cookies1.txt;
