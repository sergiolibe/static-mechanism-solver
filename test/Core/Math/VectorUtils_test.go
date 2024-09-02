package Math

import (
	"static_mechanism_solver/src/Core/Math"
	"testing"
)

func TestRoundVector(t *testing.T) {
	notRoundVector := []float64{1.76547, 2.37654367, -4.38765783}

	expectedRoundVector := []float64{1.765, 2.377, -4.388}

	notRoundVector = Math.RoundVector(notRoundVector, 3)

	if !vectorsEqual(expectedRoundVector, notRoundVector) {
		t.Errorf("Expected %v, got %v", expectedRoundVector, notRoundVector)
	}

	expectedRoundVector2Precision := []float64{1.77, 2.38, -4.39}
	notRoundVector = Math.RoundVector(notRoundVector, 2)
	if !vectorsEqual(expectedRoundVector2Precision, notRoundVector) {
		t.Errorf("Expected %v, got %v", expectedRoundVector2Precision, notRoundVector)
	}

	expectedRoundVector1Precision := []float64{1.8, 2.4, -4.4}
	notRoundVector = Math.RoundVector(notRoundVector, 1)
	if !vectorsEqual(expectedRoundVector1Precision, notRoundVector) {
		t.Errorf("Expected %v, got %v", expectedRoundVector1Precision, notRoundVector)
	}

}
