package Math

import "math"

func RoundVector(vector []float64, precision int) []float64 {
	p := math.Pow10(precision)
	for i, e := range vector {
		vector[i] = math.Round(e*p) / p
	}
	return vector
}

func ScalarMultiply(vector []float64, scalar float64) []float64 {

	for i, e := range vector {
		vector[i] = e * scalar
	}
	return vector
}

func ZerosMatrix[T any](rows int, columns int) [][]T {
	m := make([][]T, rows)
	for i, _ := range m {
		m[i] = ZerosVector[T](columns)
	}
	return m
}
func ZerosVector[T any](entries int) []T {
	return make([]T, entries)
}
