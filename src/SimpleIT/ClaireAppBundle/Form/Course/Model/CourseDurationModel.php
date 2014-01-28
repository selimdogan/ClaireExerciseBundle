<?php

namespace SimpleIT\ClaireAppBundle\Form\Course\Model;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class CourseDurationModel
{
    /**
     * @var string
     */
    private $duration;

    public function __construct($duration = null)
    {
        $this->duration = $duration;
    }

    /**
     * @return string
     */
    public function getDuration()
    {
        return $this->duration;
    }

    public function setDuration($duration)
    {
        $this->duration = $duration;
    }
}
