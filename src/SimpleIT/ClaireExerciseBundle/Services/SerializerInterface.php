<?php


namespace SimpleIT\ClaireExerciseBundle\Services;

use JMS\Serializer\Handler\HandlerRegistry;
use JMS\Serializer\SerializationContext;

/**
 * Interface for Serializer
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
interface SerializerInterface
{
    /**
     * Check is format is serializable / deserializable
     *
     * @param string $format Format
     *
     * @return bool
     */
    public static function isFormatSerializable($format);

    /**
     * Serializes the given data to the specified output format.
     *
     * @param object|array $data   Data
     * @param string       $format Format
     * @param array        $groups Groups of allowed fields
     *
     * @return string
     */
    public function serialize($data, $format = null, array $groups = array());

    /**
     * Deserializes the given data to the specified type.
     *
     * @param string $data   Data
     * @param string $type   Type
     * @param string $format Format
     *
     * @return object|array
     */
    public function deserialize($data, $type, $format = null);

    /**
     * Get defaultFormat
     *
     * @return string
     * @codeCoverageIgnore
     */
    public function getDefaultFormat();

    /**
     * Set defaultFormat
     *
     * @param string $defaultFormat
     *
     * @codeCoverageIgnore
     */
    public function setDefaultFormat($defaultFormat);

    /**
     * Get serializer
     *
     * @return \JMS\Serializer\SerializerInterface
     * @codeCoverageIgnore
     */
    public function getSerializer();

    /**
     * Set serializer
     *
     * @param \JMS\Serializer\SerializerInterface $serializer
     *
     * @codeCoverageIgnore
     */
    public function setSerializer($serializer);
}
