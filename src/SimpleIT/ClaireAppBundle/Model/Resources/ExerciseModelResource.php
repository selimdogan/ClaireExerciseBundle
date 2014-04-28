<?php
namespace SimpleIT\ApiResourcesBundle\Exercise;

use JMS\Serializer\Annotation as Serializer;
use SimpleIT\ApiResourcesBundle\Exercise\Exercise\Common\CommonExercise;
use SimpleIT\ApiResourcesBundle\Exercise\ExerciseModel\Common\CommonModel;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class ExerciseModelResource
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class ExerciseModelResource
{
    /**
     * @const RESOURCE_NAME = 'Exercise Model'
     */
    const RESOURCE_NAME = 'Exercise Model';

    /**
     * @const MULTIPLE_CHOICE_MODEL_CLASS = 'SimpleIT\ApiResourcesBundle\Exercise\ExerciseModel\MultipleChoice\Model'
     */
    const MULTIPLE_CHOICE_MODEL_CLASS = 'SimpleIT\ApiResourcesBundle\Exercise\ExerciseModel\MultipleChoice\Model';

    /**
     * @const GROUP_ITEMS_MODEL_CLASS = 'SimpleIT\ApiResourcesBundle\Exercise\ExerciseModel\GroupItems\Model'
     */
    const GROUP_ITEMS_MODEL_CLASS = 'SimpleIT\ApiResourcesBundle\Exercise\ExerciseModel\GroupItems\Model';

    /**
     * @const ORDER_ITEMS_MODEL_CLASS = 'SimpleIT\ApiResourcesBundle\Exercise\ExerciseModel\OrderItems\Model'
     */
    const ORDER_ITEMS_MODEL_CLASS = 'SimpleIT\ApiResourcesBundle\Exercise\ExerciseModel\OrderItems\Model';

    /**
     * @const PAIR_ITEMS_MODEL_CLASS = 'SimpleIT\ApiResourcesBundle\Exercise\ExerciseModel\PairItems\Model'
     */
    const PAIR_ITEMS_MODEL_CLASS = 'SimpleIT\ApiResourcesBundle\Exercise\ExerciseModel\PairItems\Model';

    /**
     * @const OPEN_ENDED_QUESTION_CLASS = 'SimpleIT\ApiResourcesBundle\Exercise\ExerciseModel\OpenEndedQuestion\Model'
     */
    const OPEN_ENDED_QUESTION_CLASS = 'SimpleIT\ApiResourcesBundle\Exercise\ExerciseModel\OpenEndedQuestion\Model';

    /**
     * @var int $id Id of exercise model
     * @Serializer\Type("integer")
     * @Serializer\Groups({"details", "list", "exercise", "oem"})
     * @Assert\Blank(groups={"create", "editType","edit","editContent", "editTitle", "appCreate","editDraft"})
     */
    private $id;

    /**
     * @var string $type
     * @Serializer\Type("string")
     * @Serializer\Groups({"details", "list", "exercise", "oem"})
     * @Assert\NotBlank(groups={"create", "editType", "appCreate"})
     * @Assert\Blank(groups={"editContent", "editTitle", "editDraft"})
     */
    private $type;

    /**
     * @var string $title
     * @Serializer\Type("string")
     * @Serializer\Groups({"details", "list", "exercise", "oem"})
     * @Assert\NotBlank(groups={"create", "editTitle"})
     * @Assert\Blank(groups={"editContent", "editType", "appCreate","editDraft"})
     */
    private $title;

    /**
     * @var CommonModel $content
     * @Serializer\Groups({"details"})
     * @Serializer\Type("SimpleIT\ApiResourcesBundle\Exercise\ExerciseModel\Common\CommonModel")
     * @Assert\NotBlank(groups={"create","editContent"})
     * @Assert\Blank(groups={"editType", "editTitle", "appCreate","editDraft"})
     * @Assert\Valid
     */
    private $content;

    /**
     * @var boolean $draft
     * @Serializer\Groups({"details", "list"})
     * @Serializer\Type("boolean")
     * @Assert\NotNull(groups={"create","editDraft"})
     * @Assert\Null(groups={"editType", "editTitle", "appCreate","editContent"})
     */
    private $draft;

    /**
     * @var boolean $complete
     * @Serializer\Groups({"details", "list"})
     * @Serializer\Type("boolean")
     * @Assert\Null(groups={"editType", "editTitle", "appCreate","editContent", "create",
     * "editDraft"})
     */
    private $complete;

    /**
     * @var int
     * @Serializer\Type("integer")
     * @Serializer\Groups({"details", "list", "resource_list"})
     * @Assert\Blank(groups={"create", "edit", "editType","editContent", "editTitle", "appCreate","editDraft"})
     */
    private $author;

    /**
     * @var array $requiredExerciseResources
     * @Serializer\Type("array")
     * @Serializer\Groups({"details"})
     * @Assert\NotNull(groups={"create"})
     * @Assert\Blank(groups={"editType","editContent", "editTitle", "appCreate","editDraft"})
     */
    private $requiredExerciseResources;

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
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
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
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
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
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
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
            case CommonExercise::MULTIPLE_CHOICE:
                $class = self::MULTIPLE_CHOICE_MODEL_CLASS;
                break;
            case CommonExercise::GROUP_ITEMS:
                $class = self::GROUP_ITEMS_MODEL_CLASS;
                break;
            case CommonExercise::ORDER_ITEMS:
                $class = self::ORDER_ITEMS_MODEL_CLASS;
                break;
            case CommonExercise::PAIR_ITEMS:
                $class = self::PAIR_ITEMS_MODEL_CLASS;
                break;
            case CommonExercise::OPEN_ENDED_QUESTION:
                $class = self::OPEN_ENDED_QUESTION_CLASS;
                break;
            default:
                throw new \LogicException('Unknown type');
        }

        return $class;
    }

}
