<?php


namespace SimpleIT\ClaireAppBundle\Repository\Course;

use SimpleIT\AppBundle\Repository\AppRepository;
use SimpleIT\Utils\Collection\CollectionInformation;

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
     * @param string                $courseIdentifier      Course id | slug
     * @param CollectionInformation $collectionInformation Collection information
     *
     * @return array
     */
    public function findAll($courseIdentifier, CollectionInformation $collectionInformation = null)
    {
        return parent::findResource(
            array('courseIdentifier' => $courseIdentifier),
            $collectionInformation
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
