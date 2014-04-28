<?php

namespace SimpleIT\ApiResourcesBundle\Exercise\ExerciseResource\Sequence;

use JMS\Serializer\Annotation as Serializer;
use SimpleIT\ApiResourcesBundle\Exercise\ExerciseResource\CommonResource;
use SimpleIT\ApiResourcesBundle\Exception\InvalidExerciseResourceException;
use SimpleIT\ApiResourcesBundle\Exercise\ExerciseResource\SequenceResource;
use SimpleIT\ApiResourcesBundle\Exercise\ResourceResource;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class ResourceId
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class ResourceId extends SequenceElement
{
    /**
     * @var int The id of the resource
     * @Serializer\Type("integer")
     * @Serializer\Groups({"details", "resource_storage"})
     * @Assert\NotBlank(groups={"create"})
     */
    private $resourceId;

    /**
     * Set resourceId
     *
     * @param int $resourceId
     */
    public function setResourceId($resourceId)
    {
        $this->resourceId = $resourceId;
    }

    /**
     * Get resourceId
     *
     * @return int
     */
    public function getResourceId()
    {
        return $this->resourceId;
    }

    /**
     * Validate the resource id object
     *
     * @throws \LogicException
     */
    public function  validate($param = null)
    {
        if ($param !== SequenceResource::OBJECTS) {
            throw new InvalidExerciseResourceException('Only a sequence objects can contain object elements');
        }
        if ($this->resourceId === null || $this->resourceId == '') {
            throw new InvalidExerciseResourceException('Invalid resource in sequence');
        }
    }
}
