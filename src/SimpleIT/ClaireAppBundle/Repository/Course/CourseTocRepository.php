<?php


namespace SimpleIT\ClaireAppBundle\Repository\Course;

use SimpleIT\AppBundle\Repository\AppRepository;
use SimpleIT\Utils\FormatUtils;

/**
 * Class CourseTocRepository
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class CourseTocRepository extends AppRepository
{
    /**
     * @var string
     */
    protected $path = 'courses/{courseIdentifier}/introduction';

    /**
     * @var  string
     */
    protected $resourceClass = 'SimpleIT\ApiResourcesBundle\Course\PartResource';

    /**
     * Find a course
     *
     * @param string $courseIdentifier Course id | slug
     * @param array  $parameters       Parameters
     * @param string $format           Format
     *
     * @return mixed
     */
    public function find(
        $courseIdentifier,
        array $parameters = array(),
        $format = FormatUtils::JSON
    )
    {
        return $this->findResource(
            array('courseIdentifier' => $courseIdentifier),
            $parameters,
            $format
        );
    }
}
