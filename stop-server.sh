export ptag=pid-static-mechanism-solver-backend;
kill $(ps aux | grep -E "[l]ocalhost:8080 -t public" | awk '{print $2}')
