<?php

namespace SimpleIT\ClaireAppBundle\Repository\Course;

use SimpleIT\AppBundle\Repository\AppRepository;
use SimpleIT\Utils\FormatUtils;
use SimpleIT\AppBundle\Annotation\Cache;

/**
 * Class PartTocRepository
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class PartTocRepository extends AppRepository
{
    /**
     * @var string
     */
    protected $path = 'courses/{courseIdentifier}/parts/{partIdentifier}/toc';

    /**
     * @var  string
     */
    protected $resourceClass = 'SimpleIT\ApiResourcesBundle\Course\PartResource';

    /**
     * Find a part toc
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
        $format = FormatUtils::JSON
    )
    {
        return $this->findResource(
            array('courseIdentifier' => $courseIdentifier, 'partIdentifier' => $partIdentifier),
            $parameters,
            $format
        );
    }

    /**
     * Find a toc without cache
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
        $format = FormatUtils::JSON
    )
    {
        return $this->findResource(
            array('courseIdentifier' => $courseIdentifier, 'partIdentifier' => $partIdentifier),
            $parameters,
            $format
        );
    }
}
