package main

import (
	"encoding/json"
	"fmt"
	"io"
	"net/http"
	. "static_mechanism_solver/src/Core/Models"
)

type Beam struct {
	ForceType ForceType `json:"type"`
}

func getRoot(w http.ResponseWriter, r *http.Request) {
	fmt.Printf("got / request\n")
	io.WriteString(w, "This is my website!\n")
}
func getHello(w http.ResponseWriter, r *http.Request) {
	fmt.Printf("got /hello request\n")

	b := Beam{}
	b.ForceType = Defined
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
