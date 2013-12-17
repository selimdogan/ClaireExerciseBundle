<?php
namespace SimpleIT\ClaireAppBundle\Repository\Exercise\Attempt;

use SimpleIT\ApiResourcesBundle\Exercise\AttemptResource;
use SimpleIT\AppBundle\Repository\AppRepository;
use SimpleIT\Utils\Collection\CollectionInformation;
use SimpleIT\Utils\Collection\PaginatedCollection;

/**
 * Class AttemptByExerciseRepository
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class AttemptByExerciseRepository extends AppRepository
{
    /**
     * @var string
     */
    protected $path = 'exercises/{exerciseId}/attempts/{attemptId}';

    /**
     * @var string
     */
    protected $resourceClass = 'SimpleIT\ApiResourcesBundle\Exercise\AttemptResource';

    /**
     * Find a list of attempt
     *
     * @param int                   $exerciseId
     * @param CollectionInformation $collectionInformation Collection information
     *
     * @return PaginatedCollection
     */
    public function findAll($exerciseId, CollectionInformation $collectionInformation = null)
    {
        return $this->findAllResources(
            array('exerciseId' => $exerciseId),
            $collectionInformation
        );
    }

    /**
     * Insert a new attempt for this exercise
     *
     * @param int $exerciseId
     *
     * @return AttemptResource
     */
    public function insert($exerciseId)
    {
        return $this->insertResource(null, array('exerciseId' => $exerciseId));
    }
}
