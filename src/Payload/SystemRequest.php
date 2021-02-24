<?php
declare(strict_types=1);

namespace SMSolver\Payload;


//use SMSolver\Core\ArrayOf;
use JsonSerializable;
use SMSolver\Core\Models\Beam;
use SMSolver\Core\Models\Mappable;
use SMSolver\Core\Models\Node;

class SystemRequest implements Mappable, JsonSerializable
{
    private array $nodes = [];
    private array $beams = [];

    public static function constructFromArray(array $data): self
    {
        $instance = new self();

        foreach ($data['nodes'] as $nodeData) {
            $node = Node::constructFromArray($nodeData);
            $instance->nodes[$node->getId()] = $node;
            unset($node);
        }

        foreach ($data['beams'] as $beamData) {

            $beamDataWithInstances = $beamData;
            $beamDataWithInstances['startNode'] = $instance->nodes[$beamData['startNode']];
            $beamDataWithInstances['endNode'] = $instance->nodes[$beamData['endNode']];

            $beam = Beam::constructFromArray($beamDataWithInstances);
            $instance->beams[$beam->getId()] = $beam;
            unset($node);
        }

        return $instance;
    }

    public function jsonSerialize()
    {
        $arrayRepresentation = [];
        $arrayRepresentation['nodes'] = $this->nodes;
        $arrayRepresentation['beams'] = $this->beams;
        return $arrayRepresentation;
    }
}