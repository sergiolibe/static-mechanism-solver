package Math

import (
	"math"
	"static_mechanism_solver/src/Core/Math"
	"testing"
)

func TestCorrectSolutionOutput(t *testing.T) {
	//A := [][]float64{[]float64{1.0, 2.0, 3.0}, []float64{1.0, 2.0, 3.0}}

	A := [][]float64{
		{7},
	}
	x := []float64{
		3,
	}
	result := Math.SolveAX(A, x)
	expected := []float64{3.0 / 7.0}
	if !vectorsEqual(expected, result) {
		t.Errorf("Expected %v, got %v", expected, result)
	}

	A = [][]float64{
		{7, 1},
		{5, 13},
	}
	x = []float64{
		1,
		3,
	}
	result = Math.SolveAX(A, x)
	expected = []float64{5.0 / 43.0, 8.0 / 43.0}
	if !vectorsEqual(expected, result) {
		t.Errorf("Expected %v, got %v", expected, result)
	}

	A = [][]float64{
		{7, 1, 6, 3},
		{5, 13, 0, 2},
		{4, 9, 2, 1},
		{-1, -8, 1.5, 10},
	}
	x = []float64{
		1,
		3,
		13,
		4,
	}
	result = Math.SolveAX(A, x)
	expected = []float64{
		-18680.0 / 2647.0,
		7461.0 / 2647.0,
		19904.0 / 2647.0,
		2174.0 / 2647.0,
	}
	if !vectorsEqual(expected, result) {
		t.Errorf("Expected %v, got %v", expected, result)
	}
}

func TestCorrectOutputMiddleMatrix(t *testing.T) {

	F := 1.0
	dX := 2.0
	dY := 1.0
	H := math.Sqrt(dX*dX + dY*dY)
	cosAlpha := dX / H
	sinAlpha := dY / H
	Ax := [][]float64{
		{0, cosAlpha, 0, 0, 0, 0, F},
		{0, sinAlpha, 1, 0, 0, 0, 0},
		{1, 0, 0, 1, 0, 0, 0},
		{0, 0, 1, 0, 1, 0, 0},
		{1, cosAlpha, 0, 0, 0, 0, 0},
		{0, sinAlpha, 0, 0, 0, 1, 0},
	}
	// expected = ['F12','F23','F13','S1X','S1Y','S2Y'];
	expected := []float64{-1.0, 1.118, -0.5, 1.0, 0.5, -0.5}
	result := Math.Solve(Ax)
	if !vectorsEqual(expected, result) {
		t.Errorf("Expected %v, got %v", expected, result)
	}
}
func TestCorrectOutputMiddleMatrix2(t *testing.T) {
	F1 := 50.0
	x1 := 0.0
	y1 := 0.0
	x2 := -14.0
	y2 := 14.0
	x3 := 50.0
	y3 := -2.0
	x4 := 17.0
	y4 := 14.0
	x5 := 52.0
	y5 := -21.0

	dx12 := -14.0
	dy12 := 14.0
	H12 := +math.Sqrt((dx12 * dx12) + (dy12 * dy12))

	dx13 := 50.0
	dy13 := -2.0
	H13 := +math.Sqrt((dx13 * dx13) + (dy13 * dy13))

	dx23 := 64.0
	dy23 := -16.0
	H23 := +math.Sqrt((dx23 * dx23) + (dy23 * dy23))

	dx35 := 2.0
	dy35 := -19.0
	H35 := +math.Sqrt((dx35 * dx35) + (dy35 * dy35))

	dx45 := 35.0
	dy45 := -35.0
	H45 := +math.Sqrt((dx45 * dx45) + (dy45 * dy45))

	cosAlphaFx := math.Cos(math.Pi / 4)
	sinAlphaFx := math.Sin(math.Pi / 4)

	Ax := [][]float64{
		{(x2 - x1) / H12, (x3 - x1) / H13, 0, 0, 0, 1, 0, 0, 0, 0, 0}, //1x
		{(y2 - y1) / H12, (y3 - y1) / H13, 0, 0, 0, 0, 1, 0, 0, 0, 0}, //1y

		{(x1 - x2) / H12, 0, (x3 - x2) / H23, 0, 0, 0, 0, 0, 0, 0, F1}, //2x
		{(y1 - y2) / H12, 0, (y3 - y2) / H23, 0, 0, 0, 0, 0, 0, 0, 0},  //2y

		{0, (x1 - x3) / H13, (x2 - x3) / H23, (x5 - x3) / H35, 0, 0, 0, 0, 0, 0, 0}, //3x
		{0, (y1 - y3) / H13, (y2 - y3) / H23, (y5 - y3) / H35, 0, 0, 0, 0, 0, 0, 0}, //3y

		{0, 0, 0, 0, (x5 - x4) / H45, 0, 0, 1, 0, 0, 0}, //4x
		{0, 0, 0, 0, (y5 - y4) / H45, 0, 0, 0, 1, 0, 0}, //4y

		{0, 0, 0, (x3 - x5) / H35, (x4 - x5) / H45, 0, 0, 0, 0, cosAlphaFx, 0}, //5x
		{0, 0, 0, (y3 - y5) / H35, (y4 - y5) / H45, 0, 0, 0, 0, sinAlphaFx, 0}, //5y
	}

	//        expectedResult = ['F12','F13','F23','F35','F45','S1X','S1Y','S4X','S4Y','Fx'];
	expected := []float64{23.57, 65.239, -68.718, -14.137, 10.988, -48.52, -14.059, -7.77, 7.77, 8.895}

	result := Math.Solve(Ax)
	result = Math.ScalarMultiply(result, -1)
	result = Math.RoundVector(result, 3)

	if !vectorsEqual(expected, result) {
		t.Errorf("Expected %v, got %v", expected, result)
	}
}

func TestCorrectOutputBigMatrix(t *testing.T) {

	Ax := [][]float64{
		{0.5547, 0.0000, 0.0000, 0.0000, 1.0000, 0.0000, 0.0000, 0.0000, 1.0000, 0.0000, 0.0000},
		{0.8321, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 1.0000, 0.0000, 0.0000, 0.0000},
		{-.5547, 1.0000, 0.0000, 0.0000, 0.0000, 0.5547, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000},
		{-.8321, 0.0000, 0.0000, 0.0000, 0.0000, -.8321, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000},
		{0.0000, -1.000, 0.5547, 0.0000, 0.0000, 0.0000, -.5547, 0.0000, 0.0000, 0.0000, 0.0000},
		{0.0000, 0.0000, -.8321, 0.0000, 0.0000, 0.0000, -.8321, 0.0000, 0.0000, 0.0000, 0.0000},
		{0.0000, 0.0000, 0.0000, 1.0000, -1.000, -.5547, 0.5547, 0.0000, 0.0000, 0.0000, 0.0000},
		{0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.8321, 0.8321, 0.0000, 0.0000, 0.0000, 4.0000},
		{0.0000, 0.0000, -.5547, -1.000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000},
		{0.0000, 0.0000, 0.8321, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 1.0000, 0.0000},
	}
	expected := []float64{-2.404, -2.667, -2.404, 1.333, 1.333, 2.404, 2.404, 2.0, 0.0, 2.0}

	result := Math.Solve(Ax)
	result = Math.RoundVector(result, 3)

	if !vectorsEqual(expected, result) {
		t.Errorf("Expected %v, got %v", expected, result)
	}
}

////////////////////

func vectorsEqual(a, b []float64) bool {
	const tolerance = 0.000001
	// Check if both matrices have the same number of rows
	if len(a) != len(b) {
		return false
	}

	// Check if each row has the same number of columns and corresponding elements are equal
	for i := range a {
		if a[i]-b[i] > tolerance {
			return false
		}
	}

	return true
}

func matricesEqual(a, b [][]float64) bool {
	// Check if both matrices have the same number of rows
	if len(a) != len(b) {
		return false
	}

	// Check if each row has the same number of columns and corresponding elements are equal
	for i := range a {
		if len(a[i]) != len(b[i]) {
			return false
		}
		for j := range a[i] {
			if a[i][j] != b[i][j] {
				return false
			}
		}
	}

	return true
}
