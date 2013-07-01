<?php


namespace SimpleIT\ClaireAppBundle\Services\Course;

use SimpleIT\ClaireAppBundle\Repository\Course\MetadataByPartRepository;

/**
 * Class MetadataService
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class MetadataService
{
    /**
     * @var  MetadataByPartRepository
     */
    private $metadataByPartRepository;

    /**
     * Set metadataByPartRepository
     *
     * @param \SimpleIT\ClaireAppBundle\Repository\Course\MetadataByPartRepository $metadataByPartRepository
     */
    public function setMetadataByPartRepository($metadataByPartRepository)
    {
        $this->metadataByPartRepository = $metadataByPartRepository;
    }

    /**
     * Get metadatas
     *
     * @param string $courseIdentifier Course id | slug
     * @param string $partIdentifier   Part id | slug
     *
     * @return string
     */
    public function get($courseIdentifier, $partIdentifier)
    {
        return $this->metadataByPartRepository->find($courseIdentifier, $partIdentifier);
    }

    /**
     * Add metadatas
     *
     * @param string $courseIdentifier Course id | slug
     * @param string $partIdentifier   Part id | slug
     * @param array  $metadatas        Metadatas (key => value)
     *
     * @return string
     */
    public function add($courseIdentifier, $partIdentifier, $metadatas)
    {
        return $this->metadataByPartRepository->insert(
            $courseIdentifier,
            $partIdentifier,
            $metadatas
        );
    }
}
