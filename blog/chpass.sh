query="update wp_users set user_pass=MD5('$__ADMINPASSWORD__') where user_login='admin' limit 1";
echo ${query} | mysql -u$__DBUSERNAME__ -p$__DBPASSWORD__ -hmysqlhost $__DBNAME__
