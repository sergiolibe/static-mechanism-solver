<?php
declare(strict_types=1);

namespace SMSolver\Payload;


//use SMSolver\Core\ArrayOf;
use RuntimeException;
use JsonSerializable;
use SMSolver\Core\Axis;
use SMSolver\Core\Math\VectorUtils;
use SMSolver\Core\Models\Beam;
use SMSolver\Core\Models\Force;
use SMSolver\Core\Models\ForceType;
use SMSolver\Core\Models\Node;
use SMSolver\Core\Models\NodeType;
use SMSolver\Core\Models\Reaction;
use SMSolver\Core\Models\ReactionType;
use SMSolver\Utils\OutputInfo;

class SystemRequest implements JsonSerializable
{
    /** @var Node[] $nodes */
    private array $nodes = [];

    /** @var Beam[] $beams */
    private array $beams = [];

    /** @var Force[] $forces */
    private array $forces = [];

    /** @var Reaction[] $reactions */
    private array $reactions = [];

    private array $referenceSymbolMatrix = [];

    /**
     * @param array<string,array<array<string,scalar>>> $data
     * @return self
     */
    public static function constructFromArray(array $data): self
    {
        $instance = new self();

        foreach ($data['nodes'] as $nodeData) {
            $node = Node::constructFromArray($nodeData);
            $instance->addNode($node);
            unset($node);
        }

        foreach ($data['beams'] as $beamData) {

            $startNode = $instance->getNodeById((string)$beamData['startNode']);
            $beamData['_startNode'] = $beamData['startNode'];
            $beamData['startNode'] = $startNode;

            $endNode = $instance->getNodeById((string)$beamData['endNode']);
            $beamData['_endNode'] = $beamData['endNode'];
            $beamData['endNode'] = $endNode;

            $beam = Beam::constructFromArray($beamData);

            $startNode->addBeam($beam);
            $endNode->addBeam($beam);

            $instance->addBeam($beam);
            unset($beam);
        }

        foreach ($data['forces'] as $forceData) {

            $forceNode = $instance->getNodeById((string)$forceData['node']);
            $forceData['_node'] = $forceData['node'];
            $forceData['node'] = $forceNode;

            $force = Force::constructFromArray($forceData);

            $forceNode->addForce($force);

            $instance->addForce($force);
            unset($force);
        }

        return $instance;
    }

    public function jsonSerialize()
    {
        $arrayRepresentation = [];
        $arrayRepresentation['nodes'] = $this->nodes;
        $arrayRepresentation['beams'] = $this->beams;
        $arrayRepresentation['forces'] = $this->forces;
        return $arrayRepresentation;
    }

    // Getters

    public function getNodeById(string $nodeId): Node
    {
        return $this->nodes[$nodeId] ?? throw new RuntimeException('Node not found by id: ' . $nodeId);
    }

    public function getBeamById(string $beamId): Beam
    {
        return $this->beams[$beamId] ?? throw new RuntimeException('Beam not found by id: ' . $beamId);
    }

    public function getForceById(string $forceId): Force
    {
        return $this->forces[$forceId] ?? throw new RuntimeException('Force not found by id: ' . $forceId);
    }


    // Adders

    public function addNode(Node $node): void
    {
        $this->nodes[$node->getId()] = $node;
    }

    public function addBeam(Beam $beam): void
    {
        $this->beams[$beam->getId()] = $beam;
    }

    public function addForce(Force $force): void
    {
        $this->forces[$force->getId()] = $force;
    }

    /**
     * @return float[][]
     */
    public function generateMatrix(): array
    {
        $n = $this->calcN();

        $this->buildReferenceSymbolMatrix();

//        OutputInfo::printJSONln($this->referenceSymbolMatrix);
//        die();
        $c = count($this->referenceSymbolMatrix);

        $Ax = VectorUtils::zerosMatrix($c - 1, $c);

        $i = 0;
        foreach ($this->nodes as $node) {

//            OutputInfo::echoln('using node ' . $node->getId() . ' with $i: '.$i);
            //X for $node
            $valuesBySymbol = $node->getValuesBySymbolByAxis(Axis::X());
            foreach ($valuesBySymbol as $symbol => $value) {
                $Ax[$i][$this->referenceSymbolMatrix[$symbol]] = $value;
            }

            //Y for $node
            $valuesBySymbol = $node->getValuesBySymbolByAxis(Axis::Y());
            foreach ($valuesBySymbol as $symbol => $value) {
                $Ax[$i + 1][$this->referenceSymbolMatrix[$symbol]] = $value;
            }
            $i += 2;
        }
//        echo json_encode($Ax);
//        die();
//
//        var_dump($valuesBySymbol);
//        die();
//        var_dump($this->referenceSymbolMatrix);

        return $Ax;
    }

    private function buildReferenceSymbolMatrix(): void
    {

        // build $reactions array from nodes, beams and incognito force
        // then build referenceSymbolMatrix from this array

        if (empty($this->reactions)) {
            foreach ($this->beams as $beam)
                $this->reactions[] = Reaction::constructFromBeam($beam);

            foreach ($this->nodes as $node)
                foreach (Reaction::constructFromNode($node) as $reaction)
                    $this->reactions[] = $reaction;

            foreach ($this->forces as $force)
                if (ForceType::UNKNOWN()->equals($force->getType()))
                    $this->reactions[] = Reaction::constructFromForce($force);

            $this->reactions[] = Reaction::resultReaction();
        }

        foreach ($this->reactions as $intIndex => $reaction)
            $this->referenceSymbolMatrix[$reaction->getSymbol()] = $intIndex;
    }

//    private function buildBaseMatrix(): array
//    {
//
//    }

    private function calcN(): int
    {
        $n = 0;

        foreach ($this->nodes as $node)
            $n += $node->getN();

        return $n;
    }

    public function getReferenceSymbolMatrix(): array
    {
        return $this->referenceSymbolMatrix;
    }

    /**
     * @param array $result
     * @return Reaction[]
     */
    public function mapReactionsWithResults(array $result): array
    {
        $reactions = array_filter($this->reactions,
            fn(Reaction $reaction) => !ReactionType::RESULT()->equals($reaction->getType())
        );
        foreach ($reactions as $intIndex => $reaction)
            $reaction->setMagnitude($result[$intIndex]);

        return $reactions;
    }
}