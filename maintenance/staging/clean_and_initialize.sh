sudo rm -rf ../../uploads/*;
cp -r img/* ../../uploads/;
chmod -R 777 ../../uploads/;
cat ../../sql/create.sql ../../sql/base_data.sql ../../sql/test_data.sql | mysql -uroot -h localhost -p qhojo_staging
