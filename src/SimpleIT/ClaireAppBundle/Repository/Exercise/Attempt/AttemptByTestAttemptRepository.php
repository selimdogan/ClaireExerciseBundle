<?php
namespace SimpleIT\ClaireAppBundle\Repository\Exercise\Attempt;

use SimpleIT\AppBundle\Repository\AppRepository;
use SimpleIT\Utils\Collection\CollectionInformation;
use SimpleIT\Utils\Collection\PaginatedCollection;

/**
 * Class AttemptByTestAttemptRepository
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class AttemptByTestAttemptRepository extends AppRepository
{
    /**
     * @var string
     */
    protected $path = 'test-attempts/{testAttemptId}/attempts/';

    /**
     * @var string
     */
    protected $resourceClass = 'SimpleIT\ApiResourcesBundle\Exercise\AttemptResource';

    /**
     * Find a list of attempt
     *
     * @param int                   $testAttemptId
     * @param CollectionInformation $collectionInformation Collection information
     *
     * @return PaginatedCollection
     */
    public function findAll($testAttemptId, CollectionInformation $collectionInformation = null)
    {
        return $this->findAllResources(
            array('testAttemptId' => $testAttemptId),
            $collectionInformation
        );
    }
}
