<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\Course\DTO;

use OC\CLAIRE\BusinessRules\Responders\Course\Course\GetCourseResponse;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetCourseResponseDTO implements GetCourseResponse
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
     * @var int
     */
    public $displayLevel;

    /**
     * @var \DateInterval
     */
    public $duration;

    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $image;

    /**
     * @var string
     */
    public $license;

    /**
     * @var string
     */
    public $slug;

    /**
     * @var string
     */
    public $status;

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
     * @return int
     */
    public function getDisplayLevel()
    {
        return $this->displayLevel;
    }

    /**
     * @return \DateInterval
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
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @return string
     */
    public function getLicense()
    {
        return $this->license;
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
    public function getStatus()
    {
        return $this->status;
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
