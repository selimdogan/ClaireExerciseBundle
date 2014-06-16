<?php

namespace SimpleIT\ClaireExerciseBundle\Model\Resources;

use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class MetadataResource
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class MetadataResource
{
    /**
     * @const RESOURCE_NAME = 'Metadata'
     */
    const RESOURCE_NAME = 'Metadata';

    /**
     * @const MISC_METADATA_KEY = '_misc'
     */
    const MISC_METADATA_KEY = '_misc';

    /**
     * @var string
     * @Serializer\Type("string")
     * @Serializer\Groups({"list","details", "resource_list"})
     * @Assert\NotBlank(groups={"create"})
     * @Assert\Blank(groups={"edit"})
     */
    private $key;

    /**
     * @var string
     * @Serializer\Type("string")
     * @Serializer\Groups({"list","details", "resource_list"})
     * @Assert\NotBlank(groups={"create","edit"})
     */
    private $value;

    /**
     * Get key
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Set key
     *
     * @param string $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set value
     *
     * @param string $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }
}
