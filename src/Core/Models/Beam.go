package Models

import "math"

type Beam struct {
	StartNode Node     `json:"start_node"`
	EndNode   Node     `json:"end_node"`
	Id        string   `json:"id,omitempty"`
	Symbol    *string  `json:"symbol,omitempty"`
	Longitude *float64 `json:"longitude,omitempty"`
}

func ConstructBeam(startNode Node, endNode Node, id string) Beam {
	b := Beam{}
	b.StartNode = startNode
	b.EndNode = endNode
	b.Id = id
	return b
}
func (b Beam) getId() string {
	return b.Id
}
func (b Beam) getStartNode() Node {
	return b.StartNode
}
func (b Beam) getEndNode() Node {
	return b.EndNode
}

func (b Beam) GetSymbol() string {
	if b.Symbol == nil { // _todo: cache this
		return "B_" + b.Id
	}

	return *b.Symbol
}

func (b Beam) GetCosOnNode(n Node) float64 {
	if b.StartNode.Id == n.Id {
		dX := b.EndNode.x - b.StartNode.x
		return dX / b.GetLongitude()
	}

	if b.EndNode.Id == n.Id {
		dX := b.StartNode.x - b.EndNode.x
		return dX / b.GetLongitude()
	}

	panic("Node [" + n.getId() + "] is not the start or end of this beam (" + b.Id + ")")
}
func (b Beam) GetSinOnNode(n Node) float64 {
	if b.StartNode.Id == n.Id {
		dY := b.EndNode.y - b.StartNode.y
		return dY / b.GetLongitude()
	}

	if b.EndNode.Id == n.Id {
		dY := b.StartNode.y - b.EndNode.y
		return dY / b.GetLongitude()
	}

	panic("Node [" + n.getId() + "] is not the start or end of this beam (" + b.Id + ")")
}
func (b Beam) GetLongitude() float64 {
	dX := b.EndNode.x - b.StartNode.x
	dY := b.EndNode.y - b.StartNode.y
	return math.Sqrt(dX*dX + dY*dY)
}

// _todo: remove if not needed
//func stringPtr(s string) *string {
//	return &s
//}
