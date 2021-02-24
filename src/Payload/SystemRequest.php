<?php
declare(strict_types=1);

namespace SMSolver\Payload;


//use SMSolver\Core\ArrayOf;
use RuntimeException;
use JsonSerializable;
use SMSolver\Core\Models\Beam;
use SMSolver\Core\Models\Force;
use SMSolver\Core\Models\Mappable;
use SMSolver\Core\Models\Node;

class SystemRequest implements Mappable, JsonSerializable
{
    /** @var Node[] $nodes */
    private array $nodes = [];

    /** @var Beam[] $beams */
    private array $beams = [];

    /** @var Force[] $forces */
    private array $forces = [];

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
}