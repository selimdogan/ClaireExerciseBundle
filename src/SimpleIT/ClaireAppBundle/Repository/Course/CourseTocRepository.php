<?php


namespace SimpleIT\ClaireAppBundle\Repository\Course;

use SimpleIT\AppBundle\Repository\AppRepository;
use SimpleIT\Utils\FormatUtils;
use SimpleIT\AppBundle\Annotation\Cache;

/**
 * Class CourseTocRepository
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class CourseTocRepository extends AppRepository
{
    /**
     * @type string
     */
    protected $path = 'courses/{courseIdentifier}/course';

    /**
     * @type string
     */
    protected $resourceClass = 'SimpleIT\ApiResourcesBundle\Course\PartResource';

    /**
     * Find a course course
     *
     * @param string $courseIdentifier Course id | slug
     * @param array  $parameters       Parameters
     * @param string $format           Format
     *
     * @return mixed
     * @cache (namespacePrefix="claire_app_course_course", namespaceAttribute="courseIdentifier", lifetime=0)
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

    /**
     * Find a course course to edit
     *
     * @param string $courseIdentifier Course id | slug
     * @param array  $parameters       Parameters
     * @param string $format           Format
     *
     * @return mixed
     */
    public function findByStatus(
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
