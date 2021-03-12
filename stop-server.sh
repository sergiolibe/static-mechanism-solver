export ptag=pid-static-mechanism-solver-backend;
kill $(ps aux | grep -E "[c] $ptag" | awk '{print $2}')
