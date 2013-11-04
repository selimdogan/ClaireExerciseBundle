<?php

namespace SimpleIT\ClaireAppBundle\Repository\AssociatedContent;

use SimpleIT\ApiResourcesBundle\AssociatedContent\CategoryResource;
use SimpleIT\AppBundle\Repository\AppRepository;
use SimpleIT\AppBundle\Annotation\Cache;

/**
 * Class CategoryByCourseRepository
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class CategoryByCourseRepository extends AppRepository
{
    /**
     * @type string
     */
    protected $path = 'courses/{courseIdentifier}/categories/';

    /**
     * @type string
     */
    protected $resourceClass = 'SimpleIT\ApiResourcesBundle\AssociatedContent\CategoryResource';

    /**
     * Find a category
     *
     * @param int|string $courseIdentifier Course id | slug
     *
     * @return CategoryResource
     * @cache
     */
    public function find($courseIdentifier)
    {
        return parent::findResource(array('courseIdentifier' => $courseIdentifier));
    }

    /**
     * Find a category
     *
     * @param int   $courseId   Course id
     * @param array $parameters Parameters
     *
     * @return CategoryResource
     */
    public function findToEdit($courseId, $parameters)
    {
        return parent::findResource(array('courseIdentifier' => $courseId), $parameters);
    }

    /**
     * Insert a category
     *
     * @param int|string $courseIdentifier Course id | slug
     * @param int        $categoryId       Category id
     *
     * @return mixed
     */
    public function insert($courseIdentifier, $categoryId)
    {
        return parent::insertResource(
            array($categoryId),
            array('courseIdentifier' => $courseIdentifier)
        );
    }
}
