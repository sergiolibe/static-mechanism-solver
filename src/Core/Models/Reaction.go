package Models

import "math"

type Reaction struct {
	referenceId  string
	magnitude    float64
	angle        float64
	radAngle     float64
	reactionType ReactionType
	symbol       string
}

func ConstructFromNode(n Node) []Reaction {
	var reactionTypes []ReactionType
	if n.nodeType == U1U2 {
		reactionTypes = append(reactionTypes, R_U1, R_U2)
	} else if n.nodeType == U1 {
		reactionTypes = append(reactionTypes, R_U1)
	} else if n.nodeType == U2 {
		reactionTypes = append(reactionTypes, R_U2)
	} /*else {
		reactionTypes = []ReactionType{}
	}*/
	var reactions []Reaction
	for _, rt := range reactionTypes {
		r := Reaction{}
		r.referenceId = n.Id
		r.reactionType = rt
		if rt == R_U1 {
			r.symbol = n.GetU1Symbol()
			r.setAngle(0)
		} else {
			r.symbol = n.GetU2Symbol()
			r.setAngle(90)
		}
		reactions = append(reactions, r)
	}

	return reactions
}

func ConstructFromBeam(b Beam) Reaction {
	r := Reaction{}
	r.referenceId = b.Id
	r.reactionType = R_Beam
	r.setAngle(0)
	r.symbol = b.GetSymbol()
	return r
}
func ConstructFromForce(f Force) Reaction {
	r := Reaction{}
	r.referenceId = f.Id
	r.reactionType = R_Force
	r.setAngle(0)
	r.symbol = f.GetSymbol()
	return r
}

func ResultReaction() Reaction {
	r := Reaction{}
	r.referenceId = "nan"
	r.reactionType = R_Result
	r.setAngle(0)
	r.symbol = string(r.reactionType)
	return r
}

func (r *Reaction) setAngle(angle float64) {
	r.angle = angle
	r.radAngle = angle * math.Pi / 180
}

/*<?php
declare(strict_types=1);

namespace SMSolver\Core\Models;

use JsonSerializable;

class Reaction implements JsonSerializable
{
    private string $referenceId = 'nan';
    private float $magnitude = -1e3;
    private float $angle = -1e3;
    private float $radAngle = -1e3;
    private ReactionType $type;
    private string $symbol = 'noSymbol';

    private function __construct()
    {
    }

    /**
     * @param Node $node
     * @return self[]

    public static function constructFromNode(Node $node): array
    {
        /** @var ReactionType[] $reactionTypes
        $reactionTypes = match ($node->getType()->getValue()) {
            NodeType::U1U2()->getValue() => [ReactionType::U1(), ReactionType::U2(),],
            NodeType::U1()->getValue() => [ReactionType::U1()],
            NodeType::U2()->getValue() => [ReactionType::U2()],
            default =>[]
        };

        $reactions = [];
        foreach ($reactionTypes as $reactionType) {
            $instance = new self();
            $instance->referenceId = $node->getId();
            $instance->type = $reactionType;
            if (ReactionType::U1()->equals($reactionType)) {
                $instance->symbol = $node->getU1Symbol();
                $instance->setAngle(0);
            } else {
                $instance->symbol = $node->getU2Symbol();
                $instance->setAngle(90);
            }

            $reactions[] = $instance;
        }

        return $reactions;
    }

    public static function constructFromBeam(Beam $beam): self
    {
        $instance = new self();
        $instance->referenceId = $beam->getId();
        $instance->type = ReactionType::BEAM();
        $instance->setAngle(0.0000);
        $instance->symbol = $beam->getSymbol();

        return $instance;
    }

    public static function constructFromForce(Force $force): self
    {
        $instance = new self();
        $instance->referenceId = $force->getId();
        $instance->type = ReactionType::FORCE();
        $instance->setAngle($force->getAngle());
        $instance->symbol = $force->getSymbol();

        return $instance;
    }

    public static function resultReaction(): self
    {
        $instance = new self();
        $instance->referenceId = 'nan';
        $instance->type = ReactionType::RESULT();
        $instance->setAngle(0);
        $instance->symbol = $instance->type->getValue();

        return $instance;
    }

    public function getSymbol(): string
    {
        return $this->symbol;
    }

    // Adders
    // Getters


    public function getType(): ?ReactionType
    {
        return $this->type;
    }


    public function getMagnitude(): float
    {
        return $this->magnitude;
    }

    public function getCos(): float
    {
        return cos($this->radAngle);
    }

    public function getSin(): float
    {
        return sin($this->radAngle);
    }

    private function toRadians(float $degrees): float
    {
        return $degrees * M_PI / 180;
    }

    public function setMagnitude(float $magnitude): void
    {
        $this->magnitude = $magnitude;
    }

    public function setAngle(float $angle): void
    {
        $this->angle = $angle;
        $this->radAngle = $this->toRadians($this->angle);
    }


    // Interfaces

    public function jsonSerialize(): array
    {
        $arrayRepresentation = [];
        $arrayRepresentation['symbol'] = $this->symbol;
        $arrayRepresentation['referenceId'] = $this->referenceId;
        $arrayRepresentation['type'] = $this->type;
        $arrayRepresentation['angle'] = $this->angle;
        $arrayRepresentation['radAngle'] = round($this->radAngle,3);
        $arrayRepresentation['magnitude'] = $this->magnitude;
        $arrayRepresentation['cos'] = round($this->getCos(),3);
        $arrayRepresentation['sin'] = round($this->getSin(),3);
        return $arrayRepresentation;
    }

    public function __toString()
    {
        return
            'Reaction'
            . ' [#' . $this->symbol . ']'
            . ' (' . $this->referenceId . ')'
            . ' {' . $this->type . '}'
            . ' ' . $this->magnitude . 'N'
            . '@' . $this->angle . '° (' . $this->radAngle . ' rad)'
            ;
    }
}*/
