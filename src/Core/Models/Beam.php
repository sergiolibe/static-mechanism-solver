<?php
declare(strict_types=1);

namespace SMSolver\Core\Models;


use JsonSerializable;

class Beam implements Mappable, JsonSerializable
{
    private ?string $id = null;
    private ?Node $startNode = null;
    private ?Node $endNode = null;

    public static function constructFromArray(array $data): self
    {
        $instance = new self();
        $instance->id = $data['id'];
        $instance->startNode = $data['startNode'];
        $instance->endNode = $data['endNode'];
        return $instance;
    }

    public function jsonSerialize()
    {
        $arrayRepresentation = [];
        $arrayRepresentation['id'] = $this->id;
        $arrayRepresentation['startNode'] = $this->startNode;
        $arrayRepresentation['endNode'] = $this->endNode;
        return $arrayRepresentation;
    }

    // Getters

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getStartNode(): ?Node
    {
        return $this->startNode;
    }

    public function getEndNode(): ?Node
    {
        return $this->endNode;
    }
}