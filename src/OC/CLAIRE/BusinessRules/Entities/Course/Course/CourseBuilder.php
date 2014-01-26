<?php

namespace OC\CLAIRE\BusinessRules\Entities\Course\Course;

use SimpleIT\ApiResourcesBundle\Course\CourseResource;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
abstract class CourseBuilder
{
    /**
     * @var CourseResource
     */
    protected $course;

    public function withDescription($description)
    {
        $this->course->setDescription($description);

        return $this;
    }

    public function withDifficulty($difficulty)
    {
        $this->course->setDifficulty($difficulty);

        return $this;
    }

    public function withDisplayLevel($displayLevel)
    {
        $this->course->setDisplayLevel($displayLevel);

        return $this;
    }

    public function withDuration($duration)
    {
        $this->course->setDuration($duration);

        return $this;
    }

    public function withImage($image)
    {
        $this->course->setImage($image);

        return $this;
    }

    public function withTitle($title)
    {
        $this->course->setTitle($title);

        return $this;
    }

    /**
     * @return CourseResource
     */
    public function build()
    {
        return $this->course;
    }
}
