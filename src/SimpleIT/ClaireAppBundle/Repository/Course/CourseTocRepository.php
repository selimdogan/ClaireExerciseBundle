<?php

namespace SimpleIT\ClaireAppBundle\Repository\Course;

use SimpleIT\ApiResourcesBundle\Course\PartResource;
use SimpleIT\AppBundle\Repository\AppRepository;
use SimpleIT\Utils\FormatUtils;
use SimpleIT\AppBundle\Annotation\Cache;

/**
 * Class CourseTocRepository
 * @deprecated
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class CourseTocRepository extends AppRepository
{
    /**
     * @type string
     */
    protected $path = 'courses/{courseIdentifier}/toc';

    /**
     * @type string
     */
    protected $resourceClass = 'SimpleIT\ApiResourcesBundle\Course\PartResource';

    /**
     * @var PartResource
     */
    private $child;

    /**
     * @var PartResource
     */
    private $parent;

    private $toc;

    /**
     * Find a course toc
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
     * Find a course toc to edit
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

    public function update($courseId, PartResource $toc)
    {
        $data = $this->serializer->serialize($toc, 'json', array('edit'));
        $request = $this->client->put(
            array($this->path, self::formatIdentifiers(array('courseIdentifier' => $courseId))),
            null,
            $data
        );

        return $this->getSingleResource($request);

    }

    private function formatToc()
    {
        /** @var PartResource $child */
        foreach ($this->parent->getChildren() as $this->child) {
            $child->setCreatedAt(null);
            $child->setMetadatas(null);
            $child->setSlug(null);
            $child->setUpdatedAt(null);
            $this->parent = $this->child;
            $this->formatToc();
        }
    }

}
