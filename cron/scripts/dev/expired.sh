curl -c cookies1.txt -d "email=support@qhojo.com&password=tucrERefApaS7ast" http://localhost/user/signin/null/1;

curl -b cookies1.txt http://localhost/transaction/expired/;

curl -b cookies1.txt http://localhost/user/signout;

rm cookies1.txt;
