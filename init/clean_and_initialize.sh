rm -r ../uploads/*;
cp -r img/* ../uploads/;
mysql -umanish -h localhost -padmin < sql/create.sql;
mysql -umanish -h localhost -padmin < sql/base_data.sql;
mysql -umanish -h localhost -padmin < sql/test_data.sql;
