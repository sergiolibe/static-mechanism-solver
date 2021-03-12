export ptag=pid-static-mechanism-solver-backend;
kill $(ps aux | grep -E "[-]- $ptag" | awk '{print $2}')
