package Payload

import (
	. "static_mechanism_solver/src/Core"
	. "static_mechanism_solver/src/Core/Math"
	. "static_mechanism_solver/src/Core/Models"
)

type SystemRequest struct {
	nodes                 map[string]Node
	beams                 map[string]Beam
	forces                map[string]Force
	reactions             []Reaction
	referenceSymbolMatrix map[string]int
}

func ConstructSystemRequestFromArray(data SystemData) SystemRequest {
	s := SystemRequest{}

	return s
}

func (s *SystemRequest) AddNode(n Node) { // _todo: maybe replace with n.Beams = append(n.Beams, b)
	if s.nodes == nil {
		s.nodes = map[string]Node{}
	}
	s.nodes[n.Id] = n
}

func (s *SystemRequest) AddBeam(b Beam) { // _todo: maybe replace with n.Beams = append(n.Beams, b)
	if s.beams == nil {
		s.beams = map[string]Beam{}
	}
	s.beams[b.Id] = b
}
func (s *SystemRequest) AddForce(f Force) { // _todo: maybe replace with n.Beams = append(n.Beams, b)
	if s.forces == nil {
		s.forces = map[string]Force{}
	}
	s.forces[f.Id] = f
}

// _todo: remove this
func (s *SystemRequest) addReaction(r Reaction) {
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

func (s *SystemRequest) GenerateMatrix() [][]float64 {
	//n := s.calcN()
	s.buildReferenceSymbolMatrix()
	c := len(s.referenceSymbolMatrix)
	Ax := ZerosMatrix[float64](c-1, c)
	i := 0
	for _, node := range s.nodes {
		// X for node
		valuesBySymbol := node.GetValuesBySymbolByAxis(X)
		for symbol, value := range valuesBySymbol {
			Ax[i][s.referenceSymbolMatrix[symbol]] = value
		}

		// Y for node
		valuesBySymbol = node.GetValuesBySymbolByAxis(Y)
		for symbol, value := range valuesBySymbol {
			Ax[i+1][s.referenceSymbolMatrix[symbol]] = value
		}
		i += 2
	}
	return Ax
}
func (s *SystemRequest) buildReferenceSymbolMatrix() {
	// build $reactions array from nodes, beams and incognito force
	// then build referenceSymbolMatrix from this array
	if len(s.reactions) == 0 {
		for _, b := range s.beams {
			s.reactions = append(s.reactions, ConstructFromBeam(b))
		}
		for _, n := range s.nodes {
			for _, r := range ConstructFromNode(n) {
				s.reactions = append(s.reactions, r)
			}
		}
		for _, f := range s.forces {
			s.reactions = append(s.reactions, ConstructFromForce(f))
		}
		s.reactions = append(s.reactions, ResultReaction())
	}
	for i, r := range s.reactions {
		s.referenceSymbolMatrix[r.GetSymbol()] = i
	}
}
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
