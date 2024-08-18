<?php
declare(strict_types=1);

namespace SMSolver\Core\Models;

use RuntimeException;
use JsonSerializable;

class Force implements JsonSerializable
{
    use Vector;

    private ?string $symbol = null;

    public function __construct(
        private ForceType $type,
        private Node $node,
        private string $id = '_forceId',
        float $magnitude = -1e3,
        float $angle = -1e3,
    )
    {
        $this->magnitude = $magnitude;
        $this->angle = $angle;
        $this->radAngle = $this->toRadians($this->angle);
    }

    /**
     * @param array<string,scalar|Node> $data
     * @return self
     */
    public static function constructFromArray(array $data): self
    {
        return new self(
            new ForceType((string)$data['type']),
            Node::validateInstance($data['node']),
            (string)$data['id'],
            (float)($data['magnitude']??0),
            (float)$data['angle'],
        );
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
            $this->symbol = 'F_' . $this->id;

        return $this->symbol;
    }

    // Adders
    // Getters


    public function getId(): string
    {
        return $this->id;
    }

    public function getType(): ForceType
    {
        return $this->type;
    }

    // Interfaces

    public function jsonSerialize(): array
    {
        $arrayRepresentation = [];
        $arrayRepresentation['id'] = $this->id;
        $arrayRepresentation['magnitude'] = $this->magnitude;
        $arrayRepresentation['angle'] = $this->angle;
        $arrayRepresentation['node'] = $this->node->getId();
        $arrayRepresentation['type'] = $this->type;
        return $arrayRepresentation;
    }

    public function __toString()
    {
        return
            'force'
            . ' [' . $this->id . ']'
            . ' (' . $this->node->getX() . ',' . $this->node->getY() . ')'
            . ' {' . $this->type . '}'
            . ' ' . $this->magnitude . 'N'
            . '@' . $this->angle . '° (' . $this->radAngle . ' rad)';
    }
}