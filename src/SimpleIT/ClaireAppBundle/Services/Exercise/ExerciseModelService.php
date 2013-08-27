<?php


namespace SimpleIT\ClaireAppBundle\Services\Exercise;

use SimpleIT\ApiResourcesBundle\Exercise\ExerciseResource;
use SimpleIT\ClaireAppBundle\Repository\Exercise\ExerciseModelRepository;

/**
 * Class ExerciseModelService
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class ExerciseModelService implements ExerciseModelServiceInterface
{
    /**
     * @var  ExerciseModelRepository
     */
    private $exerciseModelRepository;

    /**
     * Set exerciseModelRepository
     *
     * @param \SimpleIT\ClaireAppBundle\Repository\Exercise\ExerciseModelRepository $exerciseModelRepository
     */
    public function setExerciseModelRepository($exerciseModelRepository)
    {
        $this->exerciseModelRepository = $exerciseModelRepository;
    }

    /**
     * Get an exercise model
     *
     * @param int $exerciseId Exercise Model Id
     *
     * @return \SimpleIT\ApiResourcesBundle\Exercise\ExerciseModelResource
     */
    public function get($exerciseId)
    {
        return $this->exerciseModelRepository->find($exerciseId);
    }

    /**
     * Get an ordered list of the exercise models
     *
     * @return \SimpleIT\Utils\Collection\PaginatedCollection
     */
    public function getListByType()
    {
        $exModels = $this->exerciseModelRepository->findAllResources();
        $modelList = array();

        foreach ($exModels as $model)
        {
            $modelList[$model->getType()][] = $model;
        }
        return $modelList;
    }
}
