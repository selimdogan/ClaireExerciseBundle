<?php

namespace SimpleIT\ClaireAppBundle\Repository\Course;

use SimpleIT\AppBundle\Repository\AppRepository;
use SimpleIT\Utils\FormatUtils;
use SimpleIT\AppBundle\Annotation\Cache;

/**
 * Class CourseIntroductionRepository
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class PartIntroductionRepository extends AppRepository
{
    /**
     * @var string
     */
    protected $path = 'courses/{courseIdentifier}/parts/{partIdentifier}/introduction';

    /**
     * @var  string
     */
    protected $resourceClass = '';

    /**
     * Find a part introduction
     *
     * @param string $courseIdentifier Course id | slug
     * @param string $partIdentifier   Part id | slug
     * @param array  $parameters       Parameters
     * @param string $format           Format
     *
     * @return mixed
     * @cache (namespacePrefix="claire_app_course_course", namespaceAttribute="courseIdentifier", lifetime=0)
     */
    public function find(
        $courseIdentifier,
        $partIdentifier,
        array $parameters = array(),
        $format = FormatUtils::HTML
    )
    {
        return $this->findResource(
            array('courseIdentifier' => $courseIdentifier, 'partIdentifier' => $partIdentifier),
            $parameters,
            $format
        );
    }

    /**
     * Find a part introduction
     *
     * @param string $courseIdentifier Course id | slug
     * @param string $partIdentifier   Part id | slug
     * @param array  $parameters       Parameters
     * @param string $format           Format
     *
     * @return mixed
     */
    public function findByStatus(
        $courseIdentifier,
        $partIdentifier,
        array $parameters = array(),
        $format = FormatUtils::HTML
    )
    {
        return $this->findResource(
            array('courseIdentifier' => $courseIdentifier, 'partIdentifier' => $partIdentifier),
            $parameters,
            $format
        );
    }
}
