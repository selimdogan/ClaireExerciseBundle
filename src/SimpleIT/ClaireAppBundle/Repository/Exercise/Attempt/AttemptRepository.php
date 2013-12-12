<?php
namespace SimpleIT\ClaireAppBundle\Repository\Exercise\Attempt;

use SimpleIT\ApiResourcesBundle\Exercise\AttemptResource;
use SimpleIT\AppBundle\Repository\AppRepository;
use SimpleIT\Utils\Collection\CollectionInformation;
use SimpleIT\Utils\Collection\PaginatedCollection;

/**
 * Class AttemptRepository
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class AttemptRepository extends AppRepository
{
    /**
     * @var string
     */
    protected $path = 'attempts/{attemptId}';

    /**
     * @var string
     */
    protected $resourceClass = 'SimpleIT\ApiResourcesBundle\Exercise\AttemptResource';

    /**
     * Find a list of attempt
     *
     * @param CollectionInformation $collectionInformation Collection information
     *
     * @return PaginatedCollection
     */
    public function findAll(CollectionInformation $collectionInformation = null)
    {
        return $this->findAllResources(
            array(),
            $collectionInformation
        );
    }

    /**
     * Find an attempt
     *
     * @param int $attemptId
     *
     * @return AttemptResource
     */
    public function find($attemptId)
    {
        return $this->findResource(array('attemptId' => $attemptId));
    }
}
