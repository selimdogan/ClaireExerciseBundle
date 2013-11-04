<?php

namespace SimpleIT\ClaireAppBundle\Repository\Course;

use SimpleIT\AppBundle\Repository\AppRepository;

/**
 * Class CourseStatusRepository
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class CourseStatusRepository extends AppRepository
{
    /**
     * @var string
     */
    protected $path = 'courses/{courseIdentifier}/status';

    /**
     * @var  string
     */
    protected $resourceClass = 'SimpleIT\ApiResourcesBundle\Course\CourseResource';

    /**
     * Get a course content
     *
     * @param int|string $courseIdentifier Course id | slug
     *
     * @return mixed
     */
    public function findAll($courseIdentifier)
    {
        return parent::findAllResources(array('courseIdentifier' => $courseIdentifier));
    }
}
