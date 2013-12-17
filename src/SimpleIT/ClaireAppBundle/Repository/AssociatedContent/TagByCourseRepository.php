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
class TagByCourseRepository extends AppRepository
{
    /**
     * @var string
     */
    protected $path = 'courses/{courseIdentifier}/tags/';

    /**
     * @var  string
     */
    protected $resourceClass = 'SimpleIT\ApiResourcesBundle\AssociatedContent\TagResource';

    /**
     * Find a list of tags for a course
     *
     * @param int|string            $courseIdentifier      Course id | slug
     * @param CollectionInformation $collectionInformation Collection information
     *
     * @return PaginatedCollection
     * @cache
     */
    public function findAll($courseIdentifier, CollectionInformation $collectionInformation = null)
    {
        return parent::findAllResources(
            array(
                'courseIdentifier' => $courseIdentifier
            ),
            $collectionInformation
        );
    }

    /**
     * Find a list of tags for a course
     *
     * @param int                   $courseId              Course id
     * @param CollectionInformation $collectionInformation Collection information
     *
     * @return PaginatedCollection
     */
    public function findAllToEdit($courseId, CollectionInformation $collectionInformation = null)
    {
        return parent::findAllResources(
            array(
                'courseIdentifier' => $courseId
            ),
            $collectionInformation
        );
    }

    /**
     * @param       $courseId
     * @param array $tagIds
     *
     * @return mixed
     */
    public function update($courseId, array $tagIds)
    {
        return parent::updateResource(
            $tagIds,
            array(
                'courseIdentifier' => $courseId
            )
        );
    }
}
