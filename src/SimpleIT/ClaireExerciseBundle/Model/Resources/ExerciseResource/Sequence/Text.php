<?php

namespace SimpleIT\ClaireExerciseResourceBundle\Model\Resources\ExerciseResource\Sequence;

use JMS\Serializer\Annotation as Serializer;
use SimpleIT\ClaireExerciseBundle\Exception\InvalidExerciseResourceException;
use SimpleIT\ClaireExerciseResourceBundle\Model\Resources\ExerciseResource\SequenceResource;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Text
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class Text extends SequenceElement
{
    /**
     * @var string
     * @Serializer\Type("string")
     * @Serializer\Groups({"details", "resource_storage"})
     * @Assert\NotBlank(groups={"create"})
     */
    private $text;

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
     * Validate text
     *
     * @throws InvalidExerciseResourceException
     */
    public function  validate($param = null)
    {
        if ($param != SequenceResource::TEXT) {
            throw new InvalidExerciseResourceException('Only a sequence of manual texts can contain text elements');
        }

        if (is_null($this->text) || $this->text == "") {
            throw new InvalidExerciseResourceException('Text can not be empty in sequence');
        }
    }
}
