sudo rm -rf ../uploads/*;
cp -r img/* ../uploads/;
chmod -R 777 ../uploads/;
cat sql/create.sql sql/base_data.sql sql/test_data.sql | mysql -uqho -h localhost -p $1
