<?php
declare(strict_types=1);

namespace SMSolver\Core\Models;


use JsonSerializable;
use RuntimeException;
use SMSolver\Core\Axis;

class Node implements Mappable, JsonSerializable
{
    private ?string $id = null;
    private ?float $x = null;
    private ?float $y = null;
    private ?NodeType $type = null;
    /** @var Beam[] */
    private array $beams = [];
    /** @var Force[] */
    private array $forces = [];
    private ?array $symbols = null;
    private ?string $XSymbol = null;
    private ?string $YSymbol = null;


    public static function constructFromArray(array $data): self
    {
        $instance = new self();
        $instance->id = $data['id'];
        $instance->x = $data['x'];
        $instance->y = $data['y'];
        $instance->type = $data['type'];
        return $instance;
    }

    public function jsonSerialize()
    {
        $arrayRepresentation = [];
        $arrayRepresentation['id'] = $this->id;
        $arrayRepresentation['x'] = $this->x;
        $arrayRepresentation['y'] = $this->y;
        $arrayRepresentation['symbols'] = $this->getSymbols();
        $arrayRepresentation['type'] = $this->type;
        $arrayRepresentation['beams'] = $this->getBeamsIds();
        $arrayRepresentation['forces'] = $this->getForcesIds();
        return $arrayRepresentation;
    }

    public function getN()
    {
        $n = 0;
        if ($this->type->equals(NodeType::U1U2()))
            $n += 2;
        elseif ($this->type->equals(NodeType::U1()) || $this->type->equals(NodeType::U2()))
            $n += 1;

        foreach ($this->forces as $force)
            if ($force->getType()->equals(ForceType::UNKNOWN()))
                $n += 1;

        return $n;
    }

    public function hasSymbols(): bool
    {
        return $this->hasXSymbol() || $this->hasYSymbol();
    }

    public function hasXSymbol(): bool
    {
        return match ($this->type->getValue()) {
            NodeType::U1()->getValue(),
            NodeType::U1U2()->getValue() => true,
            default => false
        };
    }

    public function hasYSymbol(): bool
    {
        return match ($this->type->getValue()) {
            NodeType::U2()->getValue(),
            NodeType::U1U2()->getValue() => true,
            default => false
        };
    }

    public function getSymbols(): array
    {
        if (is_null($this->symbols)) {
            $this->symbols = [];
            if ($this->hasXSymbol())
                $this->symbols[] = $this->getXSymbol();

            if ($this->hasYSymbol())
                $this->symbols[] = $this->getYSymbol();
        }

        return $this->symbols;
    }

    public function getXSymbol(): string
    {
        if (is_null($this->XSymbol))
            $this->XSymbol = 'S' . $this->getId() . 'x';

        return $this->XSymbol;
    }


    public function getYSymbol(): string
    {
        if (is_null($this->YSymbol))
            $this->YSymbol = 'S' . $this->getId() . 'y';

        return $this->YSymbol;
    }

    public function getValuesBySymbolByAxis(Axis $axis): array
    {
        $valuesBySymbol = [];
        if ($axis->equals(Axis::X())) {
            foreach ($this->beams as $beam) {
                $valuesBySymbol[$beam->getSymbol()] = $beam->getCosOnNode($this);
            }

            if ($this->hasXSymbol()) {
                $valuesBySymbol[$this->getXSymbol()] = 1;
            }

            foreach ($this->forces as $force) {
                if ($force->getType()->equals(ForceType::UNKNOWN()))
                    $valuesBySymbol[$force->getSymbol()] = $force->getCos();
                if ($force->getType()->equals(ForceType::DEFINED()))
                    $valuesBySymbol['R'] = $force->getMagnitude() * $force->getCos();
            }

        } elseif ($axis->equals(Axis::Y())) {
            foreach ($this->beams as $beam) {
                $valuesBySymbol[$beam->getSymbol()] = $beam->getSinOnNode($this);
            }

            if ($this->hasYSymbol()) {
                $valuesBySymbol[$this->getYSymbol()] = 1;
            }

            foreach ($this->forces as $force) {
                if ($force->getType()->equals(ForceType::UNKNOWN()))
                    $valuesBySymbol[$force->getSymbol()] = $force->getSin();
                if ($force->getType()->equals(ForceType::DEFINED()))
                    $valuesBySymbol['R'] = $force->getMagnitude() * $force->getSin();
            }

        } else {
            throw new RuntimeException('Axis ' . $axis->getKey() . ' not supported');
        }
        return $valuesBySymbol;
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