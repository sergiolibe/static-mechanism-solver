<?php
declare(strict_types=1);

namespace SMSolver\Core\Models;


use JsonSerializable;

class Node implements Mappable, JsonSerializable
{
    private ?string $id = null;
    private ?float $x = null;
    private ?float $y = null;
    private array $beams = [];
    private array $forces = [];

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
        $arrayRepresentation['beams'] = $this->getBeamsIds();
        $arrayRepresentation['forces'] = $this->getForcesIds();
        return $arrayRepresentation;
    }

    // Adders

    public function addBeam(Beam $beam): void
    {
        $this->beams[$beam->getId()] = $beam;
    }

    public function addForce(Force $force): void
    {
        $this->forces[$force->getId()] = $force;
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

    /**
     * @return string[]
     */
    public function getBeamsIds(): array
    {
        return !empty($this->beams) ? array_keys($this->beams) : [];
    }

    /**
     * @return string[]
     */
    public function getForcesIds(): array
    {
        return !empty($this->forces) ? array_keys($this->forces) : [];
    }

    //Setters
}