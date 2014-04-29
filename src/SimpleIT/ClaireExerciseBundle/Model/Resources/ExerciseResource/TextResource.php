<?php

namespace SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseResource;

use JMS\Serializer\Annotation as Serializer;
use SimpleIT\ClaireExerciseBundle\Exception\InvalidExerciseResourceException;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class TextResource
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class TextResource extends CommonResource
{
    /**
     * @var string $text The text
     * @Serializer\Type("string")
     * @Serializer\Groups({"details", "resource_storage"})
     * @Assert\NotBlank(groups={"create"})
     */
    private $text;

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
     * Set text
     *
     * @param string $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * Validate text resource
     *
     * @throws InvalidExerciseResourceException
     */
    public function  validate($param = null)
    {
        if (is_null($this->text) || $this->text == '') {
            throw new InvalidExerciseResourceException('Invalid Text resource');
        }
    }
}
