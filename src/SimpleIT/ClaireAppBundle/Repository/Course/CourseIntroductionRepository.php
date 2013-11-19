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
class CourseIntroductionRepository extends AppRepository
{
    /**
     * @var string
     */
    protected $path = 'courses/{courseIdentifier}/introduction';

    /**
     * @var  string
     */
    protected $resourceClass = '';

    /**
     * Find a course introduction
     *
     * @param string $courseIdentifier Course id | slug
     * @param array  $parameters       Parameters
     * @param string $format           Format
     *
     * @return string
     * @cache (namespacePrefix="claire_app_course_course", namespaceAttribute="courseIdentifier", lifetime=0)
     */
    public function find(
        $courseIdentifier,
        array $parameters = array(),
        $format = FormatUtils::HTML
    )
    {
        return $this->findResource(
            array('courseIdentifier' => $courseIdentifier),
            $parameters,
            $format
        );
    }

    /**
     * Find a course introduction
     *
     * @param int|string $courseIdentifier Course id | slug
     * @param array      $parameters       Parameters
     * @param string     $format           Format
     *
     * @return string
     */
    public function findToEdit(
        $courseIdentifier,
        array $parameters = array(),
        $format = FormatUtils::HTML
    )
    {
        return $this->findResource(
            array('courseIdentifier' => $courseIdentifier),
            $parameters,
            $format
        );
    }

    /**
     * Update a course introduction content
     *
     * @param string $courseIdentifier    Course id | slug
     * @param string $introductionContent Introduction content
     * @param array  $parameters          Parameters
     * @param string $format              Format
     *
     * @return string
     */
    public function update(
        $courseIdentifier,
        $introductionContent,
        $parameters = array(),
        $format = FormatUtils::HTML
    )
    {
        return parent::updateResource(
            $introductionContent,
            array('courseIdentifier' => $courseIdentifier),
            $parameters,
            $format
        );
    }
}
