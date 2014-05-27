<?php

namespace SimpleIT\ClaireExerciseBundle\Repository\Exercise\DomainKnowledge;

use SimpleIT\ClaireExerciseBundle\Repository\Exercise\SharedEntity\SharedMetadataRepository;

/**
 * Knowledge Metadata repository
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class MetadataRepository extends SharedMetadataRepository
{
    const METADATA_TABLE = 'claire_exercise_knowledge_metadata';

    const ENTITY_ID_FIELD_NAME = 'knowledge_id';

    const ENTITY_NAME = 'knowledge';

}
