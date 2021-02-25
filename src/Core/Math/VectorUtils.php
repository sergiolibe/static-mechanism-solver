<?php
declare(strict_types=1);

namespace SMSolver\Core\Math;


class VectorUtils
{
    public static function roundVector(array &$vector, int $precision = 3): void
    {
        foreach ($vector as &$entry)
            $entry = round($entry, $precision);
    }

    public static function scalarMultiply(array &$vector, float $scalar): void
    {
        foreach ($vector as &$entry)
            $entry = $entry * $scalar;
    }

    /**
     * @param int $rows
     * @param int $columns
     * @return int[][]
     */
    public static function zerosMatrix(int $rows, int $columns): array
    {
        $M = array_fill(0, $rows, $columns);
        return array_map(fn(int $columns) => self::zerosVector($columns), $M);
    }

    /**
     * @param int $entries
     * @return int[]
     */
    public static function zerosVector(int $entries): array
    {
        return array_fill(0, $entries, 0);
    }
}