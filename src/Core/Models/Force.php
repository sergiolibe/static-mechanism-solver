<?php
declare(strict_types=1);

namespace SMSolver\Core\Models;

use JsonSerializable;

class Force implements Mappable, JsonSerializable
{
    private ?string $id = null;
    private ?float $magnitude = null;
    private ?Node $node = null;

    public static function constructFromArray(array $data): self
    {
        $instance = new self();
        $instance->id = $data['id'];
        $instance->magnitude = $data['magnitude'];
        return $instance;
    }

    public function jsonSerialize()
    {
        $arrayRepresentation = [];
        $arrayRepresentation['id'] = $this->id;
        $arrayRepresentation['magnitude'] = $this->magnitude;
        return $arrayRepresentation;
    }

    // Adders


    // Getters

    public function getId(): ?string
    {
        return $this->id;
    }

    //Setters
}