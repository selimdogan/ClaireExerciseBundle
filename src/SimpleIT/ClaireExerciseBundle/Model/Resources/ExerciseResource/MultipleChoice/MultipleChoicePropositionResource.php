<?php

namespace SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseResource\MultipleChoice;

use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class MultipleChoicePropositionResource
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class MultipleChoicePropositionResource
{
    /**
     * @var string $text
     * @Serializer\Type("string")
     * @Serializer\Groups({"details", "resource_storage"})
     * @Assert\NotBlank(groups={"create"})
     */
    private $text;

    /**
     * @var boolean $right
     * @Serializer\Type("boolean")
     * @Serializer\Groups({"details", "resource_storage"})
     * @Assert\NotBlank(groups={"create"})
     */
    private $right;

    /**
     * @var boolean $forceUse
     * @Serializer\Type("boolean")
     * @Serializer\Groups({"details", "resource_storage"})
     * @Assert\NotBlank(groups={"create"})
     */
    private $forceUse;

    /**
     * Set right
     *
     * @param boolean $right
     */
    public function setRight($right)
    {
        $this->right = $right;
    }

    /**
     * Get right
     *
     * @return boolean
     */
    public function getRight()
    {
        return $this->right;
    }

    /**
     * Set text
     *
     * @param string $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set forceUse
     *
     * @param boolean $forceUse
     */
    public function setForceUse($forceUse)
    {
        $this->forceUse = $forceUse;
    }

    /**
     * Get forceUse
     *
     * @return boolean
     */
    public function getForceUse()
    {
        return $this->forceUse;
    }
}
