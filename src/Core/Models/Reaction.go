package Models

import (
	"fmt"
	"math"
)

// _todo: make props public like ReferenceId
type Reaction struct {
	referenceId  string
	magnitude    float64
	angle        float64
	radAngle     float64
	reactionType ReactionType
	symbol       string
}

type ReactionJSON struct {
	Symbol      string       `json:"symbol"`
	ReferenceId string       `json:"referenceId"`
	Type        ReactionType `json:"type"`
	Angle       float64      `json:"angle"`
	RadAngle    float64      `json:"radAngle"`
	Magnitude   float64      `json:"magnitude"`
	Cos         float64      `json:"cos"`
	Sin         float64      `json:"sin"`
}

func round(x float64, precision int) float64 {
	p := math.Pow10(precision)
	return math.Round(x*p) / p
}

func (r Reaction) ToReactionJSON() ReactionJSON {
	return ReactionJSON{
		Symbol:      r.symbol,
		ReferenceId: r.referenceId,
		Type:        r.reactionType,
		Angle:       r.angle,
		RadAngle:    round(r.radAngle, 3),
		Magnitude:   r.magnitude,
		Cos:         round(math.Cos(r.radAngle), 3),
		Sin:         round(math.Sin(r.radAngle), 3),
	}
}

func ConstructFromNode(n Node) []Reaction {
	if n.nodeType == Joint || n.nodeType == Free {
		return []Reaction{}
	}
	var reactionTypes []ReactionType
	if n.nodeType == U1U2 {
		reactionTypes = append(reactionTypes, R_U1, R_U2)
	} else if n.nodeType == U1 {
		reactionTypes = append(reactionTypes, R_U1)
	} else if n.nodeType == U2 {
		reactionTypes = append(reactionTypes, R_U2)
	} /*else {
		reactionTypes = []ReactionType{}
	}*/
	var reactions []Reaction
	for _, rt := range reactionTypes {
		r := Reaction{}
		r.referenceId = n.Id
		r.reactionType = rt
		if rt == R_U1 {
			r.symbol = n.GetU1Symbol()
			r.setAngle(0)
		} else {
			r.symbol = n.GetU2Symbol()
			r.setAngle(90)
		}
		reactions = append(reactions, r)
	}

	return reactions
}

func ConstructFromBeam(b Beam) Reaction {
	r := Reaction{}
	r.referenceId = b.Id
	r.reactionType = R_Beam
	r.setAngle(0)
	r.symbol = b.GetSymbol()
	return r
}
func ConstructFromForce(f Force) Reaction {
	r := Reaction{}
	r.referenceId = f.Id
	r.reactionType = R_Force
	r.setAngle(f.Angle)
	r.symbol = f.GetSymbol()
	return r
}

func ResultReaction() Reaction {
	r := Reaction{}
	r.referenceId = "nan"
	r.reactionType = R_Result
	r.setAngle(0)
	r.symbol = string(r.reactionType)
	return r
}

////////////////////

func (r Reaction) GetType() ReactionType {
	return r.reactionType
}
func (r Reaction) Print() string {
	return fmt.Sprintf("referenceId:%s\nmagnitude:%f\nangle:%f\nradAngle:%f\nreactionType:%s\nsymbol:%s",
		r.referenceId,
		r.magnitude,
		r.angle,
		r.radAngle,
		r.reactionType,
		r.symbol,
	)
}
func (r *Reaction) setAngle(angle float64) {
	r.angle = angle
	r.radAngle = angle * math.Pi / 180
}
func (r *Reaction) SetMagnitude(magnitude float64) {
	r.magnitude = magnitude
}
func (r Reaction) GetMagnitude() float64 {
	return r.magnitude
}
func (r Reaction) GetSymbol() string {
	return r.symbol
}
