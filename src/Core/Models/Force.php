<?php
declare(strict_types=1);

namespace SMSolver\Core\Models;

use JsonSerializable;

class Force implements Mappable, JsonSerializable
{
    private ?string $id = null;
    private ?float $magnitude = null;
    private ?float $angle = null;
    private ?float $radAngle = null;
    private ?Node $node = null;
    private ForceType $type;
    private ?string $symbol = null;

    private function __construct()
    {
    }

    public static function constructFromArray(array $data): self
    {
        $instance = new self();
        $instance->id = $data['id'];
        $instance->type = $data['type'];
        if ($instance->type->equals(ForceType::DEFINED()))
            $instance->magnitude = $data['magnitude'];
        $instance->angle = $data['angle'];
        $instance->radAngle = $instance->toRadians($instance->angle);
        $instance->node = $data['node'];
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
            ForceType::UNKNOWN()->getValue() => true,
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

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getType(): ?ForceType
    {
        return $this->type;
    }

    public function getMagnitude(): ?float
    {
        return $this->magnitude;
    }

    public function getCos()
    {
        return cos($this->radAngle);
    }

    public function getSin()
    {
        return sin($this->radAngle);
    }

    private function toRadians(float $degrees): float
    {
        return $degrees * M_PI / 180;
    }
}