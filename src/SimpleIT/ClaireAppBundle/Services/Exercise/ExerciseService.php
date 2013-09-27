<?php


namespace SimpleIT\ClaireAppBundle\Services\Exercise;

use JMS\Serializer\SerializerInterface;
use SimpleIT\ApiResourcesBundle\Exercise\Exercise\Common\CommonExercise;
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
        return $this->get($exerciseId)->getContent();
    }
}
