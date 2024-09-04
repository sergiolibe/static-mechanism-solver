package main

import (
	"encoding/json"
	"fmt"
	"io"
	"net/http"
	"static_mechanism_solver/src/Core/Math"
	. "static_mechanism_solver/src/Core/Models"
	"static_mechanism_solver/src/Payload"
)

func getRoot(w http.ResponseWriter, r *http.Request) {
	fmt.Printf("got / request\n")
	io.WriteString(w, "This is my website!\n")
}

func StaticSystem(w http.ResponseWriter, r *http.Request) {
	enableCors(&w)
	fmt.Printf("got /static_system.php request: %s\n", r.Method)

	if r.Method == http.MethodOptions {
		http.Error(w, "{\"success\":false,\"errormessage\":\"method OPTIONS not supported\"}", http.StatusOK)
		return
	}

	// Read the body of the request
	body, err := io.ReadAll(r.Body)
	if err != nil {
		http.Error(w, "{\"success\":false,\"errormessage\":\"Unable to read request body\"}", http.StatusOK)
		return
	}
	defer r.Body.Close()

	// Unmarshal the JSON body into the struct
	var d Payload.SolveSystemRequest
	err = json.Unmarshal(body, &d)
	if err != nil {
		//w.Header().Set("Content-Type", "application/json; charset=UTF-8");
		http.Error(w, "{\"success\":false,\"errormessage\":\"Invalid JSON\"}", http.StatusOK)
		return
	}

	// Now you can use the 'data' variable which contains the parsed JSON data
	//log.Printf("Received: %+v", data)

	if d.Action != Payload.ACTION_SOLVE_SYSTEM {
		http.Error(w, "action "+d.Action+" not supported", http.StatusBadRequest) // _todo: replace with ResponseBuilder.error
		return
	}

	sd := d.SystemData

	if sd == nil {
		http.Error(w, "parameter system_data must be present and array_type", http.StatusBadRequest)
		return
	}

	sr := Payload.ConstructSystemRequestFromArray(*sd)

	Ax := sr.GenerateMatrix()
	result := Math.Solve(Ax)
	Math.ScalarMultiply(result, -1)
	Math.RoundVector(result, 3)

	reactions := sr.MapReactionsWithResults(result)

	res := map[string][]Reaction{"list_of_reactions": reactions}

	err = json.NewEncoder(w).Encode(res)
	if err != nil {
		io.WriteString(w, err.Error())
	}
}

func getHello(w http.ResponseWriter, r *http.Request) {
	fmt.Printf("got /hello request\n")

	//n1 := ConstructNode(Joint, "n1", 13.4, 12.3)
	n2 := ConstructNode(U1U2, "n2", 21.4, 2.2)
	//b := ConstructBeam(n1, n2, "b12")
	b2 := Beam{}
	n2.AddBeam(b2)
	//io.WriteString(w, "Hello, HTTP!\n")

	err := json.NewEncoder(w).Encode(n2)
	//io.WriteString(w, fmt.Sprintf("n2.u1symbol:%s", n2.GetU1Symbol()))
	//io.WriteString(w, fmt.Sprintf("cos(n1):%f", b.GetCosOnNode(n1)))
	//io.WriteString(w, fmt.Sprintf("sin(n1):%f", b.GetSinOnNode(n1)))
	//io.WriteString(w, fmt.Sprintf("cos(n2):%f", b.GetCosOnNode(n2)))
	//io.WriteString(w, fmt.Sprintf("sin(n2):%f", b.GetSinOnNode(n2)))
	//io.WriteString(w, fmt.Sprintf("long:%f", b.GetLongitude()))
	if err != nil {
		io.WriteString(w, err.Error())
	}
}
func enableCors(w *http.ResponseWriter) {
	(*w).Header().Set("Access-Control-Allow-Origin", "*")
	(*w).Header().Set("Access-Control-Allow-Methods", "POST,OPTIONS")
	(*w).Header().Set("Access-Control-Allow-Headers", "*")
}

func main() {
	http.HandleFunc("/", getRoot)
	http.HandleFunc("/hello", getHello)
	http.HandleFunc("/static_system.php", StaticSystem)

	_ = http.ListenAndServe(":8080", nil)
}
