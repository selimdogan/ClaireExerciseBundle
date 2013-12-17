<?php

namespace SimpleIT\ClaireAppBundle\Repository\AssociatedContent;

use SimpleIT\AppBundle\Repository\AppRepository;
use SimpleIT\Utils\Collection\CollectionInformation;
use SimpleIT\Utils\Collection\PaginatedCollection;
use SimpleIT\AppBundle\Annotation\Cache;

/**
 * Class CourseByCategoryRepository
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class CourseByTagRepository extends AppRepository
{
    /**
     * @var string
     */
    protected $path = 'tags/{tagIdentifier}/courses/';

    /**
     * @var  string
     */
    protected $resourceClass = 'SimpleIT\ApiResourcesBundle\Course\CourseResource';

    /**
     * Find courses for a tag
     *
     * @param int |string           $tagIdentifier         Tag id | slug
     * @param CollectionInformation $collectionInformation Collection information
     *
     * @return PaginatedCollection
     * @cache
     */
    public function findAll($tagIdentifier, CollectionInformation $collectionInformation = null)
    {
        return parent::findAllResources(
            array('tagIdentifier' => $tagIdentifier),
            $collectionInformation
        );
    }
}
