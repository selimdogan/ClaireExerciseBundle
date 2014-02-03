<?php

namespace SimpleIT\ClaireAppBundle\Form\Course\Model;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class PartTitleModel
{
    /**
     * @var string
     */
    private $title;

    public function __construct($title = null)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }
}
