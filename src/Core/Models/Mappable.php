<?php
declare(strict_types=1);

namespace SMSolver\Core\Models;


interface Mappable
{
    public static function constructFromArray(array $data): self;
}