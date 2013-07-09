<?php


namespace SimpleIT\ClaireAppBundle\Repository\Course;

use SimpleIT\AppBundle\Repository\AppRepository;

/**
 * Class MetadataByCourseRepository
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class MetadataByCourseRepository extends AppRepository
{
    /**
     * @var string
     */
    protected $path = 'courses/{courseIdentifier}/metadatas/{metadataIdentifier}';

    /**
     * @var  string
     */
    protected $resourceClass = 'array';

    /**
     * Find metadatas
     *
     * @param string $courseIdentifier Course id | slug
     * @param array  $parameters       Parameters
     *
     * @return array
     */
    public function find($courseIdentifier, $parameters = array())
    {
        return parent::findResource(
            array('courseIdentifier' => $courseIdentifier),
            $parameters
        );
    }

    /**
     * Insert metadatas
     *
     * @param string $courseIdentifier Course id | slug
     * @param array  $metadatas        Metadatas (key => value)
     * @param array  $parameters       Parameters
     *
     * @return array
     */
    public function insert($courseIdentifier, $metadatas, $parameters = array())
    {
        $metadatasInserted = parent::insertResource(
            $metadatas,
            array('courseIdentifier' => $courseIdentifier),
            $parameters
        );

        return $metadatasInserted;
    }

    /**
     * Update metadatas
     *
     * @param string $courseIdentifier Course id | slug
     * @param array  $metadatas        Metadatas (key => value)
     * @param array  $parameters       Parameters
     *
     * @return array
     */
    public function update($courseIdentifier, $metadatas, $parameters = array())
    {
        $metadatasUpdated = array();
        foreach ($metadatas as $key => $value) {
            $metadatasUpdated = parent::updateResource(
                $metadatas,
                array('courseIdentifier' => $courseIdentifier, 'metadataIdentifier' => $key),
                $parameters
            );
        }

        return $metadatasUpdated;
    }

    /**
     * Delete metadatas
     *
     * @param string $courseIdentifier Course id | slug
     * @param array  $metadatas        Metadatas (key => value)
     * @param array  $parameters       Parameters
     *
     * @return array
     */
    public function delete($courseIdentifier, $metadatas, $parameters = array())
    {
        foreach ($metadatas as $key => $value) {
            parent::deleteResource(
                $metadatas,
                array(
                    'courseIdentifier'   => $courseIdentifier,
                    'metadataIdentifier' => $key
                ),
                $parameters
            );
        }
    }
}
