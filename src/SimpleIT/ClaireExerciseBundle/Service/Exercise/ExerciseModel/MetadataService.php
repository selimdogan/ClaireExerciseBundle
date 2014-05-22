<?php

namespace SimpleIT\ClaireExerciseBundle\Service\Exercise\ExerciseModel;

use SimpleIT\ClaireExerciseBundle\Model\Resources\MetadataResource;
use SimpleIT\ClaireExerciseBundle\Service\Exercise\ExerciseResource\SharedMetadataService;
use SimpleIT\CoreBundle\Services\TransactionalService;
use SimpleIT\ClaireExerciseBundle\Entity\ExerciseModel\Metadata;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ResourceResourceFactory;
use SimpleIT\ClaireExerciseBundle\Repository\Exercise\ExerciseModel\MetadataRepository;
use SimpleIT\Utils\Collection\CollectionInformation;
use SimpleIT\CoreBundle\Annotation\Transactional;

/**
 * Service which manages the metadata for exercise models
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class MetadataService extends SharedMetadataService
{

}
