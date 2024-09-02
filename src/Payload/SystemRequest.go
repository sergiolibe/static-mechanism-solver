package Payload

import . "static_mechanism_solver/src/Core/Models"

type SystemRequest struct {
	nodes                 []Node
	beams                 []Beam
	forces                []Force
	reactions             []Reaction
	referenceSymbolMatrix [][]int
}

func (s *SystemRequest) AddNode(n Node) { // _todo: maybe replace with n.Beams = append(n.Beams, b)
	s.nodes = append(s.nodes, n)
}

func (s *SystemRequest) AddBeam(b Beam) { // _todo: maybe replace with n.Beams = append(n.Beams, b)
	s.beams = append(s.beams, b)
}
func (s *SystemRequest) AddForce(f Force) { // _todo: maybe replace with n.Beams = append(n.Beams, b)
	s.forces = append(s.forces, f)
}

// _todo: remove this
func (s *SystemRequest) AddReaction(r Reaction) {
	s.reactions = append(s.reactions, r)
}

// _todo: remove this
func (s SystemRequest) PrintReactions() string {
	text := " "
	for _, r := range s.reactions {
		text += r.Print()
	}
	return text
}

// func (s SystemRequest) generateMatrix // _todo: implement
// func (s SystemRequest) buildReferenceSymbolMatrix // _todo: implement
func (s SystemRequest) calcN() int {
	n := 0
	for _, node := range s.nodes {
		n += node.GetN()
	}
	return n
}

// func (s SystemRequest) getReferenceSymbolMatrix
func (s SystemRequest) MapReactionsWithResults(result []float64) []Reaction {
	var rs []Reaction
	for _, r := range s.reactions {
		if r.GetType() != R_Result {
			rs = append(rs, r)
		}
	}
	for i := range rs {
		rs[i].SetMagnitude(result[i])
	}

	return rs
}
