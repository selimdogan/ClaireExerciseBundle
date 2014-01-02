<?php

namespace OC\BusinessRules\UseCases\Course\Course\DTO;

use OC\BusinessRules\Responders\Course\Course\GetCourseResponse;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetCourseResponseDTO implements GetCourseResponse
{
    /**
     * @var int
     */
    public $id;

    /**
     * string
     */
    public $slug;

    /**
     * @var string
     */
    public $title;

    /**
     * @var int
     */
    public $displayLevel;

    /**
     * @var string
     */
    public $status;

    /**
     * @var \DateTime
     */
    public $createdAt;

    /**
     * @var \DateTime
     */
    public $updatedAt;

    public function __construct(
        $id,
        $slug,
        $status,
        $title,
        $displayLevel,
        $createdAt,
        $updatedAt
    )
    {
        $this->id = $id;
        $this->status = $status;
        $this->title = $title;
        $this->createdAt = $createdAt;
        $this->displayLevel = $displayLevel;
        $this->updatedAt = $updatedAt;
        $this->slug = $slug;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return int
     */
    public function getDisplayLevel()
    {
        return $this->displayLevel;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
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
