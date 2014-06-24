<?php

namespace SimpleIT\ClaireExerciseBundle\Model\Resources;

use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * The learner answer as sent to the API
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class AnswerResource
{
    /**
     * @const RESOURCE_NAME = 'Answer'
     */
    const RESOURCE_NAME = 'Answer';

    /**
     * @var int
     * @Serializer\Type("integer")
     * @Serializer\Groups({"details", "list"})
     * @Assert\Blank()
     */
    private $id;

    /**
     * @var array $content
     * @Serializer\Type("array")
     * @Serializer\Groups({"details", "answer_storage"})
     * @Assert\NotBlank(groups={"create"})
     */
    private $content;

    /**
     * Set id
     *
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set content
     *
     * @param array $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * Get content
     *
     * @return array
     */
    public function getContent()
    {
        return $this->content;
    }
}
