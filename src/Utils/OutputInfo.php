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

    public static function printJSONln(JsonSerializable $data, bool $pretty = false): void
    {
        self::echoln(json_encode($data, $pretty ? JSON_PRETTY_PRINT : 0));
    }
}