<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\Course\DTO;

use OC\CLAIRE\BusinessRules\Requestors\Course\Course\SaveCourseRequest;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class SaveCourseRequestDTO implements SaveCourseRequest
{
    /**
     * @var string
     */
    public $courseDescription;

    /**
     * @var string
     */
    public $courseDifficulty;

    /**
     * @var string
     */
    public $courseDisplayLevel;

    /**
     * @var string
     */
    public $courseDuration;

    /**
     * @var int
     */
    public $courseId;

    /**
     * @var string
     */
    public $courseImage;

    /**
     * @var string
     */
    public $courseTitle;

    /**
     * @return string
     */
    public function getCourseDescription()
    {
        return $this->courseDescription;
    }

    /**
     * @return string
     */
    public function getCourseDifficulty()
    {
        return $this->courseDifficulty;
    }

    /**
     * @return string
     */
    public function getCourseDisplayLevel()
    {
        return $this->courseDisplayLevel;
    }

    /**
     * @return string
     */
    public function getCourseDuration()
    {
        return $this->courseDuration;
    }

    /**
     * @return int
     */
    public function getCourseId()
    {
        return $this->courseId;
    }

    /**
     * @return string
     */
    public function getCourseImage()
    {
        return $this->courseImage;
    }

    /**
     * @return string
     */
    public function getCourseTitle()
    {
        return $this->courseTitle;
    }
}
