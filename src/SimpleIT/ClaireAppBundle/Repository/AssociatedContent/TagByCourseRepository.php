<?php

namespace SimpleIT\ClaireAppBundle\Repository\AssociatedContent;

use OC\CLAIRE\BusinessRules\Entities\AssociatedContent\Tag\Tag;
use OC\CLAIRE\BusinessRules\Entities\Course\Course\Status;
use OC\CLAIRE\BusinessRules\Gateways\AssociatedContent\Tag\TagByCourseGateway;
use SimpleIT\AppBundle\Repository\AppRepository;
use SimpleIT\Utils\Collection\CollectionInformation;
use SimpleIT\Utils\Collection\PaginatedCollection;
use SimpleIT\AppBundle\Annotation\Cache;

/**
 * Class TagByPartRepository
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class TagByCourseRepository extends AppRepository implements TagByCourseGateway
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

    /**
     * @return Tag[]
     */
    public function findDraft($courseId)
    {
        $collectionInformation = new CollectionInformation();
        $collectionInformation->addFilter('status', Status::DRAFT);

        return parent::findAllResources(
            array(
                'courseIdentifier' => $courseId
            ),
            $collectionInformation
        );
    }

    /**
     * @return Tag[]
     */
    public function findWaitingForPublication($courseId)
    {
        $collectionInformation = new CollectionInformation();
        $collectionInformation->addFilter('status', Status::WAITING_FOR_PUBLICATION);

        return parent::findAllResources(
            array(
                'courseIdentifier' => $courseId
            ),
            $collectionInformation
        );
    }

    /**
     * @return Tag[]
     * @cache (namespacePrefix="claire_app_course_course", namespaceAttribute="courseIdentifier", lifetime=0)
     */
    public function findPublished($courseIdentifier)
    {
        $collectionInformation = new CollectionInformation();
        $collectionInformation->addFilter('status', Status::PUBLISHED);

        return parent::findAllResources(
            array(
                'courseIdentifier' => $courseIdentifier
            ),
            $collectionInformation
        );
    }

}
