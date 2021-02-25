<?php
declare(strict_types=1);

namespace SMSolver\Utils;


use JsonSerializable;

class OutputInfo
{

    public static function pr(mixed $data): void
    {
        print_r($data);
    }

    public static function vd(mixed $data): void
    {
        var_dump($data);
    }

    public static function echoln(string $data): void
    {
        echo $data . PHP_EOL;
    }

    /**
     * @param bool|float|int|string|array<mixed>|JsonSerializable $data
     * @param bool $pretty
     */
    public static function printJSONln(bool|float|int|string|array|JsonSerializable $data, bool $pretty = false): void
    {
        self::echoln(json_encode($data, $pretty ? JSON_PRETTY_PRINT : 0));
    }

    public static function printMatrix(array $matrix): void
    {
        foreach ($matrix as $row) {
            self::printJSONln($row);
        }
    }
}