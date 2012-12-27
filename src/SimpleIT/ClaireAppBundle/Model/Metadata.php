<?php
namespace SimpleIT\ClaireAppBundle\Model;
/**
 * Class Metadata
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class Metadata
{
    /** the metadata key for icon */
    const COURSE_METADATA_IMAGE = 'image';

    /** the metadata key for duration */
    const COURSE_METADATA_DIFFICULTY = 'difficulty';

    /** the metadata key for aggregate rating */
    const COURSE_METADATA_AGGREGATE_RATING = 'aggregateRating';

    /** the metadata key for duration */
    const COURSE_METADATA_DURATION = 'duration';

    /** the metadata key for license */
    const COURSE_METADATA_LICENSE = 'license';

    /** the metadata key for license */
    const COURSE_METADATA_DESCRIPTION = 'description';

    /** @var string $key key */
    private $key;

    /** @var mixed $value value */
    private $value;

    /**
     * Getter for $key
     *
     * @return string the $key
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Setter for $key
     *
     * @param string $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * Getter for $value
     *
     * @return string the $value
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Setter for $value
     *
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

}
