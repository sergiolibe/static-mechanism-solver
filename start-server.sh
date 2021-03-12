
#export $(cat .env | xargs)

export ptag=pid-static-mechanism-solver-backend;
nohup php -S localhost:8080 -t public $ptag &> /dev/null & #

PHP_SERVER_PID=$!

echo 'PHP SERVER ID:' $PHP_SERVER_PID;
