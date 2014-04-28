<?php
namespace SimpleIT\ApiResourcesBundle\Exercise;

use JMS\Serializer\Annotation as Serializer;
use SimpleIT\ApiResourcesBundle\Exercise\ExerciseResource\CommonResource;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class OwnerResourceResource
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class OwnerResourceResource
{
    /**
     * @const RESOURCE_NAME = 'Owner Resource'
     */
    const RESOURCE_NAME = 'Owner Resource';

    /**
     * @const METADATA_IS_RESOURCE_PREFIX = "__";
     */
    const METADATA_IS_RESOURCE_PREFIX = "__";

    /**
     * @var int
     * @Serializer\Type("integer")
     * @Serializer\Groups({"details", "list", "owner_resource", "owner_resource_list"})
     * @Assert\Blank(groups={"create","edit"})
     */
    private $id;

    /**
     * @var int
     * @Serializer\Type("integer")
     * @Serializer\Groups({"details", "owner_resource", "owner_resource_list"})
     * @Assert\Blank(groups={"create","edit"})
     */
    private $resource;

    /**
     * @var int
     * @Serializer\Type("integer")
     * @Serializer\Groups({"details", "list", "owner_resource", "owner_resource_list"})
     * @Assert\Blank(groups={"create","edit"})
     */
    private $owner;

    /**
     * @var bool
     * @Serializer\Type("boolean")
     * @Serializer\Groups({"details", "list", "owner_resource", "owner_resource_list"})
     * @Assert\NotNull(groups={"create","edit"})
     */
    private $public;

    /**
     * @var array
     * @Serializer\Type("array")
     * @Serializer\Groups({"details", "owner_resource", "owner_resource_list"})
     * @Assert\NotNull(groups={"create"})
     * @Assert\Null(groups={"edit"})
     */
    private $metadata;

    /**
     * @var string $type
     * @Serializer\Type("string")
     * @Serializer\Groups({"details", "list", "owner_resource_list", "owner_resource"})
     * @Assert\Blank()
     */
    private $type;

    /**
     * @var CommonResource $content
     * @Serializer\Type("SimpleIT\ApiResourcesBundle\Exercise\ExerciseResource\CommonResource")
     * @Serializer\Groups({"details", "owner_resource_list"})
     * @Assert\Blank()
     * @Assert\Valid
     */
    private $content;

    /**
     * Set id
     *
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set metadata
     *
     * @param array $metadata
     */
    public function setMetadata($metadata)
    {
        $this->metadata = $metadata;
    }

    /**
     * Get metadata
     *
     * @return array
     */
    public function getMetadata()
    {
        return $this->metadata;
    }

    /**
     * Set owner
     *
     * @param int $owner
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;
    }

    /**
     * Get owner
     *
     * @return int
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Set public
     *
     * @param boolean $public
     */
    public function setPublic($public)
    {
        $this->public = $public;
    }

    /**
     * Get public
     *
     * @return boolean
     */
    public function getPublic()
    {
        return $this->public;
    }

    /**
     * Set resource
     *
     * @param int $resource
     */
    public function setResource($resource)
    {
        $this->resource = $resource;
    }

    /**
     * Get resource
     *
     * @return int
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * Set content
     *
     * @param \SimpleIT\ApiResourcesBundle\Exercise\ExerciseResource\CommonResource $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * Get content
     *
     * @return \SimpleIT\ApiResourcesBundle\Exercise\ExerciseResource\CommonResource
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set type
     *
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
}
