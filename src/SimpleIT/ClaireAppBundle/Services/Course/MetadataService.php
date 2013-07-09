<?php

namespace SimpleIT\ClaireAppBundle\Services\Course;

use SimpleIT\ClaireAppBundle\Repository\Course\MetadataByCourseRepository;
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
     * @var  MetadataByCourseRepository
     */
    private $metadataByCourseRepository;

    /**
     * Set metadataByPartRepository
     *
     * @param MetadataByPartRepository $metadataByPartRepository
     */
    public function setMetadataByPartRepository($metadataByPartRepository)
    {
        $this->metadataByPartRepository = $metadataByPartRepository;
    }

    /**
     * Set metadataByCourseRepository
     *
     * @param \SimpleIT\ClaireAppBundle\Repository\Course\MetadataByCourseRepository $metadataByCourseRepository
     */
    public function setMetadataByCourseRepository($metadataByCourseRepository)
    {
        $this->metadataByCourseRepository = $metadataByCourseRepository;
    }

    /**
     * Get metadatas from a course
     *
     * @param string $courseIdentifier Course id | slug
     *
     * @return string
     */
    public function getAllFromCourse($courseIdentifier)
    {
        return $this->metadataByCourseRepository->find($courseIdentifier);
    }

    /**
     * Get metadatas from a part
     *
     * @param string $courseIdentifier Course id | slug
     * @param string $partIdentifier   Part id | slug
     *
     * @return string
     */
    public function getAllFromPart($courseIdentifier, $partIdentifier)
    {
        return $this->metadataByPartRepository->find($courseIdentifier, $partIdentifier);
    }

    /**
     * Add metadatas to a course
     *
     * @param string $courseIdentifier Course id | slug
     * @param array  $metadatas        Metadatas (key => value)
     *
     * @return string
     */
    public function addToCourse($courseIdentifier, $metadatas)
    {
        return $this->metadataByCourseRepository->insert(
            $courseIdentifier,
            $metadatas
        );
    }

    /**
     * Add metadatas to a part
     *
     * @param string $courseIdentifier Course id | slug
     * @param string $partIdentifier   Part id | slug
     * @param array  $metadatas        Metadatas (key => value)
     *
     * @return string
     */
    public function addToPart($courseIdentifier, $partIdentifier, $metadatas)
    {
        return $this->metadataByPartRepository->insert(
            $courseIdentifier,
            $partIdentifier,
            $metadatas
        );
    }

    /**
     * Save metadatas from a course
     *
     * @param string $courseIdentifier Course id | slug
     * @param array  $metadatas        Metadatas (key => value)
     *
     * @return string
     */
    public function saveFromCourse($courseIdentifier, $metadatas)
    {
        $metadatasToUpdate = $this->metadataByCourseRepository->find($courseIdentifier);
        foreach ($metadatas as $key => $value) {
            if (array_key_exists($key, $metadatasToUpdate)) {
                $metadatasToUpdate = $this->metadataByCourseRepository->update(
                    $courseIdentifier,
                    array($key => $value)
                );
            } else {
                $metadatasToUpdate = $this->metadataByCourseRepository->insert(
                    $courseIdentifier,
                    array($key => $value)
                );
            }
        }

        return $metadatasToUpdate;
    }

    /**
     * Save metadatas from a part
     *
     * @param string $courseIdentifier Course id | slug
     * @param string $partIdentifier   Part id | slug
     * @param array  $metadatas        Metadatas (key => value)
     *
     * @return string
     */
    public function saveFromPart($courseIdentifier, $partIdentifier, $metadatas)
    {
        $metadatasToUpdate = $this->metadataByPartRepository->find(
            $courseIdentifier,
            $partIdentifier
        );
        foreach ($metadatas as $key => $value) {
            if (array_key_exists($key, $metadatasToUpdate)) {
                $metadatasToUpdate = $this->metadataByPartRepository->update(
                    $courseIdentifier,
                    $partIdentifier,
                    array($key => $value)
                );
            } else {
                $metadatasToUpdate = $this->metadataByPartRepository->insert(
                    $courseIdentifier,
                    $partIdentifier,
                    array($key => $value)
                );
            }
        }

        return $metadatasToUpdate;
    }
}
