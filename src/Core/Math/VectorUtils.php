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
}