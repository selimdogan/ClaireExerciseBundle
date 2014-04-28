<?php
namespace SimpleIT\ApiResourcesBundle\Exercise;

use JMS\Serializer\Annotation as Serializer;
use SimpleIT\ApiResourcesBundle\Exercise\DomainKnowledge\CommonKnowledge;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class OwnerKnowledgeResource
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class OwnerKnowledgeResource
{
    /**
     * @const RESOURCE_NAME = 'Owner Knowledge'
     */
    const RESOURCE_NAME = 'Owner Knowledge';

    /**
     * @const METADATA_IS_RESOURCE_PREFIX = "__";
     */
    const METADATA_IS_RESOURCE_PREFIX = "__";

    /**
     * @var int
     * @Serializer\Type("integer")
     * @Serializer\Groups({"details", "list", "owner_knowledge", "owner_knowledge_list"})
     * @Assert\Blank(groups={"create","edit"})
     */
    private $id;

    /**
     * @var int
     * @Serializer\Type("integer")
     * @Serializer\Groups({"details", "owner_knowledge", "owner_knowledge_list"})
     * @Assert\Blank(groups={"create","edit"})
     */
    private $knowledge;

    /**
     * @var int
     * @Serializer\Type("integer")
     * @Serializer\Groups({"details", "list", "owner_knowledge", "owner_knowledge_list"})
     * @Assert\Blank(groups={"create","edit"})
     */
    private $owner;

    /**
     * @var bool
     * @Serializer\Type("boolean")
     * @Serializer\Groups({"details", "list", "owner_knowledge", "owner_knowledge_list"})
     * @Assert\NotNull(groups={"create","edit"})
     */
    private $public;

    /**
     * @var array
     * @Serializer\Type("array")
     * @Serializer\Groups({"details", "owner_knowledge", "owner_knowledge_list"})
     * @Assert\NotNull(groups={"create"})
     * @Assert\Null(groups={"edit"})
     */
    private $metadata;

    /**
     * @var string $type
     * @Serializer\Type("string")
     * @Serializer\Groups({"details", "list", "owner_knowledge", "owner_knowledge_list"})
     * @Assert\Blank()
     */
    private $type;

    /**
     * @var CommonKnowledge $content
     * @Serializer\Type("SimpleIT\ApiResourcesBundle\Exercise\DomainKnowledge\CommonKnowledge")
     * @Serializer\Groups({"details", "owner_knowledge_list"})
     * @Assert\Blank()
     * @Assert\Valid
     */
    private $content;

    /**
     * Set content
     *
     * @param \SimpleIT\ApiResourcesBundle\Exercise\DomainKnowledge\CommonKnowledge $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * Get content
     *
     * @return \SimpleIT\ApiResourcesBundle\Exercise\DomainKnowledge\CommonKnowledge
     */
    public function getContent()
    {
        return $this->content;
    }

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
     * Set knowledge
     *
     * @param int $knowledge
     */
    public function setKnowledge($knowledge)
    {
        $this->knowledge = $knowledge;
    }

    /**
     * Get knowledge
     *
     * @return int
     */
    public function getKnowledge()
    {
        return $this->knowledge;
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
