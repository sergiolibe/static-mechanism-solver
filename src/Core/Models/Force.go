package Models

import "math"

type Force struct {
	//beams []Beam
	//forces []Force
	ForceType ForceType
	Id        string `json:"id,omitempty"`
	symbol    *string
	//x        float64
	//y        float64
	RadAngle  float64
	Magnitude float64
	Angle     float64
}

func ConstructForce(forceType ForceType, id string, magnitude *float64, angle float64) Force {
	f := Force{}
	f.ForceType = forceType
	f.Id = id
	f.Angle = angle
	if magnitude == nil {
		f.Magnitude = 0.0
	} else {
		f.Magnitude = *magnitude
	}
	return f
}
func (f Force) GetSymbol() string {
	if f.symbol == nil { // _todo: cache this
		return "F_" + f.Id
	}

	return *f.symbol
}

func (f Force) GetCos() float64 {
	return math.Cos(f.RadAngle)
}

func (f Force) GetSin() float64 {
	return math.Sin(f.RadAngle)
}

func (f Force) GetAngle() float64 {
	return f.Angle
}

func (f Force) toRadians(degrees float64) float64 {
	return degrees * math.Pi / 180
}

/*
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
*/
