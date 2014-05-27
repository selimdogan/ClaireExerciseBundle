<?php

namespace SimpleIT\ClaireExerciseBundle\Service\Exercise\ExerciseResource;

use SimpleIT\ClaireExerciseBundle\Service\Exercise\SharedEntity\SharedMetadataService;

/**
 * Service which manages the metadata for resources
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class MetadataService extends SharedMetadataService
{
    const ENTITY_NAME = 'resource';
}
