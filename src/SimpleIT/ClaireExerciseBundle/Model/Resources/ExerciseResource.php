<?php
namespace SimpleIT\ClaireExerciseResourceBundle\Model\Resources;

use JMS\Serializer\Annotation as Serializer;
use SimpleIT\ClaireExerciseResourceBundle\Model\Resources\Exercise\Common\CommonExercise;

/**
 * Class ExerciseModelResource
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class ExerciseResource
{
    /**
     * @const RESOURCE_NAME = 'Exercise'
     */
    const RESOURCE_NAME = 'Exercise';

    /**
     * @const MULTIPLE_CHOICE_CLASS = 'SimpleIT\ClaireExerciseResourceBundle\Model\Resources\Exercise\MultipleChoice\Exercise'
     */
    const MULTIPLE_CHOICE_CLASS = 'SimpleIT\ClaireExerciseResourceBundle\Model\Resources\Exercise\MultipleChoice\Exercise';

    /**
     * @const GROUP_ITEMS_CLASS = 'SimpleIT\ClaireExerciseResourceBundle\Model\Resources\Exercise\GroupItems\Exercise'
     */
    const GROUP_ITEMS_CLASS = 'SimpleIT\ClaireExerciseResourceBundle\Model\Resources\Exercise\GroupItems\Exercise';

    /**
     * @const GROUP_ITEMS_CLASS = 'SimpleIT\ClaireExerciseResourceBundle\Model\Resources\Exercise\GroupItems\Exercise'
     */
    const ORDER_ITEMS_CLASS = 'SimpleIT\ClaireExerciseResourceBundle\Model\Resources\Exercise\OrderItems\Exercise';

    /**
     * @const PAIR_ITEMS_CLASS = 'SimpleIT\ClaireExerciseResourceBundle\Model\Resources\Exercise\PairItems\Exercise'
     */
    const PAIR_ITEMS_CLASS = 'SimpleIT\ClaireExerciseResourceBundle\Model\Resources\Exercise\PairItems\Exercise';

    /**
     * @const OPEN_ENDED_QUESTION_CLASS = 'SimpleIT\ClaireExerciseResourceBundle\Model\Resources\Exercise\OpenEndedQuestion\Exercise'
     */
    const OPEN_ENDED_QUESTION_CLASS = 'SimpleIT\ClaireExerciseResourceBundle\Model\Resources\Exercise\OpenEndedQuestion\Exercise';

    /**
     * @var int $id Id of exercise
     * @Serializer\Type("integer")
     * @Serializer\Groups({"details", "exercise", "list"})
     */
    private $id;

    /**
     * @var int $ownerExerciseModel
     * @Serializer\Type("integer")
     * @Serializer\Groups({"details", "exercise", "list"})
     */
    private $ownerExerciseModel;

    /**
     * @var CommonExercise $content
     * @Serializer\Type("SimpleIT\ClaireExerciseResourceBundle\Model\Resources\Exercise\Common\CommonExercise")
     * @Serializer\Groups({"details", "exercise"})
     */
    private $content;

    /**
     * Set content
     *
     * @param \SimpleIT\ClaireExerciseResourceBundle\Model\Resources\Exercise\Common\CommonExercise $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * Get content
     *
     * @return \SimpleIT\ClaireExerciseResourceBundle\Model\Resources\Exercise\Common\CommonExercise
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
     * Set ownerExerciseModel
     *
     * @param int $ownerExerciseModel
     */
    public function setOwnerExerciseModel($ownerExerciseModel)
    {
        $this->ownerExerciseModel = $ownerExerciseModel;
    }

    /**
     * Get ownerExerciseModel
     *
     * @return int
     */
    public function getOwnerExerciseModel()
    {
        return $this->ownerExerciseModel;
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
                $class = self::MULTIPLE_CHOICE_CLASS;
                break;
            case CommonExercise::GROUP_ITEMS:
                $class = self::GROUP_ITEMS_CLASS;
                break;
            case CommonExercise::ORDER_ITEMS:
                $class = self::ORDER_ITEMS_CLASS;
                break;
            case CommonExercise::PAIR_ITEMS:
                $class = self::PAIR_ITEMS_CLASS;
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
