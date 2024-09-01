package main

import (
	"encoding/json"
	"fmt"
	"io"
	"net/http"
	. "static_mechanism_solver/src/Core/Models"
)

type Beamx struct {
	ForceType ForceType `json:"type"`
}

func getRoot(w http.ResponseWriter, r *http.Request) {
	fmt.Printf("got / request\n")
	io.WriteString(w, "This is my website!\n")
}
func getHello(w http.ResponseWriter, r *http.Request) {
	fmt.Printf("got /hello request\n")

	n1 := ConstructNode(Joint, "n1", 13.4, 12.3)
	n2 := ConstructNode(Joint, "n2", 21.4, 2.2)
	b := ConstructBeam(n1, n2, "b12")
	//io.WriteString(w, "Hello, HTTP!\n")
	err := json.NewEncoder(w).Encode(b)
	if err != nil {
		io.WriteString(w, err.Error())
	}
}

func main() {
	http.HandleFunc("/", getRoot)
	http.HandleFunc("/hello", getHello)

	_ = http.ListenAndServe(":3333", nil)
}
