<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\Course\DTO;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetCourseResponseBuilderImpl
{
    /**
     * @var GetCourseResponseDTO
     */
    private $getCourseResponse;

    private function __construct()
    {
        $this->getCourseResponse = new GetCourseResponseDTO();
    }

    public static function create()
    {
        return new GetCourseResponseBuilderImpl();
    }

    public function build()
    {
        return $this->getCourseResponse;
    }

    public function created(\DateTime $createdAt = null)
    {
        $this->getCourseResponse->createdAt = $createdAt;

        return $this;
    }

    public function withDescription($description)
    {
        $this->getCourseResponse->description = $description;

        return $this;
    }

    public function withDifficulty($difficulty)
    {
        $this->getCourseResponse->difficulty = $difficulty;

        return $this;
    }

    public function withDisplayLevel($displayLevel)
    {
        $this->getCourseResponse->displayLevel = $displayLevel;

        return $this;
    }

    public function withDuration($duration)
    {
        if (null !== $duration) {
            $this->getCourseResponse->duration = new \DateInterval($duration);
        }

        return $this;
    }

    public function course($id)
    {
        $this->getCourseResponse->id = $id;

        return $this;
    }

    public function withImage($image)
    {
        $this->getCourseResponse->image = $image;

        return $this;
    }

    public function withLicense($license)
    {
        $this->getCourseResponse->license = $license;

        return $this;
    }

    public function rated($rating)
    {
        $this->getCourseResponse->rating = $rating;

        return $this;
    }

    public function withSlug($slug)
    {
        $this->getCourseResponse->slug = $slug;

        return $this;
    }

    public function withStatus($status)
    {
        $this->getCourseResponse->status = $status;

        return $this;
    }

    public function named($title)
    {
        $this->getCourseResponse->title = $title;

        return $this;
    }

    public function updated($updatedAt)
    {
        $this->getCourseResponse->updatedAt = $updatedAt;

        return $this;
    }
}
