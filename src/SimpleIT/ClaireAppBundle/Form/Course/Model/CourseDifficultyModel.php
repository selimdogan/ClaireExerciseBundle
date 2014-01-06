<?php

namespace SimpleIT\ClaireAppBundle\Form\Course\Model;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class CourseDifficultyModel
{
    /**
     * @var string
     */
    private $difficulty;

    public function __construct($difficulty = null)
    {
        $this->difficulty = $difficulty;
    }

    /**
     * @return string
     */
    public function getDifficulty()
    {
        return $this->difficulty;
    }

    public function setDifficulty($difficulty)
    {
        $this->difficulty = $difficulty;
    }

}
