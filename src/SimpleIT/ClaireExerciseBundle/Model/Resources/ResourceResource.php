<?php
namespace SimpleIT\ClaireExerciseBundle\Model\Resources;

use JMS\Serializer\Annotation as Serializer;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseResource\CommonResource;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class ResourceResource
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class ResourceResource
{
    /**
     * @const RESOURCE_NAME = 'Exercise Resource'
     */
    const RESOURCE_NAME = 'Exercise Resource';

    /**
     * @const MULTIPLE_CHOICE_QUESTION = 'SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseResource\MultipleChoiceQuestionResource'
     */
    const MULTIPLE_CHOICE_QUESTION_CLASS = 'SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseResource\MultipleChoiceQuestionResource';

    /**
     * @const PICTURE = 'SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseResource\PictureResource'
     */
    const PICTURE_CLASS = 'SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseResource\PictureResource';

    /**
     * @const SEQUENCE = 'SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseResource\SequenceResource'
     */
    const SEQUENCE_CLASS = 'SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseResource\SequenceResource';

    /**
     * @const OPEN_ENDED_QUESTION = 'SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseResource\OpenEndedQuestionResource'
     */
    const OPEN_ENDED_QUESTION = 'SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseResource\OpenEndedQuestionResource';

    /**
     * @const TEXT = 'SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseResource\TextResource'
     */
    const TEXT_CLASS = 'SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseResource\TextResource';

    /**
     * @var int $id Id of resource
     * @Serializer\Type("integer")
     * @Serializer\Groups({"details", "list", "resource_list"})
     * @Assert\Blank(groups={"create","edit"})
     */
    private $id;

    /**
     * @var string $type
     * @Serializer\Type("string")
     * @Serializer\Groups({"details", "list", "resource_list"})
     * @Assert\NotBlank(groups={"create"})
     * @Assert\Blank(groups={"edit"})
     */
    private $type;

    /**
     * @var CommonResource $content
     * @Serializer\Type("SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseResource\CommonResource")
     * @Serializer\Groups({"details"})
     * @Assert\NotBlank(groups={"create"})
     * @Assert\Valid
     */
    private $content;

    /**
     * @var array $requiredExerciseResources
     * @Serializer\Type("array")
     * @Serializer\Groups({"details"})
     * @Assert\NotNull(groups={"create"})
     */
    private $requiredExerciseResources;

    /**
     * @var array $requiredKnowledges
     * @Serializer\Type("array")
     * @Serializer\Groups({"details"})
     * @Assert\NotNull(groups={"create"})
     */
    private $requiredKnowledges;

    /**
     * @var int
     * @Serializer\Type("integer")
     * @Serializer\Groups({"details", "list", "resource_list"})
     * @Assert\Blank(groups={"create", "edit"})
     */
    private $author;

    /**
     * @var int $owner
     * @Serializer\Type("integer")
     * @Serializer\Groups({"details", "list", "resource_list"})
     * @Assert\Blank(groups={"create", "edit"})
     */
    private $owner;

    /**
     * @var bool $public
     * @Serializer\Type("boolean")
     * @Serializer\Groups({"details","list", "resource_list"})
     * @Assert\NotNull(groups={"create"})
     */
    private $public;

    /**
     * @var bool $archived
     * @Serializer\Type("boolean")
     * @Serializer\Groups({"details","list", "resource_list"})
     * @Assert\Null(groups={"create"})
     */
    private $archived;

    /**
     * @var array
     * @Serializer\Type("array")
     * @Serializer\Groups({"details"})
     */
    private $metadata;

    /**
     * @var int
     * @Serializer\Type("integer")
     * @Serializer\Groups({"details"})
     */
    private $parent;

    /**
     * @var int
     * @Serializer\Type("integer")
     * @Serializer\Groups({"details", "resource_list"})
     */
    private $forkFrom;

    /**
     * Set content
     *
     * @param \SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseResource\CommonResource $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * Get content
     *
     * @return \SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseResource\CommonResource
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
     * Set requiredExerciseResources
     *
     * @param array $requiredExerciseResources
     */
    public function setRequiredExerciseResources($requiredExerciseResources)
    {
        $this->requiredExerciseResources = $requiredExerciseResources;
    }

    /**
     * Get requiredExerciseResources
     *
     * @return array
     */
    public function getRequiredExerciseResources()
    {
        return $this->requiredExerciseResources;
    }

    /**
     * Set author
     *
     * @param int $author
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    }

    /**
     * Get author
     *
     * @return int
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set archived
     *
     * @param boolean $archived
     */
    public function setArchived($archived)
    {
        $this->archived = $archived;
    }

    /**
     * Get archived
     *
     * @return boolean
     */
    public function getArchived()
    {
        return $this->archived;
    }

    /**
     * Set forkFrom
     *
     * @param int $forkFrom
     */
    public function setForkFrom($forkFrom)
    {
        $this->forkFrom = $forkFrom;
    }

    /**
     * Get forkFrom
     *
     * @return int
     */
    public function getForkFrom()
    {
        return $this->forkFrom;
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
     * Set parent
     *
     * @param int $parent
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
    }

    /**
     * Get parent
     *
     * @return int
     */
    public function getParent()
    {
        return $this->parent;
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
     * Set requiredKnowledges
     *
     * @param array $requiredKnowledges
     */
    public function setRequiredKnowledges($requiredKnowledges)
    {
        $this->requiredKnowledges = $requiredKnowledges;
    }

    /**
     * Get requiredKnowledges
     *
     * @return array
     */
    public function getRequiredKnowledges()
    {
        return $this->requiredKnowledges;
    }

    /**
     * Return the item serialization class corresponding to the type
     *
     * @param string $type
     *
     * @return string
     * @throws \LogicException
     */
    static public function getClass($type)
    {
        switch ($type) {
            case CommonResource::MULTIPLE_CHOICE_QUESTION:
                $class = self::MULTIPLE_CHOICE_QUESTION_CLASS;
                break;
            case CommonResource::PICTURE:
                $class = self::PICTURE_CLASS;
                break;
            case CommonResource::SEQUENCE:
                $class = self::SEQUENCE_CLASS;
                break;
            case CommonResource::TEXT:
                $class = self::TEXT_CLASS;
                break;
            case CommonResource::OPEN_ENDED_QUESTION:
                $class = self::OPEN_ENDED_QUESTION;
                break;
            default:
                throw new \LogicException('Unknown type');
        }

        return $class;
    }
}
