<?php

namespace SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseResource;

use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class PictureResource
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class PictureResource extends CommonResource
{
    /**
     * @var string $source The source of the picture
     * @Serializer\Type("string")
     * @Serializer\Groups({"details", "resource_storage", "resource_list", "owner_resource_list"})
     * @Assert\NotBlank(groups={"create"})
     */
    private $source;

    /**
     * Set source
     *
     * @param string $source
     */
    public function setSource($source)
    {
        $this->source = $source;
    }

    /**
     * Get source
     *
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Validate the picture
     *
     * @throws \LogicException
     */
    public function validate($param = null)
    {
        if ($this->getSource() === null || $this->getSource() == '') {
            throw new \LogicException('The picture must contain a source.');
        }
    }
}
