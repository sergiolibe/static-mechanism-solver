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
use SMSolver\Core\Models\Mappable;
use SMSolver\Core\Models\Node;
use SMSolver\Utils\OutputInfo;

class SystemRequest implements Mappable, JsonSerializable
{
    /** @var Node[] $nodes */
    private array $nodes = [];

    /** @var Beam[] $beams */
    private array $beams = [];

    /** @var Force[] $forces */
    private array $forces = [];

    private array $referenceSymbolMatrix = [];

    public static function constructFromArray(array $data): self
    {
        $instance = new self();

        foreach ($data['nodes'] as $nodeData) {
            $node = Node::constructFromArray($nodeData);
            $instance->addNode($node);
            unset($node);
        }

        foreach ($data['beams'] as $beamData) {

            $beamDataWithInstances = $beamData;
            $startNode = $instance->getNodeById($beamData['startNode']);
            $beamDataWithInstances['startNode'] = $startNode;

            $endNode = $instance->getNodeById($beamData['endNode']);
            $beamDataWithInstances['endNode'] = $endNode;

            $beam = Beam::constructFromArray($beamDataWithInstances);

            $startNode->addBeam($beam);
            $endNode->addBeam($beam);

            $instance->addBeam($beam);
            unset($beam);
        }

        foreach ($data['forces'] as $forceData) {

            $forceDataWithInstances = $forceData;
            $forceNode = $instance->getNodeById($forceData['node']);
            $forceDataWithInstances['node'] = $forceNode;
            $forceDataWithInstances['type'] = ForceType::from($forceData['type']);

            $force = Force::constructFromArray($forceDataWithInstances);

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

    public function generateMatrix(): array
    {
        $n = $this->calcN();

        $this->buildReferenceSymbolMatrix();

        $c = count($this->referenceSymbolMatrix);

        $Ax = VectorUtils::zerosMatrix($c - 1, $c);

        $i=0;
        foreach ($this->nodes as $node) {

            OutputInfo::echoln('using node ' . $node->getId() . ' with $i: '.$i);
            //X for $node
            $valuesBySymbol = $node->getValuesBySymbolByAxis(Axis::X());
            foreach ($valuesBySymbol as $symbol => $value) {
                $Ax[$i][$this->referenceSymbolMatrix[$symbol]] = $value;
            }

            //Y for $node
            $valuesBySymbol = $node->getValuesBySymbolByAxis(Axis::Y());
            foreach ($valuesBySymbol as $symbol => $value) {
                $Ax[$i+1][$this->referenceSymbolMatrix[$symbol]] = $value;
            }
            $i+=2;
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
        foreach ($this->beams as $beam)
            $this->referenceSymbolMatrix[] = $beam->getSymbol();

        foreach ($this->nodes as $node)
            if ($node->hasSymbols())
                foreach ($node->getSymbols() as $symbol)
                    $this->referenceSymbolMatrix[] = $symbol;

        foreach ($this->forces as $force)
            if ($force->hasSymbol())
                $this->referenceSymbolMatrix[] = $force->getSymbol();

        $this->referenceSymbolMatrix[] = 'R';

        $tempReferenceSymbolMatrix = [];
        foreach ($this->referenceSymbolMatrix as $intIndex => $symbol)
            $tempReferenceSymbolMatrix[$symbol] = $intIndex;

        $this->referenceSymbolMatrix = $tempReferenceSymbolMatrix;
    }

    private function buildBaseMatrix(): array
    {

    }

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
}