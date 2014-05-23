<?php

namespace SimpleIT\ClaireExerciseBundle\Repository\Exercise\ExerciseResource;

use Doctrine\ORM\QueryBuilder;
use SimpleIT\ClaireExerciseBundle\Entity\ExerciseResource\ExerciseResource;
use SimpleIT\ClaireExerciseBundle\Entity\ExerciseResource\Metadata;
use SimpleIT\ClaireExerciseBundle\Repository\Exercise\SharedEntity\SharedMetadataRepository;
use SimpleIT\CoreBundle\Exception\NonExistingObjectException;
use SimpleIT\Utils\Collection\CollectionInformation;
use SimpleIT\Utils\Collection\PaginatorInterface;
use SimpleIT\Utils\Collection\Sort;

/**
 * Resource Metadata repository
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class MetadataRepository extends SharedMetadataRepository
{
    const METADATA_TABLE = 'claire_exercise_resource_metadata';

    const ENTITY_ID_FIELD_NAME = 'resource_id';
    /**
     * Find a model by id
     *
     * @param array $parameters
     *
     * @return Metadata
     * @throws NonExistingObjectException
     */
    public function find($parameters)
    {
        $metadata = parent::find($parameters);
        if ($metadata === null) {
            throw new NonExistingObjectException();
        }

        return $metadata;
    }

    /**
     * Return all the metadata
     *
     * @param CollectionInformation $collectionInformation
     * @param ExerciseResource      $resource
     *
     * @return PaginatorInterface
     */
    public function findAllBy(
        $collectionInformation = null,
        $resource = null
    )
    {
        return parent::findAllByEntityName('resource', $collectionInformation, $resource);
    }
}
