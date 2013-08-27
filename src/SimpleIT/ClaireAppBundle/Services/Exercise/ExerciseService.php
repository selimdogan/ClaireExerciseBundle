<?php


namespace SimpleIT\ClaireAppBundle\Services\Exercise;

use JMS\Serializer\SerializerInterface;
use SimpleIT\ApiResourcesBundle\Exercise\ExerciseCreation\Common\CommonExercise;
use SimpleIT\ApiResourcesBundle\Exercise\ExerciseResource;
use SimpleIT\ClaireAppBundle\Repository\Exercise\ExerciseByExerciseModelRepository;
use SimpleIT\ClaireAppBundle\Repository\Exercise\ExerciseRepository;

/**
 * Class ExerciseService
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class ExerciseService implements ExerciseServiceInterface
{

    const MULTIPLE_CHOICE_CLASS = 'SimpleIT\ApiResourcesBundle\Exercise\ExerciseCreation\MultipleChoice\Exercise';

    const GROUP_ITEMS_CLASS = 'SimpleIT\ApiResourcesBundle\Exercise\ExerciseCreation\GroupItems\Exercise';

    const ORDER_ITEMS_CLASS = 'SimpleIT\ApiResourcesBundle\Exercise\ExerciseCreation\OrderItems\Exercise';

    const PAIR_ITEMS_CLASS = 'SimpleIT\ApiResourcesBundle\Exercise\ExerciseCreation\PairItems\Exercise';

    /**
     * @var  ExerciseRepository
     */
    private $exerciseRepository;

    /**
     * @var  ExerciseByExerciseModelRepository
     */
    private $exerciseByExerciseModelRepository;

    /**
     * @var SerializerInterface
     */
    protected $serializer;

    /**
     * Set serializer
     *
     * @param SerializerInterface $serializer
     */
    public function setSerializer(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * Set exerciseRepository
     *
     * @param ExerciseRepository $exerciseRepository
     */
    public function setExerciseRepository(ExerciseRepository $exerciseRepository)
    {
        $this->exerciseRepository = $exerciseRepository;
    }

    /**
     * Set exerciseByExerciseModelRepository
     *
     * @param ExerciseByExerciseModelRepository $exerciseByExerciseModelRepository
     */
    public function setExerciseByExerciseModelRepository(
        ExerciseByExerciseModelRepository $exerciseByExerciseModelRepository
    )
    {
        $this->exerciseByExerciseModelRepository = $exerciseByExerciseModelRepository;
    }

    /**
     * Get an exercise
     *
     * @param int $exerciseId Exercise Id
     *
     * @return ExerciseResource
     */
    public function get($exerciseId)
    {
        return $this->exerciseRepository->find($exerciseId);
    }

    /**
     * Generate a new instance of exercise with this model
     *
     * @param int $exerciseModelId
     *
     * @return ExerciseResource
     */
    public function generate($exerciseModelId)
    {
        return $this->exerciseByExerciseModelRepository->generate($exerciseModelId);
    }

    /**
     * Get an exercise object
     *
     * @param int $exerciseId
     *
     * @return CommonExercise
     */
    public function getExerciseObjectFromExercise($exerciseId)
    {
        $exerciseResource = $this->get($exerciseId);
        $class = $this->getClassFromType($exerciseResource->getExerciseModel()->getType());

        return $this->serializer->deserialize($exerciseResource, $class, 'json');
    }

    /**
     * Get the serialization class from the type of the exercise
     *
     * @param string $type
     *
     * @return string
     * @throws \LogicException
     */
    private function getClassFromType($type)
    {
        switch ($type) {
            case CommonExercise::MULTIPLE_CHOICE:
                $class = self::MULTIPLE_CHOICE_CLASS;
                break;
            case CommonExercise::GROUP_ITEMS:
                $class = self::GROUP_ITEMS_CLASS;
                break;
            case CommonExercise::PAIR_ITEMS:
                $class = self::PAIR_ITEMS_CLASS;
                break;
            case CommonExercise::ORDER_ITEMS:
                $class = self::ORDER_ITEMS_CLASS;
                break;
            default:
                throw new \LogicException('Unknown type of exercise: ' . $type);
        }

        return $class;
    }
}
