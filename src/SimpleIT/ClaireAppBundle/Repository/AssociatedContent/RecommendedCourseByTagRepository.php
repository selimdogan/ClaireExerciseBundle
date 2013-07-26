<?php


namespace SimpleIT\ClaireAppBundle\Repository\AssociatedContent;

use SimpleIT\AppBundle\Repository\AppRepository;
use SimpleIT\Utils\Collection\CollectionInformation;
use SimpleIT\Utils\Collection\PaginatedCollection;

/**
 * Class RecommendedCourseByTagRepository
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class RecommendedCourseByTagRepository extends AppRepository
{
    /**
     * @var string
     */
    protected $path = '/tags/{tagIdentifier}/recommended-courses/';

    /**
     * @var  string
     */
    protected $resourceClass = 'SimpleIT\ApiResourcesBundle\Course\CourseResource';

    /**
     * Find all recommended courses
     *
     * @param int |string           $tagIdentifier         Tag id | slug
     * @param CollectionInformation $collectionInformation Collection information
     *
     * @return PaginatedCollection
     */
    public function findAll(
        $tagIdentifier,
        CollectionInformation $collectionInformation = null
    )
    {
        return parent::findAllResources(
            array('tagIdentifier' => $tagIdentifier),
            $collectionInformation
        );
    }
}
