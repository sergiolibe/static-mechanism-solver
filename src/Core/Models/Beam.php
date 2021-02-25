<?php
declare(strict_types=1);

namespace SMSolver\Core\Models;


use JsonSerializable;
use RuntimeException;

class Beam implements Mappable, JsonSerializable
{
    private ?string $id = null;
    private ?Node $startNode = null;
    private ?Node $endNode = null;

    //cached values
    private ?string $symbol = null;
    private ?float $longitude = null;

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
        $arrayRepresentation['startNode'] = $this->startNode->getId();
        $arrayRepresentation['endNode'] = $this->endNode->getId();
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

    public function getSymbol(): string
    {
        if (is_null($this->symbol))
            $this->symbol = 'B' . $this->startNode->getId() . $this->endNode->getId();

        return $this->symbol;
    }

    public function getCosOnNode(Node $node): float
    {
        if ($this->startNode->getId() === $node->getId()) {
            $dX = $this->endNode->getX()-$this->startNode->getX();
            return $dX/$this->getLongitude();
        }

        if ($this->endNode->getId() === $node->getId()) {
            $dX = $this->startNode->getX() - $this->endNode->getX();
            return $dX/$this->getLongitude();
        }

        throw new RuntimeException('Node [' . $node->getId() . '] is not the start or end of this beam (' . $this->id . ')');
    }

    public function getSinOnNode(Node $node): float
    {
        if ($this->startNode->getId() === $node->getId()) {
            $dY = $this->endNode->getY()-$this->startNode->getY();
            return $dY/$this->getLongitude();
        }

        if ($this->endNode->getId() === $node->getId()) {
            $dY = $this->startNode->getY() - $this->endNode->getY();
            return $dY/$this->getLongitude();
        }

        throw new RuntimeException('Node [' . $node->getId() . '] is not the start or end of this beam (' . $this->id . ')');
    }

    public function getLongitude(): float
    {
        if (is_null($this->longitude)) {
            $dX = $this->startNode->getX() - $this->endNode->getX();
            $dY = $this->startNode->getY() - $this->endNode->getY();
            $this->longitude = sqrt($dX * $dX + $dY * $dY);
        }

        return $this->longitude;
    }
}