<?php

namespace SimpleIT\ClaireAppBundle\Form\Course\Model;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class DisplayLevelModel
{
    /**
     * @var int
     */
    private $displayLevel;

    public function __construct($displayLevel = null)
    {
        $this->displayLevel = $displayLevel;
    }

    /**
     * @return int
     */
    public function getDisplayLevel()
    {
        return $this->displayLevel;
    }

    public function setDisplayLevel($displayLevel)
    {
        $this->displayLevel = $displayLevel;
    }
}
