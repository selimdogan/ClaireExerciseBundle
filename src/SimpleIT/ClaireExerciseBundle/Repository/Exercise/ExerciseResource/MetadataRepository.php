<?php

namespace SimpleIT\ClaireExerciseBundle\Repository\Exercise\ExerciseResource;

use SimpleIT\ClaireExerciseBundle\Repository\Exercise\SharedEntity\SharedMetadataRepository;

/**
 * Resource Metadata repository
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class MetadataRepository extends SharedMetadataRepository
{
    const METADATA_TABLE = 'claire_exercise_resource_metadata';

    const ENTITY_ID_FIELD_NAME = 'resource_id';

    const ENTITY_NAME = 'resource';

}
