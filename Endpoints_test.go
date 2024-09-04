package main

import (
	"encoding/json"
	"static_mechanism_solver/src/Core/Math"
	. "static_mechanism_solver/src/Core/Models"
	"static_mechanism_solver/src/Payload"
	"testing"
)

const REQ = `{"system_data":{"nodes":{"n1":{"x":0,"y":0,"type":"U1U2"},"n2":{"x":0,"y":21,"type":"FREE"},"n3":{"x":43,"y":-35,"type":"JOINT"},"n4":{"x":14,"y":14,"type":"U1U2"},"n5":{"x":25,"y":-50,"type":"JOINT"},"n6":{"x":25,"y":-75,"type":"JOINT"},"n7":{"x":5,"y":-95,"type":"FREE"}},"beams":{"b1":{"startNode":"n1","endNode":"n2"},"b2":{"startNode":"n2","endNode":"n3"},"b3":{"startNode":"n1","endNode":"n3"},"b4":{"startNode":"n3","endNode":"n5"},"b5":{"startNode":"n4","endNode":"n5"},"b6":{"startNode":"n5","endNode":"n6"},"b7":{"startNode":"n3","endNode":"n6"},"b8":{"startNode":"n6","endNode":"n7"},"b9":{"startNode":"n3","endNode":"n7"}},"forces":{"f1":{"magnitude":50,"angle":0,"type":"DEFINED","node":"n2"},"fx":{"angle":-30,"type":"UNKNOWN","node":"n7"}}},"action":"solve_system"}`
const RES = `{"list_of_reactions":[{"symbol":"B_b1","referenceId":"b1","type":"BEAM","angle":0,"radAngle":0,"magnitude":65.116,"cos":1,"sin":0},{"symbol":"B_b2","referenceId":"b2","type":"BEAM","angle":0,"radAngle":0,"magnitude":-82.098,"cos":1,"sin":0},{"symbol":"B_b3","referenceId":"b3","type":"BEAM","angle":0,"radAngle":0,"magnitude":67.183,"cos":1,"sin":0},{"symbol":"B_b4","referenceId":"b4","type":"BEAM","angle":0,"radAngle":0,"magnitude":5.941,"cos":1,"sin":0},{"symbol":"B_b5","referenceId":"b5","type":"BEAM","angle":0,"radAngle":0,"magnitude":26.945,"cos":1,"sin":0},{"symbol":"B_b6","referenceId":"b6","type":"BEAM","angle":0,"radAngle":0,"magnitude":30.359,"cos":1,"sin":0},{"symbol":"B_b7","referenceId":"b7","type":"BEAM","angle":0,"radAngle":0,"magnitude":-60.53,"cos":1,"sin":0},{"symbol":"B_b8","referenceId":"b8","type":"BEAM","angle":0,"radAngle":0,"magnitude":-35.128,"cos":1,"sin":0},{"symbol":"B_b9","referenceId":"b9","type":"BEAM","angle":0,"radAngle":0,"magnitude":33.96,"cos":1,"sin":0},{"symbol":"Sx_n1","referenceId":"n1","type":"U1","angle":0,"radAngle":0,"magnitude":-52.105,"cos":1,"sin":0},{"symbol":"Sy_n1","referenceId":"n1","type":"U2","angle":90,"radAngle":1.571,"magnitude":-22.705,"cos":0,"sin":1},{"symbol":"Sx_n4","referenceId":"n4","type":"U1","angle":0,"radAngle":0,"magnitude":-4.564,"cos":1,"sin":0},{"symbol":"Sy_n4","referenceId":"n4","type":"U2","angle":90,"radAngle":1.571,"magnitude":26.556,"cos":0,"sin":1},{"symbol":"F_fx","referenceId":"fx","type":"FORCE","angle":-30,"radAngle":-0.524,"magnitude":7.701,"cos":0.866,"sin":-0.5}],"success":true}`

func TestStaticSystem(t *testing.T) {
	var d Payload.SolveSystemRequest
	err := json.Unmarshal([]byte(REQ), &d)
	if err != nil {
		t.Errorf("Failed parsing the request JSON")
	}
	if d.Action != Payload.ACTION_SOLVE_SYSTEM {
		t.Errorf("Wrong action")
	}
	sd := d.SystemData
	sr := Payload.ConstructSystemRequestFromArray(*sd)

	Ax := sr.GenerateMatrix()
	result := Math.Solve(Ax)
	Math.ScalarMultiply(result, -1)
	Math.RoundVector(result, 3)

	reactions := sr.MapReactionsWithResults(result)

	var rsJSON []ReactionJSON
	for _, r := range reactions {
		rsJSON = append(rsJSON, r.ToReactionJSON())
	}

	res := map[string][]ReactionJSON{"list_of_reactions": rsJSON}

	jsonBytes, err := json.Marshal(res)
	if err != nil {
		t.Errorf("Failed encoding the response as JSON")
	}
	actual := string(jsonBytes)
	if actual != RES {
		t.Errorf("Response is not correct")
	}
}
