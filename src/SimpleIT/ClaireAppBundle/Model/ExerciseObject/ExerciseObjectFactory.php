<?php

namespace SimpleIT\ExerciseBundle\Model\ExerciseObject;

use Doctrine\Common\Collections\Collection;
use SimpleIT\ApiResourcesBundle\Exercise\ExerciseObject\ExerciseObject;
use SimpleIT\ApiResourcesBundle\Exercise\ExerciseResource\CommonResource;
use SimpleIT\ApiResourcesBundle\Exercise\ExerciseResource\MultipleChoiceQuestionResource;
use SimpleIT\ApiResourcesBundle\Exercise\ExerciseResource\OpenEndedQuestionResource;
use SimpleIT\ApiResourcesBundle\Exercise\ExerciseResource\PictureResource;
use SimpleIT\ApiResourcesBundle\Exercise\ExerciseResource\SequenceResource;
use SimpleIT\ApiResourcesBundle\Exercise\ExerciseResource\TextResource;
use SimpleIT\ApiResourcesBundle\Exercise\ResourceResource;
use SimpleIT\ExerciseBundle\Entity\ExerciseResource\Metadata;
use SimpleIT\ExerciseBundle\Exception\InvalidTypeException;
use SimpleIT\ExerciseBundle\Model\ExerciseObject\MultipleChoiceQuestionFactory;

/**
 * Factory to create ExerciseObject from resources.
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
abstract class ExerciseObjectFactory
{
    /**
     * Translate a CommonExercise + metadata into an ExerciseObject
     *
     * @param CommonResource $resource
     * @param Collection     $metadata
     * @param array          $requiredResource
     *
     * @throws \SimpleIT\ExerciseBundle\Exception\InvalidTypeException
     * @return ExerciseObject The output exercise object
     */
    public static function createExerciseObject(
        CommonResource $resource,
        Collection $metadata = null,
        array $requiredResource = null
    )
    {
        // Depending on the type of the resource, call to the right factory
        switch (get_class($resource)) {
            case ResourceResource::MULTIPLE_CHOICE_QUESTION_CLASS:
                /** @var MultipleChoiceQuestionResource $resource */
                $object = MultipleChoiceQuestionFactory::createFromCommonResource($resource);
                break;
            case ResourceResource::OPEN_ENDED_QUESTION:
                /** @var OpenEndedQuestionResource $resource */
                $object = OpenEndedQuestionFactory::createFromCommonResource($resource);
                break;
            case ResourceResource::TEXT_CLASS:
                /** @var TextResource $resource */
                $object = ExerciseTextFactory::createFromCommonResource($resource);
                break;

            case ResourceResource::PICTURE_CLASS:
                /** @var PictureResource $resource */
                $object = ExercisePictureFactory::createFromCommonResource($resource);
                break;

            case ResourceResource::SEQUENCE_CLASS:
                /** @var SequenceResource $resource */
                $object = ExerciseSequenceFactory::createFromCommonResource(
                    $resource,
                    $requiredResource
                );
                break;
            default:
                throw new InvalidTypeException(
                    'Resource type is incorrect: ' . get_class($resource)
                );
        }

        // add the metadata
        if ($metadata !== null) {
            foreach ($metadata as $md) {
                /** @var Metadata $md */
                $object->addMetadata($md->getKey(), $md->getValue());
            }
        }

        if ($resource->getFormula() !== null) {
            $object->setFormula($resource->getFormula());
        }

        return $object;
    }
}