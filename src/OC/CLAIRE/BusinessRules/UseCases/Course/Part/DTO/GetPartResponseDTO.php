<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\Part\DTO;

use OC\CLAIRE\BusinessRules\Responders\Course\Part\GetPartResponse;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetPartResponseDTO implements GetPartResponse
{
    /**
     * @var \DateTime
     */
    public $createdAt;

    /**
     * @var string
     */
    public $description;

    /**
     * @var string
     */
    public $difficulty;

    /**
     * @var string
     */
    public $duration;

    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $slug;

    /**
     * @var string
     */
    public $subtype;

    /**
     * @var string
     */
    public $title;

    /**
     * @var \DateTime
     */
    public $updatedAt;

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getDifficulty()
    {
        return $this->difficulty;
    }

    /**
     * @return string
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @return string
     */
    public function getSubtype()
    {
        return $this->subtype;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

}
