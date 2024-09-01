package Models

import "math"

type Beam struct {
	StartNode Node     `json:"start_node"`
	EndNode   Node     `json:"end_node"`
	Id        string   `json:"id,omitempty"`
	Symbol    *string  `json:"symbol,omitempty"`
	Longitude *float64 `json:"longitude,omitempty"`
}

func ConstructBeam(startNode Node, endNode Node, id string) Beam {
	b := Beam{}
	b.StartNode = startNode
	b.EndNode = endNode
	b.Id = id
	return b
}
func (b Beam) getId() string {
	return b.Id
}
func (b Beam) getStartNode() Node {
	return b.StartNode
}
func (b Beam) getEndNode() Node {
	return b.EndNode
}

func (b Beam) getSymbol() string {
	if b.Symbol == nil { // _todo: cache this
		return "B_" + b.Id
	}

	return *b.Symbol
}
func (b Beam) getCosOnNode(n Node) float64 {
	if b.StartNode.Id == n.Id {
		dX := b.EndNode.x - b.StartNode.x
		return dX / b.getLongitude()
	}

	if b.EndNode.Id == n.Id {
		dX := b.StartNode.x - b.EndNode.x
		return dX / b.getLongitude()
	}

	panic("Node [" + n.getId() + "] is not the start or end of this beam (" + b.Id + ")")
}
func (b Beam) geSinOnNode(n Node) float64 {
	if b.StartNode.Id == n.Id {
		dY := b.EndNode.y - b.StartNode.y
		return dY / b.getLongitude()
	}

	if b.EndNode.Id == n.Id {
		dY := b.StartNode.y - b.EndNode.y
		return dY / b.getLongitude()
	}

	panic("Node [" + n.getId() + "] is not the start or end of this beam (" + b.Id + ")")
}
func (b Beam) getLongitude() float64 {
	dX := b.EndNode.x - b.StartNode.x
	dY := b.EndNode.y - b.StartNode.y
	return math.Sqrt(dX*dX + dY*dY)
}

// ////////////////////
func stringPtr(s string) *string {
	return &s
}

/*
	class Beam implements JsonSerializable
{
    //cached values
    private ?string $symbol = null;
    private ?float $longitude = null;

    public function __construct(
        private Node $startNode,
        private Node $endNode,
        private string $id = '_beamId',
    )
    {
    }

    /**
     * @param array<string,scalar|Node> $data
     * @return self

    public static function constructFromArray(array $data): self
    {
        return new self(
            Node::validateInstance($data['startNode']),
            Node::validateInstance($data['endNode']),
            (string)$data['id'],
        );
    }

    public function jsonSerialize(): array
    {
        $arrayRepresentation = [];
        $arrayRepresentation['id'] = $this->id;
        $arrayRepresentation['startNode'] = $this->startNode->getId();
        $arrayRepresentation['endNode'] = $this->endNode->getId();
        return $arrayRepresentation;
    }

    // Getters

    public function getId(): string
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
            $this->symbol = 'B_' . $this->id;

        return $this->symbol;
    }

    public function getCosOnNode(Node $node): float
    {
        if ($this->startNode->getId() === $node->getId()) {
            $dX = $this->endNode->getX() - $this->startNode->getX();
            return $dX / $this->getLongitude();
        }

        if ($this->endNode->getId() === $node->getId()) {
            $dX = $this->startNode->getX() - $this->endNode->getX();
            return $dX / $this->getLongitude();
        }

        throw new RuntimeException('Node [' . $node->getId() . '] is not the start or end of this beam (' . $this->id . ')');
    }

    public function getSinOnNode(Node $node): float
    {
        if ($this->startNode->getId() === $node->getId()) {
            $dY = $this->endNode->getY() - $this->startNode->getY();
            return $dY / $this->getLongitude();
        }

        if ($this->endNode->getId() === $node->getId()) {
            $dY = $this->startNode->getY() - $this->endNode->getY();
            return $dY / $this->getLongitude();
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
*/
