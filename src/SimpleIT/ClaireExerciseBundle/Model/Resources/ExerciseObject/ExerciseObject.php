<?php

namespace SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseObject;

use JMS\Serializer\Annotation as Serializer;
use SimpleIT\ClaireExerciseBundle\Model\Resources\DomainKnowledge\Formula\LocalFormula;

/**
 * Abstract class for exercise objects (pictures, texts, ...)
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 * @Serializer\Discriminator(field = "object_type", map = {
 *    "picture": "SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseObject\ExercisePictureObject",
 *    "text": "SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseObject\ExerciseTextObject",
 *    "sequence": "SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseObject\ExerciseSequenceObject"
 * })
 */
abstract class ExerciseObject
{
    /**
     * @var array $metadata An array of metadata: key => value
     * @Serializer\Type("array")
     * @Serializer\Groups({"details", "corrected", "not_corrected"})
     */
    protected $metadata;

    /**
     * A value than can be used to describe the object (classify, order, ...)
     *
     * @var string
     * @Serializer\Type("string")
     * @Serializer\Groups({"details", "corrected", "not_corrected"})
     */
    protected $metavalue = null;

    /**
     * @var LocalFormula A LocalFormula
     * @Serializer\Type("SimpleIT\ClaireExerciseBundle\Model\Resources\DomainKnowledge\Formula\LocalFormula")
     * @Serializer\Groups({"details", "exercise_model_storage"})
     */
    protected $formula;

    /**
     * Get metadata
     *
     * @return array
     */
    public function getMetadata()
    {
        return $this->metadata;
    }

    /**
     * Get metadata by key
     *
     * @param $key
     *
     * @return string The value
     */
    public function getMetadataByKey($key)
    {
        return $this->metadata[$key];
    }

    /**
     * Set metadata
     *
     * @param array $metadata
     */
    public function setMetadata($metadata)
    {
        $this->metadata = $metadata;
    }

    /**
     * Add a $key - $value metadata
     *
     * @param string $key
     * @param string $value
     */
    public function addMetadata($key, $value)
    {
        $this->metadata[$key] = $value;
    }

    /**
     * Set metavalue
     *
     * @param string $metavalue
     */
    public function setMetavalue($metavalue)
    {
        $this->metavalue = $metavalue;
    }

    /**
     * Get metavalue
     *
     * @return string
     */
    public function getMetavalue()
    {
        return $this->metavalue;
    }

    /**
     * Set formula
     *
     * @param \SimpleIT\ClaireExerciseBundle\Model\Resources\DomainKnowledge\Formula\LocalFormula $formula
     */
    public function setFormula($formula)
    {
        $this->formula = $formula;
    }

    /**
     * Get formula
     *
     * @return \SimpleIT\ClaireExerciseBundle\Model\Resources\DomainKnowledge\Formula\LocalFormula
     */
    public function getFormula()
    {
        return $this->formula;
    }
}
