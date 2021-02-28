<?php
declare(strict_types=1);

namespace SMSolver\Core\Models;

use JsonSerializable;

class Reaction implements JsonSerializable
{
    private ?string $referenceId = null;
    private ?float $magnitude = null;
    private ?float $angle = null;
    private ?float $radAngle = null;
    private ?Node $node = null;
    private ReactionType $type;
    private ?string $symbol = null;

    private function __construct()
    {
    }

    public static function constructFromBeam(Beam $beam): self
    {
        $instance = new self();
        $instance->referenceId = $beam->getId();
        $instance->type = ReactionType::BEAM();
//        $instance->

        return $instance;
    }

    public function jsonSerialize()
    {
        $arrayRepresentation = [];
        $arrayRepresentation['id'] = $this->id;
        $arrayRepresentation['magnitude'] = $this->magnitude;
        $arrayRepresentation['angle'] = $this->angle;
        $arrayRepresentation['node'] = $this->node->getId();
        $arrayRepresentation['type'] = $this->type;
        return $arrayRepresentation;
    }

    public function hasSymbol(): bool
    {
        return match ($this->type->getValue()) {
            ReactionType::UNKNOWN()->getValue() => true,
            default => false
        };
    }

    public function getSymbol(): string
    {
        if (is_null($this->symbol))
            $this->symbol = 'F' . $this->id;

        return $this->symbol;
    }
    // Adders


    // Getters



    public function getType(): ?ReactionType
    {
        return $this->type;
    }

    public function getMagnitude(): ?float
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

    private function toRadians(float $degrees): float
    {
        return $degrees * M_PI / 180;
    }
}