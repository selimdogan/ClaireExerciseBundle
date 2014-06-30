<?php

namespace SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseModel\Common;

use JMS\Serializer\Annotation as Serializer;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ModelObject\ObjectConstraints;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ModelObject\ObjectId;

/**
 * Block of resources in a model
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
abstract class ResourceBlock
{
    /**
     * @var int $numberOfOccurrences The number of occurrences to generate in the block
     * @Serializer\Type("integer")
     * @Serializer\Groups({"details", "exercise_model_storage"})
     */
    protected $numberOfOccurrences;

    /**
     * @var array $resources An array of ObjectId
     * @Serializer\Type("array<SimpleIT\ClaireExerciseBundle\Model\Resources\ModelObject\ObjectId>")
     * @Serializer\Groups({"details", "exercise_model_storage"})
     */
    protected $resources = array();

    /**
     * The object constraints. If it is null, the Block contains a
     * list of ObjectId
     *
     * @var ObjectConstraints $resourceConstraint
     * @Serializer\Type("SimpleIT\ClaireExerciseBundle\Model\Resources\ModelObject\ObjectConstraints")
     * @Serializer\Groups({"details", "exercise_model_storage"})
     */
    protected $resourceConstraint = null;

    /**
     * @var bool $isList
     * @Serializer\Type("boolean")
     * @Serializer\Groups({"details", "exercise_model_storage"})
     */
    protected $isList;

    /**
     * Test if the block is a list of designated resources or a constraint to
     * find resources
     *
     * @return boolean True if it is a list, false if it is a constraint
     */
    public function isList()
    {
        return $this->isList;
    }

    /**
     * Set is_list
     *
     * @param mixed $is_list
     */
    public function setIsList($is_list)
    {
        $this->isList = $is_list;
    }

    /**
     * Get is_list
     *
     * @return mixed
     */
    public function getIsList()
    {
        return $this->isList;
    }

    /**
     * Get the number of occurrences
     *
     * @return int
     */
    public function getNumberOfOccurrences()
    {
        return $this->numberOfOccurrences;
    }

    /**
     * Set the number of occurrences
     *
     * @param int $numberOfOccurrences
     */
    public function setNumberOfOccurrences($numberOfOccurrences)
    {
        $this->numberOfOccurrences = $numberOfOccurrences;
    }

    /**
     * Get the list of resources of the block
     *
     * @return array An array of ObjectId
     */
    public function getResources()
    {
        return $this->resources;
    }

    /**
     * Set the list of resources of the block. Sets the Block in "list" mode.
     * The constraint is switched to null.
     *
     * @param array $resources An array of ObjectId
     */
    public function setResources($resources)
    {
        $this->resources = $resources;
    }

    /**
     * Add resource to the list of the block. Sets the Block in "list" mode.
     * The constaint is switched to null.
     *
     * @param ObjectId $resource
     */
    public function addResource(ObjectId $resource)
    {
        $this->resources[] = $resource;
    }

    /**
     * Get the resource constraints
     *
     * @return ObjectConstraints
     */
    public function getResourceConstraint()
    {
        return $this->resourceConstraint;
    }

    /**
     * Set the resource constraints. Sets the block in "constraints" mode and
     * clears the list of exeisting resources.
     *
     * @param ObjectConstraints $resourceConstraint
     */
    public function setResourceConstraint(ObjectConstraints $resourceConstraint)
    {
        $this->resourceConstraint = $resourceConstraint;
    }
}
