package Models

import (
	. "static_mechanism_solver/src/Core"
)

type Node struct {
	Beams    []*Beam  `json:"beams,omitempty"`
	Forces   []*Force `json:"forces,omitempty"`
	nodeType NodeType
	Id       string `json:"id,omitempty"`
	symbol   *string
	x        float64
	y        float64
}

func ConstructNode(nodeType NodeType, id string, x float64, y float64) Node {
	n := Node{}
	n.nodeType = nodeType
	n.Id = id
	n.x = x
	n.y = y
	return n
}
func (n Node) getId() string {
	return n.Id
}
func (n Node) GetN() int {
	N := 0
	if n.nodeType == U1U2 {
		N += 2
	} else if n.nodeType == U1 || n.nodeType == U2 {
		N += 1
	}

	for _, f := range n.Forces {
		if f.ForceType == Unknown {
			N += 1
		}
	}

	return N
}

func (n Node) hasU1Symbol() bool {
	return n.nodeType == U1 || n.nodeType == U1U2

}

func (n Node) hasU2Symbol() bool {
	return n.nodeType == U2 || n.nodeType == U1U2
}

func (n Node) GetValuesBySymbolByAxis(axis Axis) map[string]float64 {
	valuesBySymbol := map[string]float64{}

	r := R_Result
	k := 1
	if axis == X {
		k += 1
		for _, b := range n.Beams {
			valuesBySymbol[b.GetSymbol()] = b.GetCosOnNode(n)
		}
		if n.hasU1Symbol() {
			valuesBySymbol[n.GetU1Symbol()] = 1
		}
		for _, f := range n.Forces {
			if f.ForceType == Unknown {
				valuesBySymbol[f.GetSymbol()] = f.GetCos()
			} else if f.ForceType == Defined {
				valuesBySymbol[string(r)] = f.Magnitude * f.GetCos()
			}
		}
	} else if axis == Y {
		k += 1
		for _, b := range n.Beams {
			valuesBySymbol[b.GetSymbol()] = b.GetSinOnNode(n)
		}
		if n.hasU2Symbol() {
			valuesBySymbol[n.GetU2Symbol()] = 1
		}
		for _, f := range n.Forces {
			if f.ForceType == Unknown {
				valuesBySymbol[f.GetSymbol()] = f.GetSin()
			} else if f.ForceType == Defined {
				valuesBySymbol[string(r)] = f.Magnitude * f.GetSin()
			}
		}
	} else {
		panic("Axis " + axis + " not supported")
	}

	return valuesBySymbol
}

// Adders

func (n *Node) AddBeam(b *Beam) { // _todo: maybe replace with n.Beams = append(n.Beams, b)
	n.Beams = append(n.Beams, b)
}

func (n *Node) AddForce(f *Force) { // _todo: maybe replace with n.Forces = append(n.Forces, f)
	n.Forces = append(n.Forces, f)
}

// Getters

func (n Node) GetU1Symbol() string {
	if !n.hasU1Symbol() {
		panic("NodeType doesn't have U1Symbol (" + n.nodeType + ")")
	}
	return "Sx_" + n.Id
}

func (n Node) GetU2Symbol() string {
	if !n.hasU2Symbol() {
		panic("NodeType doesn't have U2Symbol (" + n.nodeType + ")")
	}
	return "Sy_" + n.Id
}

func (n Node) GetBeamsIds() []string {
	ids := make([]string, len(n.Beams))
	for i, b := range n.Beams {
		ids[i] = b.Id
	}
	return ids
}

func (n Node) GetForcesIds() []string {
	ids := make([]string, len(n.Forces))
	for i, f := range n.Forces {
		ids[i] = f.Id
	}
	return ids
}
