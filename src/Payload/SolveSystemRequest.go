package Payload

const ACTION_SOLVE_SYSTEM = "solve_system"

type SolveSystemRequest struct {
	SystemData *SystemData `json:"system_data,omitempty"`
	Action     string      `json:"action,omitempty"`
}

type SystemData struct {
	Forces map[string]force `json:"forces,omitempty"`
	Nodes  map[string]node  `json:"nodes,omitempty"`
	Beams  map[string]beam  `json:"beams,omitempty"`
}

type beam struct {
	StartNode string `json:"start_node,omitempty"`
	EndNode   string `json:"end_node,omitempty"`
}

type node struct {
	X    int    `json:"x,omitempty"`
	Y    int    `json:"y,omitempty"`
	Type string `json:"type,omitempty"`
}

type force struct {
	Angle     float64  `json:"angle,omitempty"`
	Type      string   `json:"type,omitempty"`
	Node      string   `json:"node,omitempty"`
	Magnitude *float64 `json:"magnitude,omitempty"`
}
