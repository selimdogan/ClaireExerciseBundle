<?php
namespace SimpleIT\ApiResourcesBundle\Exercise;

use JMS\Serializer\Annotation as Serializer;
use SimpleIT\ApiResourcesBundle\Exercise\ExerciseModel\Common\CommonModel;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class OwnerExerciseModelResource
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class OwnerExerciseModelResource
{
    /**
     * @const RESOURCE_NAME = 'Owner Exercise Model'
     */
    const RESOURCE_NAME = 'Owner Exercise Model';

    /**
     * @var int $id
     * @Serializer\Type("integer")
     * @Serializer\Groups({"details", "list", "oem", "owner_exercise_model_list"})
     * @Assert\Blank(groups={"create", "edit", "editPublic"})
     */
    private $id;

    /**
     * @var int $exerciseModel
     * @Serializer\Type("integer")
     * @Serializer\Groups({"details", "list", "oem", "owner_exercise_model_list"})
     * @Assert\Blank(groups={"create", "editPublic"})
     */
    private $exerciseModel;

    /**
     * @var int $owner
     * @Serializer\Type("integer")
     * @Serializer\Groups({"details", "oem", "list", "owner_exercise_model_list"})
     * @Assert\Blank(groups={"create", "edit", "editPublic"})
     */
    private $owner;

    /**
     * @var bool $public
     * @Serializer\Type("boolean")
     * @Serializer\Groups({"details", "oem", "list", "owner_exercise_model_list"})
     * @Assert\NotNull(groups={"create", "editPublic"})
     */
    private $public;

    /**
     * @var array
     * @Serializer\Type("array")
     * @Serializer\Groups({"details", "oem", "owner_exercise_model_list"})
     * @Assert\NotNull(groups={"create"})
     * @Assert\Null(groups={"edit"})
     */
    private $metadata;

    /**
     * @var string $type
     * @Serializer\Type("string")
     * @Serializer\Groups({"details", "oem", "list", "owner_exercise_model_list"})
     * @Assert\Blank(groups={"create", "edit", "editPublic"})
     */
    private $type;

    /**
     * @var string $title
     * @Serializer\Type("string")
     * @Serializer\Groups({"details", "oem", "list", "owner_exercise_model_list"})
     * @Assert\Blank(groups={"create", "edit", "editPublic"})
     */
    private $title;

    /**
     * @var boolean $draft
     * @Serializer\Type("boolean")
     * @Serializer\Groups({"details", "oem", "list", "owner_exercise_model_list"})
     * @Assert\Blank(groups={"create", "edit", "editPublic"})
     */
    private $draft;

    /**
     * @var boolean $complete
     * @Serializer\Type("boolean")
     * @Serializer\Groups({"details", "oem", "list", "owner_exercise_model_list"})
     * @Assert\Blank(groups={"create", "edit", "editPublic"})
     */
    private $complete;

    /**
     * @var CommonModel $content
     * @Serializer\Type("SimpleIT\ApiResourcesBundle\Exercise\ExerciseModel\Common\CommonModel")
     * @Serializer\Groups({"details", "owner_exercise_model_list"})
     * @Assert\Blank(groups={"create", "edit", "editPublic"})
     * @Assert\Valid
     */
    private $content;

    /**
     * Set content
     *
     * @param \SimpleIT\ApiResourcesBundle\Exercise\ExerciseModel\Common\CommonModel $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * Get content
     *
     * @return \SimpleIT\ApiResourcesBundle\Exercise\ExerciseModel\Common\CommonModel
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set exerciseModel
     *
     * @param int $exerciseModel
     */
    public function setExerciseModel($exerciseModel)
    {
        $this->exerciseModel = $exerciseModel;
    }

    /**
     * Get exerciseModel
     *
     * @return int
     */
    public function getExerciseModel()
    {
        return $this->exerciseModel;
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

    /**
     * Set title
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set complete
     *
     * @param boolean $complete
     */
    public function setComplete($complete)
    {
        $this->complete = $complete;
    }

    /**
     * Get complete
     *
     * @return boolean
     */
    public function getComplete()
    {
        return $this->complete;
    }

    /**
     * Set draft
     *
     * @param boolean $draft
     */
    public function setDraft($draft)
    {
        $this->draft = $draft;
    }

    /**
     * Get draft
     *
     * @return boolean
     */
    public function getDraft()
    {
        return $this->draft;
    }
}
