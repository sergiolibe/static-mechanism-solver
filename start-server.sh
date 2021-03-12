
#export $(cat .env | xargs)

nohup php -S localhost:8080 -t public &> /dev/null &

PHP_SERVER_PID=$!

echo 'PHP SERVER ID:' $PHP_SERVER_PID;
