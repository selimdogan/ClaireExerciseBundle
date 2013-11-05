<?php

namespace SimpleIT\ClaireAppBundle\Services\Exercise\Resource;

use SimpleIT\ApiResourcesBundle\Course\CourseResource;
use SimpleIT\ApiResourcesBundle\Exercise\OwnerResourceResource;
use SimpleIT\ClaireAppBundle\Repository\Exercise\Resource\OwnerResourceRepository;
use SimpleIT\Utils\Collection\CollectionInformation;

/**
 * Class OwnerResourceService
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class OwnerResourceService
{
    /**
     * @const MISC_METADATA_KEY = "_misc"
     */
    const MISC_METADATA_KEY = "_misc";

    /**
     * @var  OwnerResourceRepository
     */
    private $ownerResourceRepository;

    /**
     * Set OwnerResourceRepository
     *
     * @param OwnerResourceRepository $OwnerResourceRepository
     */
    public function setOwnerResourceRepository($OwnerResourceRepository)
    {
        $this->ownerResourceRepository = $OwnerResourceRepository;
    }

    /**
     * Get all owner resources
     *
     * @param array $metadataArray
     *
     * @return \SimpleIT\Utils\Collection\PaginatedCollection
     */
    public function getAll(array $metadataArray)
    {

        if (!empty ($metadataArray)) {
            $collectionInformation = new CollectionInformation();

            $mdFilter = '';
            foreach ($metadataArray as $key => $value) {
                if ($mdFilter !== '') {
                    $mdFilter .= ',';
                }
                $mdFilter .= $key . ':' . $value;
            }

            $collectionInformation->addFilter('metadata', $mdFilter);
        } else {
            $collectionInformation = null;
        }
        $paginatedCollection = $this->ownerResourceRepository->findAll($collectionInformation);

        foreach ($paginatedCollection as &$ownerResource) {
            /** @var OwnerResourceResource $ownerResource */
            $metadata = array();
            foreach ($ownerResource->getMetadata() as $mkey => $value) {
                if ($mkey === self::MISC_METADATA_KEY) {
                    $metadata[$mkey] = explode(';', $value);
                } else {
                    $metadata[$mkey] = $value;
                }
            }
            $ownerResource->setMetadata($metadata);
        }

        return $paginatedCollection;
    }

    /**
     * Get an owner resource
     *
     * @param int | string $courseIdentifier Course id | slug
     * @param array        $parameters       Parameters
     *
     * @return \SimpleIT\ApiResourcesBundle\Course\CourseResource
     */
    public function get($courseIdentifier, array $parameters = array())
    {
        return $this->ownerResourceRepository->find($courseIdentifier, $parameters);
    }

    /**
     * Save a part content
     *
     * @param        $ownerResourceId
     * @param array  $ownerResource
     *
     * @return mixed
     */
    public function saveContent($ownerResourceId, array $ownerResource)
    {
        return $this->ownerResourceRepository->update(
            $ownerResourceId,
            $this->createResourceFromArray($ownerResource)
        );
    }

    /**
     * Create a resource from an array
     *
     * @param array $array
     *
     * @return OwnerResourceResource
     */
    private function createResourceFromArray(array $array)
    {
        $resource = new OwnerResourceResource();
        $resource->setMetadata($array['metadata']);
        $resource->setPublic($array['public']);

        return $resource;
    }

    /**
     * Save a resource
     *
     * @param int                   $ownerResourceId ownerResource id
     * @param OwnerResourceResource $ownerResource
     * @param array                 $parameters
     *
     * @return OwnerResourceResource
     */
    public function save(
        $ownerResourceId,
        OwnerResourceResource $ownerResource,
        array $parameters = array()
    )
    {
        return $this->ownerResourceRepository->update(
            $ownerResourceId,
            $ownerResource,
            $parameters
        );
    }
}
