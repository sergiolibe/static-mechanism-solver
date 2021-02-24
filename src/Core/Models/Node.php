<?php
declare(strict_types=1);

namespace SMSolver\Core\Models;


use JsonSerializable;

class Node implements Mappable, JsonSerializable
{
    private ?string $id = null;
    private ?float $x = null;
    private ?float $y = null;

    public static function constructFromArray(array $data): self
    {
        $instance = new self();
        $instance->id = $data['id'];
        $instance->x = $data['x'];
        $instance->y = $data['y'];
        return $instance;
    }

    public function jsonSerialize()
    {
        $arrayRepresentation = [];
        $arrayRepresentation['id'] = $this->id;
        $arrayRepresentation['x'] = $this->x;
        $arrayRepresentation['y'] = $this->y;
        return $arrayRepresentation;
    }

    // Getters

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getX(): ?float
    {
        return $this->x;
    }

    public function getY(): ?float
    {
        return $this->y;
    }

}