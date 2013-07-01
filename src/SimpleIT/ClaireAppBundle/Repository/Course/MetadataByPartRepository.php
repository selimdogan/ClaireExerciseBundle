<?php


namespace SimpleIT\ClaireAppBundle\Repository\Course;

use SimpleIT\AppBundle\Repository\AppRepository;
use SimpleIT\Utils\FormatUtils;

/**
 * Class MetadataByPartRepository
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class MetadataByPartRepository extends AppRepository
{
    /**
     * @var string
     */
    protected $path = 'courses/{courseIdentifier}/parts/{partIdentifier}/metadatas/';

    /**
     * @var  string
     */
    protected $resourceClass = '';

    /**
     * Find metadatas
     *
     * @param string $courseIdentifier Course id | slug
     * @param string $partIdentifier   Part id | slug
     * @param array  $parameters       Parameters
     *
     * @return string
     */
    public function find($courseIdentifier, $partIdentifier, $parameters = array())
    {
        return parent::findResource(
            array('courseIdentifier' => $courseIdentifier, 'partIdentifier' => $partIdentifier),
            $parameters
        );
    }

    /**
     * Insert metadatas
     *
     * @param string $courseIdentifier Course id | slug
     * @param string $partIdentifier   Part id | slug
     * @param array  $metadatas        Metadatas (key => value)
     * @param array  $parameters       Parameters
     *
     * @return string
     */
    public function insert($courseIdentifier, $partIdentifier, $metadatas, $parameters = array())
    {
        return parent::updateResource(
            $metadatas,
            array('courseIdentifier' => $courseIdentifier, 'partIdentifier' => $partIdentifier),
            $parameters
        );
    }
}
