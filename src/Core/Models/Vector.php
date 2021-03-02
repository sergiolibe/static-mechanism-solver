<?php
declare(strict_types=1);

namespace SMSolver\Core\Models;


trait Vector
{
    private float $radAngle;
    private float $magnitude = -1e3;
    private float $angle = -1e3;

    public function getMagnitude(): float
    {
        return $this->magnitude;
    }

    public function getCos(): float
    {
        return cos($this->radAngle);
    }

    public function getSin(): float
    {
        return sin($this->radAngle);
    }

    public function getAngle(): float
    {
        return $this->angle;
    }

    private function toRadians(float $degrees): float
    {
        return $degrees * M_PI / 180;
    }
}