package Payload

import (
	. "static_mechanism_solver/src/Core/Models"
	"testing"
)

func TestMapReactionsWithResults(t *testing.T) {
	r1 := Reaction{}
	r2 := Reaction{}
	result := []float64{1.2, 3.4}
	s := SystemRequest{}
	s.AddReaction(r1)
	s.AddReaction(r2)
	rs := s.MapReactionsWithResults(result)

	if rs[0].GetMagnitude() != 1.2 || rs[1].GetMagnitude() != 3.4 {
		t.Errorf("Failed to set reaction magnitues with result array")
	}
}

func TestCalcN(t *testing.T) {
	s := SystemRequest{}
	// N=1
	n1 := ConstructNode(U1, "n1", 0, 0)
	n1.AddForce(Force{ForceType: Defined})
	s.AddNode(n1)

	// N=2
	n2 := ConstructNode(U1U2, "n2", 0, 0)
	n2.AddForce(Force{ForceType: Defined})
	s.AddNode(n2)

	// N=2
	n3 := ConstructNode(U2, "n3", 0, 0)
	n3.AddForce(Force{ForceType: Unknown})
	s.AddNode(n3)

	// N=3
	n4 := ConstructNode(U1U2, "n4", 0, 0)
	n4.AddForce(Force{ForceType: Unknown})
	s.AddNode(n4)

	expected := 8
	result := s.calcN()
	if expected != result {
		t.Errorf("Expected %d, got %d", expected, result)
	}
}
