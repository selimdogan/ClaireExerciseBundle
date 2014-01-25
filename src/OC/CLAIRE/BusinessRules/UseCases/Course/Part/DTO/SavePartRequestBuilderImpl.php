<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\Part\DTO;

use OC\CLAIRE\BusinessRules\Requestors\Course\Part\SavePartRequestException;
use OC\CLAIRE\BusinessRules\Requestors\RequestBuilder;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class SavePartRequestBuilderImpl implements RequestBuilder
{
    /**
     * @var SavePartRequestDTO
     */
    private $savePartRequest;

    private function __construct()
    {
        $this->savePartRequest = new SavePartRequestDTO();
    }

    public static function create()
    {
        return new SavePartRequestBuilderImpl();
    }

    public function part($partId)
    {
        $this->savePartRequest->partId = $partId;

        return $this;
    }

    public function fromCourse($courseId)
    {
        $this->savePartRequest->courseId = $courseId;

        return $this;
    }

    public function withDescription($description)
    {
        $this->savePartRequest->partDescription = $description;

        return $this;
    }

    public function withDifficulty($difficulty)
    {
        $this->savePartRequest->partDifficulty = $difficulty;

        return $this;
    }

    public function withDuration($duration)
    {
        $this->savePartRequest->partDuration = $duration;

        return $this;
    }

    public function build()
    {
        if (null === $this->savePartRequest->courseId && null === $this->savePartRequest->partId) {
            throw new SavePartRequestException('Course id and part id are required');
        } elseif (null === $this->savePartRequest->courseId) {
            throw new SavePartRequestException('Course id is required');
        } elseif (null === $this->savePartRequest->partId) {
            throw new SavePartRequestException('Part id is required');
        }

        return $this->savePartRequest;
    }
}
