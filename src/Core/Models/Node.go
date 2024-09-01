package Models

import (
	. "static_mechanism_solver/src/Core"
)

type Node struct {
	Beams    []Beam  `json:"beams,omitempty"`
	Forces   []Force `json:"forces,omitempty"`
	nodeType NodeType
	Id       string `json:"id,omitempty"`
	symbol   *string
	x        float64
	y        float64
}

func ConstructNode(nodeType NodeType, id string, x float64, y float64) Node {
	n := Node{}
	n.nodeType = nodeType
	n.Id = id
	n.x = x
	n.y = y
	return n
}
func (n Node) getId() string {
	return n.Id
}
func (n Node) GetN() int {
	N := 0
	if n.nodeType == U1U2 {
		N += 2
	} else if n.nodeType == U1 || n.nodeType == U2 {
		N += 1
	}

	for _, f := range n.Forces {
		if f.ForceType == Unknown {
			N += 1
		}
	}

	return N
}

func (n Node) hasU1Symbol() bool {
	return n.nodeType == U1 || n.nodeType == U1U2

}

func (n Node) hasU2Symbol() bool {
	return n.nodeType == U2 || n.nodeType == U1U2
}

func (n Node) GetValuesBySymbolByAxis(axis Axis) map[string]float64 {
	valuesBySymbol := map[string]float64{}

	r := R_Result

	if axis == X {
		for _, b := range n.Beams {
			valuesBySymbol[b.GetSymbol()] = b.GetCosOnNode(n)
		}
		if n.hasU1Symbol() {
			valuesBySymbol[n.GetU1Symbol()] = 1
		}
		for _, f := range n.Forces {
			if f.ForceType == Unknown {
				valuesBySymbol[f.GetSymbol()] = f.GetCos()
			} else if f.ForceType == Defined {
				valuesBySymbol[string(r)] = f.Magnitude * f.GetCos()
			}
		}
	} else if axis == Y {
		for _, b := range n.Beams {
			valuesBySymbol[b.GetSymbol()] = b.GetSinOnNode(n)
		}
		if n.hasU2Symbol() {
			valuesBySymbol[n.GetU2Symbol()] = 1
		}
		for _, f := range n.Forces {
			if f.ForceType == Unknown {
				valuesBySymbol[f.GetSymbol()] = f.GetSin()
			} else if f.ForceType == Defined {
				valuesBySymbol[string(r)] = f.Magnitude * f.GetSin()
			}
		}
	} else {
		panic("Axis " + axis + " not supported")
	}

	return valuesBySymbol
}

// Adders

func (n *Node) AddBeam(b Beam) { // _todo: maybe replace with n.Beams = append(n.Beams, b)
	n.Beams = append(n.Beams, b)
}

func (n *Node) AddForce(f Force) { // _todo: maybe replace with n.Forces = append(n.Forces, f)
	n.Forces = append(n.Forces, f)
}

// Getters

func (n Node) GetU1Symbol() string {
	if !n.hasU1Symbol() {
		panic("NodeType doesn't have U1Symbol (" + n.nodeType + ")")
	}
	return "Sx_" + n.Id
}

func (n Node) GetU2Symbol() string {
	if !n.hasU2Symbol() {
		panic("NodeType doesn't have U2Symbol (" + n.nodeType + ")")
	}
	return "Sy_" + n.Id
}

func (n Node) GetBeamsIds() []string {
	ids := make([]string, len(n.Beams))
	for i, b := range n.Beams {
		ids[i] = b.Id
	}
	return ids
}

func (n Node) GetForcesIds() []string {
	ids := make([]string, len(n.Forces))
	for i, f := range n.Forces {
		ids[i] = f.Id
	}
	return ids
}

/*<?php
declare(strict_types=1);

namespace SMSolver\Core\Models;


use JsonSerializable;
use RuntimeException;
use SMSolver\Core\Axis;

class Node implements JsonSerializable
{
//    private string $id = '_nodeId';
//    private float $x = -1e3;
//    private float $y = -1e3;
//    private NodeType $type;

    /** @var Beam[]
    private array $beams = [];
    /** @var Force[]
    private array $forces = [];

    /** @var string[]|null $symbols
    private ?array $symbols = null;
    private ?string $U1Symbol = null;
    private ?string $U2Symbol = null;

    public function __construct(
        private NodeType $type,
        private string $id = '_nodeId',
        private float $x = -1e3,
        private float $y = -1e3,
    )
    {
    }


    /**
     * @param array<string,scalar> $data
     * @return self

    public static function constructFromArray(array $data): self
    {
        return new self(
            new NodeType((string)$data['type']),
            (string)$data['id'],
            (float)$data['x'],
            (float)$data['y'],
        );
    }

    public function getN(): int
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
        return $this->hasU1Symbol() || $this->hasU2Symbol();
    }

    public function hasU1Symbol(): bool
    {
        return match ($this->type->getValue()) {
            NodeType::U1()->getValue(),
            NodeType::U1U2()->getValue() => true,
            default => false
        };
    }

    public function hasU2Symbol(): bool
    {
        return match ($this->type->getValue()) {
            NodeType::U2()->getValue(),
            NodeType::U1U2()->getValue() => true,
            default => false
        };
    }

    /**
     * @param Axis $axis
     * @return array<string,float>

    public function getValuesBySymbolByAxis(Axis $axis): array
    {
        $valuesBySymbol = [];
        $R = ReactionType::RESULT()->getValue();
        if ($axis->equals(Axis::X())) {
            foreach ($this->beams as $beam)
                $valuesBySymbol[$beam->getSymbol()] = $beam->getCosOnNode($this);

            if ($this->hasU1Symbol())
                $valuesBySymbol[$this->getU1Symbol()] = 1;

            foreach ($this->forces as $force) {
                if ($force->getType()->equals(ForceType::UNKNOWN()))
                    $valuesBySymbol[$force->getSymbol()] = $force->getCos();
                if ($force->getType()->equals(ForceType::DEFINED()))
                    $valuesBySymbol[$R] = $force->getMagnitude() * $force->getCos();
            }
        } elseif ($axis->equals(Axis::Y())) {
            foreach ($this->beams as $beam)
                $valuesBySymbol[$beam->getSymbol()] = $beam->getSinOnNode($this);

            if ($this->hasU2Symbol())
                $valuesBySymbol[$this->getU2Symbol()] = 1;

            foreach ($this->forces as $force) {
                if ($force->getType()->equals(ForceType::UNKNOWN()))
                    $valuesBySymbol[$force->getSymbol()] = $force->getSin();
                if ($force->getType()->equals(ForceType::DEFINED()))
                    $valuesBySymbol[$R] = $force->getMagnitude() * $force->getSin();
            }

        } else {
            throw new RuntimeException('Axis ' . $axis->getKey() . ' not supported');
        }
        return $valuesBySymbol;
    }

    public static function validateInstance(mixed $instance): self
    {
        if (!$instance instanceof Node)
            throw new RuntimeException('Expected instances of Node, received: ' . json_encode($instance));

        return $instance;
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

    public function getId(): string
    {
        return $this->id;
    }

    public function getX(): float
    {
        return $this->x;
    }

    public function getY(): float
    {
        return $this->y;
    }

    public function getU1Symbol(): string
    {
        if(!$this->hasU1Symbol())
            throw new RuntimeException('NodeType doesn\'t have U1Symbol (' . $this->type . ')');

        if (is_null($this->U1Symbol))
            $this->U1Symbol = 'Sx_' . $this->getId();

        return $this->U1Symbol;
    }

    public function getU2Symbol(): string
    {
        if(!$this->hasU2Symbol())
            throw new RuntimeException('NodeType doesn\'t have U2Symbol (' . $this->type . ')');

        if (is_null($this->U2Symbol))
            $this->U2Symbol = 'Sy_' . $this->getId();

        return $this->U2Symbol;
    }

    /**
     * @return string[]

    public function getBeamsIds(): array
    {
        return !empty($this->beams) ? array_keys($this->beams) : [];
    }

    /**
     * @return string[]

    public function getForcesIds(): array
    {
        return !empty($this->forces) ? array_keys($this->forces) : [];
    }

    public function getType(): NodeType
    {
        return $this->type;
    }

    //Setters

    //Interfaces

    public function jsonSerialize(): array
    {
        $arrayRepresentation = [];
        $arrayRepresentation['id'] = $this->id;
        $arrayRepresentation['x'] = $this->x;
        $arrayRepresentation['y'] = $this->y;
        $arrayRepresentation['type'] = $this->type;
        $arrayRepresentation['beams'] = $this->getBeamsIds();
        $arrayRepresentation['forces'] = $this->getForcesIds();
        return $arrayRepresentation;
    }

    public function __toString()
    {
        return 'Node [' . $this->id . '] (' . $this->x . ',' . $this->y . ') {' . $this->type . '}';
    }
}
*/
