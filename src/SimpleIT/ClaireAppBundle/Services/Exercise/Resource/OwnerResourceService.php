<?php

namespace SimpleIT\ClaireAppBundle\Services\Exercise\Resource;

use Doctrine\Common\Collections\ArrayCollection;
use SimpleIT\ApiResourcesBundle\Course\CourseResource;
use SimpleIT\ApiResourcesBundle\Exercise\OwnerResourceResource;
use SimpleIT\ClaireAppBundle\Repository\Exercise\OwnerResource\MetadataByOwnerResourceRepository;
use SimpleIT\ClaireAppBundle\Repository\Exercise\OwnerResource\OwnerResourceRepository;
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
     * @var OwnerResourceRepository
     */
    private $ownerResourceRepository;

    /**
     * @var MetadataByOwnerResourceRepository
     */
    private $metadataByOwnerResourceRepository;

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
     * Set metadataByOwnerResourceRepository
     *
     * @param \SimpleIT\ClaireAppBundle\Repository\Exercise\OwnerResource\MetadataByOwnerResourceRepository $metadataByOwnerResourceRepository
     */
    public function setMetadataByOwnerResourceRepository($metadataByOwnerResourceRepository)
    {
        $this->metadataByOwnerResourceRepository = $metadataByOwnerResourceRepository;
    }

    /**
     * Get all owner resources
     *
     * @param array $metadataArray
     * @param array $miscArray
     *
     * @return \SimpleIT\Utils\Collection\PaginatedCollection
     */
    public function getAll(array $metadataArray, array $miscArray)
    {

        if (empty ($metadataArray) && empty($miscArray)) {
            $collectionInformation = null;
        } else {
            $collectionInformation = new CollectionInformation();

            // metadata
            $mdFilter = '';
            foreach ($metadataArray as $key => $value) {
                if ($mdFilter !== '') {
                    $mdFilter .= ',';
                }
                $mdFilter .= $key . ':' . $value;
            }

            $collectionInformation->addFilter('metadata', $mdFilter);

            // keywords
            $keywordFilter = '';
            foreach ($miscArray as $value) {
                if ($keywordFilter !== '') {
                    $keywordFilter .= ',';
                }
                $keywordFilter .= $value;
            }

            $collectionInformation->addFilter('keywords', $keywordFilter);
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
     * @return OwnerResourceResource
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
     * Add a key/value metadata to a set of owner resources
     *
     * @param $ownerResourceIds
     * @param $metaKey
     * @param $metaValue
     *
     * @return ArrayCollection
     */
    public function addMultipleMetadata($ownerResourceIds, $metaKey, $metaValue)
    {
        $metadata = new ArrayCollection(array($metaKey => $metaValue));

        foreach ($ownerResourceIds as $id) {
            $this->metadataByOwnerResourceRepository->insert($id, $metadata);
        }

        return $metadata;
    }

    /**
     * Save all the metadata for an owner resource
     *
     * @param $ownerResourceId
     * @param $resourceData
     *
     * @return mixed
     * @throws \Exception
     */
    public function saveMetadata($ownerResourceId, $resourceData)
    {
        $metadatas = array();
        if (isset($resourceData['misc'])) {
            $metadatas[self::MISC_METADATA_KEY] = $miscString = implode(';', $resourceData['misc']);
        }

        if (isset($resourceData['metaKey'])) {
            $metaValues = $resourceData['metaValue'];
            foreach ($resourceData['metaKey'] as $key => $keyValue) {
                if ($keyValue === self::MISC_METADATA_KEY) {
                    throw new \Exception(self::MISC_METADATA_KEY . 'is a reserved metadata key');
                }
                $metadatas[$keyValue] = $metaValues[$key];
            }
        }

        return $this->metadataByOwnerResourceRepository->update(
            $ownerResourceId,
            new ArrayCollection($metadatas)
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
