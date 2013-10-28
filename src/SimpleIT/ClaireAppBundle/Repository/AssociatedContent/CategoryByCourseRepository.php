<?php

namespace SimpleIT\ClaireAppBundle\Repository\AssociatedContent;

use SimpleIT\ApiResourcesBundle\AssociatedContent\CategoryResource;
use SimpleIT\AppBundle\Repository\AppRepository;

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
     */
    public function find($courseIdentifier)
    {
        return parent::findResource(array('courseIdentifier' => $courseIdentifier));
    }
}
