<?php

namespace SimpleIT\ClaireAppBundle\Repository\AssociatedContent;

use SimpleIT\AppBundle\Repository\AppRepository;
use SimpleIT\Utils\Collection\CollectionInformation;
use SimpleIT\Utils\Collection\PaginatedCollection;
use SimpleIT\AppBundle\Annotation\Cache;

/**
 * Class TagByPartRepository
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class TagByPartRepository extends AppRepository
{
    /**
     * @var string
     */
    protected $path = 'courses/{courseIdentifier}/parts/{partIdentifier}/tags/';

    /**
     * @var  string
     */
    protected $resourceClass = 'SimpleIT\ApiResourcesBundle\AssociatedContent\TagResource';

    /**
     * Find all tags of a part
     *
     * @param int | string          $courseIdentifier      Course id | slug
     * @param int | string          $partIdentifier        Part id | slug
     * @param CollectionInformation $collectionInformation Collection information
     *
     * @return PaginatedCollection
     * @cache
     */
    public function findAll(
        $courseIdentifier,
        $partIdentifier,
        CollectionInformation $collectionInformation = null
    )
    {
        return parent::findAllResources(
            array(
                'courseIdentifier' => $courseIdentifier,
                'partIdentifier'   => $partIdentifier
            ),
            $collectionInformation
        );
    }
}
