<?php

namespace SimpleIT\ClaireAppBundle\Services\Exercise\ExerciseModel;

use Doctrine\Common\Collections\ArrayCollection;
use SimpleIT\ApiResourcesBundle\Exercise\MetadataResource;
use SimpleIT\ApiResourcesBundle\Exercise\OwnerExerciseModelResource;
use
    SimpleIT\ClaireAppBundle\Repository\Exercise\OwnerExerciseModel\MetadataByOwnerExerciseModelRepository;
use
    SimpleIT\ClaireAppBundle\Repository\Exercise\OwnerExerciseModel\OwnerExerciseModelByExerciseModelRepository;
use
    SimpleIT\ClaireAppBundle\Repository\Exercise\OwnerExerciseModel\OwnerExerciseModelByOwnerRepository;
use SimpleIT\ClaireAppBundle\Repository\Exercise\OwnerExerciseModel\OwnerExerciseModelRepository;
use SimpleIT\Utils\Collection\CollectionInformation;
use SimpleIT\Utils\Collection\PaginatedCollection;

/**
 * Class OwnerExerciseModelService
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class OwnerExerciseModelService
{
    /**
     * @var OwnerExerciseModelRepository
     */
    private $ownerExerciseModelRepository;

    /**
     * @var MetadataByOwnerExerciseModelRepository
     */
    private $metadataByOwnerExerciseModelRepository;

    /**
     * @var OwnerExerciseModelByOwnerRepository
     */
    private $ownerExerciseModelByOwnerRepository;

    /**
     * @var OwnerExerciseModelByExerciseModelRepository
     */
    private $ownerExerciseModelByExerciseModelRepository;

    /**
     * Set metadataByOwnerExerciseModelRepository
     *
     * @param \SimpleIT\ClaireAppBundle\Repository\Exercise\OwnerExerciseModel\MetadataByOwnerExerciseModelRepository $metadataByOwnerExerciseModelRepository
     */
    public function setMetadataByOwnerExerciseModelRepository(
        $metadataByOwnerExerciseModelRepository
    )
    {
        $this->metadataByOwnerExerciseModelRepository = $metadataByOwnerExerciseModelRepository;
    }

    /**
     * Set ownerExerciseModelByExerciseModelRepository
     *
     * @param \SimpleIT\ClaireAppBundle\Repository\Exercise\OwnerExerciseModel\OwnerExerciseModelByExerciseModelRepository $ownerExerciseModelByExerciseModelRepository
     */
    public function setOwnerExerciseModelByExerciseModelRepository(
        $ownerExerciseModelByExerciseModelRepository
    )
    {
        $this->ownerExerciseModelByExerciseModelRepository = $ownerExerciseModelByExerciseModelRepository;
    }

    /**
     * Set ownerExerciseModelByOwnerRepository
     *
     * @param \SimpleIT\ClaireAppBundle\Repository\Exercise\OwnerExerciseModel\OwnerExerciseModelByOwnerRepository $ownerExerciseModelByOwnerRepository
     */
    public function setOwnerExerciseModelByOwnerRepository($ownerExerciseModelByOwnerRepository)
    {
        $this->ownerExerciseModelByOwnerRepository = $ownerExerciseModelByOwnerRepository;
    }

    /**
     * Set ownerExerciseModelRepository
     *
     * @param \SimpleIT\ClaireAppBundle\Repository\Exercise\OwnerExerciseModel\OwnerExerciseModelRepository $ownerExerciseModelRepository
     */
    public function setOwnerExerciseModelRepository($ownerExerciseModelRepository)
    {
        $this->ownerExerciseModelRepository = $ownerExerciseModelRepository;
    }

    /**
     * Get all owner exercise models and if a user is specified, show only the public exercise
     * models except user's ones
     *
     * @param array  $metadataArray
     * @param array  $miscArray
     * @param null   $userId
     * @param bool   $personalExerciseModel
     * @param string $type
     *
     * @return PaginatedCollection
     */
    public function getAll(
        array $metadataArray,
        array $miscArray,
        $userId = null,
        $personalExerciseModel = true,
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

        if (!is_null($userId) && $personalExerciseModel === true) {
            $paginatedCollection = $this->ownerExerciseModelByOwnerRepository->findAll
                (
                    $userId,
                    $collectionInformation
                );
        } else {
            if (!is_null($userId) && $personalExerciseModel === false) {
                $collectionInformation->addFilter('public-except-user', $userId);
            }
            $paginatedCollection = $this->ownerExerciseModelRepository->findAll(
                $collectionInformation
            );
        }

        foreach ($paginatedCollection as &$ownerExerciseModel) {
            /** @var OwnerExerciseModelResource $ownerExerciseModel */
            $metadata = array();
            foreach ($ownerExerciseModel->getMetadata() as $mkey => $value) {
                if ($mkey === MetadataResource::MISC_METADATA_KEY) {
                    $metadata[$mkey] = explode(';', $value);
                } else {
                    $metadata[$mkey] = $value;
                }
            }
            $ownerExerciseModel->setMetadata($metadata);
        }

        return $paginatedCollection;
    }

    /**
     * Get an owner exercise model
     *
     * @param int   $ownerExerciseModelId
     * @param array $parameters
     *
     * @return OwnerExerciseModelResource
     */
    public function get($ownerExerciseModelId, array $parameters = array())
    {
        return $this->ownerExerciseModelRepository->find($ownerExerciseModelId, $parameters);
    }

    /**
     * Save a part content
     *
     * @param        $ownerExerciseModelId
     * @param array  $ownerExerciseModel
     *
     * @return mixed
     */
    public function saveContent($ownerExerciseModelId, array $ownerExerciseModel)
    {
        return $this->ownerExerciseModelRepository->update(
            $ownerExerciseModelId,
            $this->createExerciseModelFromArray($ownerExerciseModel)
        );
    }

    /**
     * Add a key/value metadata to a set of owner exercise model
     *
     * @param $ownerExerciseModelIds
     * @param $metaKey
     * @param $metaValue
     *
     * @return ArrayCollection
     */
    public function addMultipleMetadata($ownerExerciseModelIds, $metaKey, $metaValue)
    {
        $metadata = new ArrayCollection(array($metaKey => $metaValue));

        foreach ($ownerExerciseModelIds as $id) {
            $this->metadataByOwnerExerciseModelRepository->insert($id, $metadata);
        }

        return $metadata;
    }

    /**
     * Add a key to several values (creates new key/values) and remove the value from misc if
     * existing
     *
     * @param $metaKey
     * @param $ownerExerciseModelIds
     * @param $values
     *
     * @throws \Exception
     */
    public function addMultipleKeyMetadata($metaKey, $ownerExerciseModelIds, $values)
    {
        foreach ($ownerExerciseModelIds as $key => $id) {
            $orMd = $this->get($id)->getMetadata();
            if (isset($orMd[MetadataResource::MISC_METADATA_KEY])) {
                $misc = explode(';', $orMd[MetadataResource::MISC_METADATA_KEY]);
                if (($delKey = array_search($values[$key], $misc)) !== false) {
                    unset($misc[$delKey]);
                }
                $orMd[MetadataResource::MISC_METADATA_KEY] = implode(';', $misc);
            }

            if (isset($orMd[$metaKey])) {
                throw new \Exception ('impossible to add this key to resource ' . $id .
                'because metadata key already in use : ' . $metaKey . ':' . $orMd[$metaKey]);
            }

            $orMd[$metaKey] = $values[$key];

            $this->metadataByOwnerExerciseModelRepository->update($id, new ArrayCollection($orMd));
        }
    }

    /**
     * Save all the metadata for an owner exercise model
     *
     * @param $ownerExerciseModelId
     * @param $resourceData
     *
     * @return mixed
     * @throws \Exception
     */
    public function saveMetadata($ownerExerciseModelId, $resourceData)
    {
        $metadatas = array();
        if (isset($resourceData['misc'])) {
            $metadatas[MetadataResource::MISC_METADATA_KEY] = $miscString = implode(';', $resourceData['misc']);
        }

        if (isset($resourceData['metaKey'])) {
            $metaValues = $resourceData['metaValue'];
            foreach ($resourceData['metaKey'] as $key => $keyValue) {
                if ($keyValue === MetadataResource::MISC_METADATA_KEY) {
                    throw new \Exception(MetadataResource::MISC_METADATA_KEY . 'is a reserved metadata key');
                }
                $metadatas[$keyValue] = $metaValues[$key];
            }
        }

        return $this->metadataByOwnerExerciseModelRepository->update(
            $ownerExerciseModelId,
            new ArrayCollection($metadatas)
        );
    }

    /**
     * Insert a new owner exercise model
     *
     * @param OwnerExerciseModelResource $ownerExerciseModelResource
     *
     * @return OwnerExerciseModelResource
     */
    public function add(OwnerExerciseModelResource $ownerExerciseModelResource)
    {
        $exerciseModelId = $ownerExerciseModelResource->getExerciseModel();
        $ownerExerciseModelResource->setExerciseModel(null);

        return $this->ownerExerciseModelByExerciseModelRepository->insert(
            $ownerExerciseModelResource,
            $exerciseModelId
        );
    }

    /**
     * Insert a basic owner exercise model with an exercise model
     *
     * @param $exerciseModelId
     *
     * @return OwnerExerciseModelResource
     */
    public function addBasicFromExerciseModel($exerciseModelId)
    {
        $ownerExerciseModelResource = new OwnerExerciseModelResource();
        $ownerExerciseModelResource->setExerciseModel($exerciseModelId);
        $ownerExerciseModelResource->setMetadata(array());
        $ownerExerciseModelResource->setPublic(false);

        return $this->add($ownerExerciseModelResource);
    }

    /**
     * Create an owner exercise model from an array
     *
     * @param array $array
     *
     * @return OwnerExerciseModelResource
     */
    private function createExerciseModelFromArray(array $array)
    {
        $ownerExerciseModel = new OwnerExerciseModelResource();
        $ownerExerciseModel->setMetadata($array['metadata']);
        $ownerExerciseModel->setPublic($array['public']);

        return $ownerExerciseModel;
    }

    /**
     * Save an owner exercise model
     *
     * @param int                        $ownerExerciseModelId ownerExerciseModel id
     * @param OwnerExerciseModelResource $ownerExerciseModel
     * @param array                      $parameters
     *
     * @return OwnerExerciseModelResource
     */
    public function save(
        $ownerExerciseModelId,
        OwnerExerciseModelResource $ownerExerciseModel,
        array $parameters = array()
    )
    {
        return $this->ownerExerciseModelRepository->update(
            $ownerExerciseModelId,
            $ownerExerciseModel,
            $parameters
        );
    }
}
