package Models

import "math"

type Force struct {
	//beams []Beam
	//forces []Force
	ForceType ForceType
	Id        string `json:"id,omitempty"`
	symbol    *string
	//x        float64
	//y        float64
	RadAngle  float64
	Magnitude float64
	Angle     float64
}

func ConstructForce(forceType ForceType, id string, magnitude *float64, angle float64) Force {
	f := Force{}
	f.ForceType = forceType
	f.Id = id
	f.Angle = angle
	if magnitude == nil {
		f.Magnitude = 0.0
	} else {
		f.Magnitude = *magnitude
	}
	return f
}
func (f Force) GetSymbol() string {
	if f.symbol == nil { // _todo: cache this
		return "F_" + f.Id
	}

	return *f.symbol
}

func (f Force) GetCos() float64 {
	return math.Cos(f.RadAngle)
}

func (f Force) GetSin() float64 {
	return math.Sin(f.RadAngle)
}

func (f Force) GetAngle() float64 {
	return f.Angle
}

func (f Force) toRadians(degrees float64) float64 {
	return degrees * math.Pi / 180
}
