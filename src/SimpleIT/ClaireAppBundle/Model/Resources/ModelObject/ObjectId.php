<?php

namespace SimpleIT\ApiResourcesBundle\Exercise\ModelObject;

use JMS\Serializer\Annotation as Serializer;

/**
 * Object that corresponds to a resource that is designated by its id. ObjectId
 * is used in the exerciseModel, before the resource is retrieved.
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class ObjectId
{
    /**
     * @var int $id
     * @Serializer\Type("integer")
     * @Serializer\Groups({"details", "exercise_model_storage"})
     */
    private $id;

    /** Get Id
     *
     * @return int Id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set Id
     *
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }
}
