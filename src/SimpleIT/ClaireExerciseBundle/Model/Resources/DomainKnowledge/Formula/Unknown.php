<?php

namespace SimpleIT\ClaireExerciseBundle\Model\Resources\DomainKnowledge\Formula;

use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Unknown. Used in formula
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class Unknown
{
    /**
     * @const INTEGER = 'integer'
     */
    const INTEGER = 'integer';

    /**
     * @const FLOAT = 'float'
     */
    const FLOAT = 'float';

    /**
     * @const SCIENTIFIC = 'scientific'
     */
    const SCIENTIFIC = 'scientific';

    /**
     * @var string $name
     * @Serializer\Type("string")
     * @Serializer\Groups({"details", "knowledge_storage", "resource_storage", "exercise_model_storage"})
     * @Assert\NotBlank(groups={"create"})
     */
    protected $name;

    /**
     * @var string $type
     * @Serializer\Type("string")
     * @Serializer\Groups({"details", "knowledge_storage", "resource_storage", "exercise_model_storage"})
     * @Assert\NotBlank(groups={"create"})
     */
    protected $type;

    /**
     * @var int $digitsAfterPoint
     * @Serializer\Type("integer")
     * @Serializer\Groups({"details", "knowledge_storage", "resource_storage", "exercise_model_storage"})
     */
    protected $digitsAfterPoint;

    /**
     * Set digitsAfterPoint
     *
     * @param int $digitsAfterPoint
     */
    public function setDigitsAfterPoint($digitsAfterPoint)
    {
        $this->digitsAfterPoint = $digitsAfterPoint;
    }

    /**
     * Get digitsAfterPoint
     *
     * @return int
     */
    public function getDigitsAfterPoint()
    {
        return $this->digitsAfterPoint;
    }

    /**
     * Set name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set type
     *
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
}
