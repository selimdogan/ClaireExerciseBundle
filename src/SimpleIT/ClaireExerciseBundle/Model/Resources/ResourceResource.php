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
     * @Assert\Blank(groups={"create","editContent","edit", "appCreate"})
     */
    private $id;

    /**
     * @var string $type
     * @Serializer\Type("string")
     * @Serializer\Groups({"details", "list", "resource_list"})
     * @Assert\NotBlank(groups={"create", "appCreate"})
     * @Assert\Blank(groups={"editContent", "edit"})
     */
    private $type;

    /**
     * @var CommonResource $content
     * @Serializer\Type("SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseResource\CommonResource")
     * @Serializer\Groups({"details", "resource_list"})
     * @Assert\NotBlank(groups={"create","editContent"})
     * @Assert\Blank(groups={"appCreate"})
     * @Assert\Valid
     */
    private $content;

    /**
     * @var array $requiredExerciseResources
     * @Serializer\Type("array")
     * @Serializer\Groups({"details"})
     * @Assert\NotNull(groups={"create"})
     * @Assert\Blank(groups={"editContent", "appCreate"})
     */
    private $requiredExerciseResources;

    /**
     * @var int
     * @Serializer\Type("integer")
     * @Serializer\Groups({"details", "list", "resource_list"})
     * @Assert\Blank(groups={"create", "edit","editContent", "appCreate"})
     */
    private $author;

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
