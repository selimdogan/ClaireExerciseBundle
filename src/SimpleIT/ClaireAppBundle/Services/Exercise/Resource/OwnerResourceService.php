<?php

namespace SimpleIT\ClaireAppBundle\Services\Exercise\Resource;

use Doctrine\Common\Collections\ArrayCollection;
use SimpleIT\ApiResourcesBundle\Course\CourseResource;
use SimpleIT\ApiResourcesBundle\Exercise\OwnerResourceResource;
use SimpleIT\ClaireAppBundle\Repository\Exercise\OwnerResource\MetadataByOwnerResourceRepository;
use SimpleIT\ClaireAppBundle\Repository\Exercise\OwnerResource\OwnerResourceByOwnerRepository;
use SimpleIT\ClaireAppBundle\Repository\Exercise\OwnerResource\OwnerResourceByResourceRepository;
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
     * @var OwnerResourceByOwnerRepository
     */
    private $ownerResourceByOwnerRepository;

    /**
     * @var OwnerResourceByResourceRepository
     */
    private $ownerResourceByResourceRepository;

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
     * Set ownerResourceByOwnerRepository
     *
     * @param \SimpleIT\ClaireAppBundle\Repository\Exercise\OwnerResource\OwnerResourceByOwnerRepository $ownerResourceByOwnerRepository
     */
    public function setOwnerResourceByOwnerRepository($ownerResourceByOwnerRepository)
    {
        $this->ownerResourceByOwnerRepository = $ownerResourceByOwnerRepository;
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
     * Set ownerResourceByResourceRepository
     *
     * @param \SimpleIT\ClaireAppBundle\Repository\Exercise\OwnerResource\OwnerResourceByResourceRepository $ownerResourceByResourceRepository
     */
    public function setOwnerResourceByResourceRepository($ownerResourceByResourceRepository)
    {
        $this->ownerResourceByResourceRepository = $ownerResourceByResourceRepository;
    }

    /**
     * Get all owner resources and if a user is specified, show only the public resource except
     * user's ones
     *
     * @param array  $metadataArray
     * @param array  $miscArray
     * @param null   $userId
     * @param bool   $personalResource
     * @param string $type
     *
     * @return \SimpleIT\Utils\Collection\PaginatedCollection
     */
    public function getAll(
        array $metadataArray,
        array $miscArray,
        $userId = null,
        $personalResource = true,
        $type = null
    )
    {

        $collectionInformation = new CollectionInformation();

        if (!empty ($metadataArray)) {
            // metadata
            $mdFilter = '';
            foreach ($metadataArray as $key => $value) {
                if ($mdFilter !== '') {
                    $mdFilter .= ',';
                }
                $mdFilter .= $key . ':' . $value;
            }

            $collectionInformation->addFilter('metadata', $mdFilter);
        }
        if (!empty ($miscArray)) {
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

        if (!empty($type)) {
            $collectionInformation->addFilter('type', $type);
        }

        if (!is_null($userId) && $personalResource === true) {
            $paginatedCollection = $this->ownerResourceByOwnerRepository->findAll
                (
                    $userId,
                    $collectionInformation
                );
        } else {
            if (!is_null($userId) && $personalResource === false) {
                $collectionInformation->addFilter('public-except-user', $userId);
            }
            $paginatedCollection = $this->ownerResourceRepository->findAll($collectionInformation);
        }

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
     * @param int | string $ownerResourceId
     * @param array        $parameters
     *
     * @return OwnerResourceResource
     */
    public function get($ownerResourceId, array $parameters = array())
    {
        return $this->ownerResourceRepository->find($ownerResourceId, $parameters);
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
     * Add a key to several values (creates new key/values) and remove the value from misc if
     * existing
     *
     * @param $metaKey
     * @param $ownerResourceIds
     * @param $values
     *
     * @throws \Exception
     */
    public function addMultipleKeyMetadata($metaKey, $ownerResourceIds, $values)
    {
        foreach ($ownerResourceIds as $key => $id) {
            $orMd = $this->get($id)->getMetadata();
            if (isset($orMd['_misc'])) {
                $misc = explode(';', $orMd['_misc']);
                if (($delKey = array_search($values[$key], $misc)) !== false) {
                    unset($misc[$delKey]);
                }
                $orMd['_misc'] = implode(';', $misc);
            }

            if (isset($orMd[$metaKey])) {
                throw new \Exception ('impossible to add this key to resource ' . $id .
                'because metadata key already in use : ' . $metaKey . ':' . $orMd[$metaKey]);
            }

            $orMd[$metaKey] = $values[$key];

            $this->metadataByOwnerResourceRepository->update($id, new ArrayCollection($orMd));
        }
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
     * Insert a new owner resource
     *
     * @param OwnerResourceResource $ownerResourceResource
     *
     * @return OwnerResourceResource
     */
    public function add(OwnerResourceResource $ownerResourceResource)
    {
        $resourceId = $ownerResourceResource->getResource();
        $ownerResourceResource->setResource(null);

        return $this->ownerResourceByResourceRepository->insert(
            $ownerResourceResource,
            $resourceId
        );
    }

    /**
     * Insert a basic owner resource with a resource
     *
     * @param $resourceId
     *
     * @return OwnerResourceResource
     */
    public function addBasicFromResource($resourceId)
    {
        $ownerResourceResource = new OwnerResourceResource();
        $ownerResourceResource->setResource($resourceId);
        $ownerResourceResource->setMetadata(array());
        $ownerResourceResource->setPublic(false);

        return $this->add($ownerResourceResource);
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
