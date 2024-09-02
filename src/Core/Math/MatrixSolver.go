package Math

import "math"

// SolveAX performs a Gaussian elimination
//
// Parameters:
//   - A: matrix n x n
//   - x: vector 1 x n
//
// Returns:
//   - []float64: a solution vector 1 x n
func SolveAX(A [][]float64, x []float64) []float64 {
	for i := range A {
		A[i] = append(A[i], x[i])
	}
	return Solve(A)
}

// Solve performs a Gaussian elimination
//
// Parameters:
//   - A: matrix n x (n + 1)
//
// Returns:
//   - []float64: a solution vector 1 x n
func Solve(A [][]float64) []float64 {
	n := len(A)
	for i := 0; i < n; i++ {
		// Search for maximum in this column
		maxEl := math.Abs(A[i][i])
		maxRow := i
		for k := i + 1; k < n; k++ {
			if math.Abs(A[k][i]) > maxEl {
				maxEl = math.Abs(A[k][i])
				maxRow = k
			}
		}

		// Swap maximum row with current row (column by column)
		for k := i; k < n+1; k++ {
			tmp := A[maxRow][k]
			A[maxRow][k] = A[i][k]
			A[i][k] = tmp
		}

		// Make all rows below this one 0 in current column
		for k := i + 1; k < n; k++ {
			c := 0.0
			if A[i][i] == 0 {
				c = 0
			} else {
				c = -A[k][i] / A[i][i]
			}
			for j := i; j < n+1; j++ {
				if i == j {
					A[k][j] = 0
				} else {
					A[k][j] += c * A[i][j]
				}
			}
		}
	}

	// Solve equation Ax=b for an upper triangular matrix A
	x := ZerosVector[float64](n)
	for i := n - 1; i > -1; i-- {
		if A[i][i] == 0 {
			x[i] = A[i][n]
		} else {
			x[i] = A[i][n] / A[i][i]
		}
		for k := i - 1; k > -1; k-- {
			A[k][n] -= A[k][i] * x[i]
		}
	}

	return x
}
