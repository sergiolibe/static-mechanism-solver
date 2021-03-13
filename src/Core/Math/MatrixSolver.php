<?php


namespace SMSolver\Core\Math;


use SMSolver\Utils\OutputInfo;

class MatrixSolver
{


    /**
     * Gaussian elimination
     * @param float[][] $A matrix n x n
     * @param float[] $x vector 1 x n
     * @return float[] solution vector 1 x n
     */
    public static function solveAX(array $A, array $x): array
    {
        # Just make a single matrix
        for ($i = 0; $i < count($A); $i++) {
            $A[$i][] = $x[$i];
        }
        return self::solve($A);
    }

    /**
     * Gaussian elimination
     * @param float[][] $A matrix n x (n+1)
     * @return float[] solution vector 1 x n
     */
    public static function solve(array $A): array
    {
        $n = count($A);

        for ($i = 0; $i < $n; $i++) {
            # Search for maximum in this column
            $maxEl = abs($A[$i][$i]);
            $maxRow = $i;
            for ($k = $i + 1; $k < $n; $k++) {//echo 'inspecting $A['.$k.']['.$i.']'.PHP_EOL;
                if (abs($A[$k][$i]) > $maxEl) {
                    $maxEl = abs($A[$k][$i]);
                    $maxRow = $k;
                }
            }


            # Swap maximum row with current row (column by column)
            for ($k = $i; $k < $n + 1; $k++) {
                $tmp = $A[$maxRow][$k];
                $A[$maxRow][$k] = $A[$i][$k];
                $A[$i][$k] = $tmp;
            }

            # Make all rows below this one 0 in current column
            for ($k = $i + 1; $k < $n; $k++) {
                if($A[$i][$i] === 0.0)
                    $c = 0;
                else
                    $c = -$A[$k][$i] / $A[$i][$i];
                for ($j = $i; $j < $n + 1; $j++) {
                    if ($i == $j) {
                        $A[$k][$j] = 0;
                    } else {
                        $A[$k][$j] += $c * $A[$i][$j];
                    }
                }
            }
        }

        # Solve equation Ax=b for an upper triangular matrix $A
        $x = array_fill(0, $n, 0);
        for ($i = $n - 1; $i > -1; $i--) {
            if ($A[$i][$i] === 0.0)
                $x[$i] = $A[$i][$n];
            else
                $x[$i] = $A[$i][$n] / $A[$i][$i];

            for ($k = $i - 1; $k > -1; $k--) {
                $A[$k][$n] -= $A[$k][$i] * $x[$i];
            }
        }

        return $x;
    }
}