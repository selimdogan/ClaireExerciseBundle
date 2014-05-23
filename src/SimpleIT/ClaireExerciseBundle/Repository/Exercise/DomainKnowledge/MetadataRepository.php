<?php

namespace SimpleIT\ClaireExerciseBundle\Repository\Exercise\DomainKnowledge;

use SimpleIT\ClaireExerciseBundle\Entity\DomainKnowledge\Knowledge;
use SimpleIT\ClaireExerciseBundle\Entity\DomainKnowledge\Metadata;
use SimpleIT\ClaireExerciseBundle\Repository\Exercise\SharedEntity\SharedMetadataRepository;
use SimpleIT\CoreBundle\Exception\NonExistingObjectException;
use SimpleIT\Utils\Collection\CollectionInformation;
use SimpleIT\Utils\Collection\PaginatorInterface;

/**
 * Knowledge Metadata repository
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class MetadataRepository extends SharedMetadataRepository
{
    const METADATA_TABLE = 'claire_exercise_knowledge_metadata';

    const ENTITY_ID_FIELD_NAME = 'knowledge_id';
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
     * @param Knowledge             $knowledge
     *
     * @return PaginatorInterface
     */
    public function findAllBy(
        $collectionInformation = null,
        $knowledge = null
    )
    {
        return parent::findAllByEntityName('knowledge', $collectionInformation, $knowledge);
    }

    /**
     * Delete all the metadata for a knowledge
     *
     * @param int $knowledgeId
     */
    public function deleteAllByKnowledge($knowledgeId)
    {
        parent::deleteAllByEntityByType(
            $knowledgeId,
            'claire_exercise_knowledge_metadata',
            'knowledge_id'
        );
    }
}
