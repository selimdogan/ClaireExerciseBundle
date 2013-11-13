<?php
namespace SimpleIT\ClaireAppBundle\Repository\Exercise\ExerciseModel;

use Doctrine\Common\Collections\ArrayCollection;
use SimpleIT\ApiResourcesBundle\Exercise\ExerciseModelResource;
use SimpleIT\AppBundle\Repository\AppRepository;

/**
 * Class RequiredResourceByExerciseModelRepository
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class RequiredResourceByExerciseModelRepository extends AppRepository
{
    /**
     * @var string
     */
    protected $path = 'exercise-models/{exerciseModelId}/required-resources/{reqResId}';

    /**
     * @var string
     */
    protected $resourceClass = 'Doctrine\Common\Collections\ArrayCollection';

    /**
     * Insert a required resource
     *
     * @param int $exerciseModelId
     * @param int $reqResId
     *
     * @return ExerciseModelResource
     */
    public function insert($exerciseModelId, $reqResId)
    {
        return $this->insertResource(
            null,
            array('exerciseModelId' => $exerciseModelId, 'reqResId' => $reqResId)
        );
    }

    /**
     * Delete a required resource
     *
     * @param int $exerciseModelId
     * @param int $reqResId
     */
    public function delete($exerciseModelId, $reqResId)
    {
        $this->deleteResource(array('exerciseModelId' => $exerciseModelId, 'reqResId' => $reqResId));
    }

    /**
     * Update the list of required resources of an exercise model
     *
     * @param int             $exerciseModelId
     * @param ArrayCollection $requiredResources
     *
     * @return mixed
     */
    public function update($exerciseModelId, ArrayCollection $requiredResources)
    {
        return $this->updateResource($requiredResources, array('exerciseModelId' => $exerciseModelId));
    }
}
