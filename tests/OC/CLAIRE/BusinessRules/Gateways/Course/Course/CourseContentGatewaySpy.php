<?php

namespace OC\CLAIRE\BusinessRules\Gateways\Course\Course;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class CourseContentGatewaySpy implements CourseContentGateway
{
    const DRAFT_CONTENT = '<p>Draft content</p>';

    const WAITING_FOR_PUBLICATION_CONTENT = '<p>Waiting for publication content</p>';

    const PUBLISHED_CONTENT = '<p>Published content</p>';

    const UPDATED_CONTENT = '<p>Updated content</p>';

    /**
     * @var int
     */
    public $courseId;

    /**
     * @var string
     */
    public $content;

    /**
     * @return string
     */
    public function findPublished($courseIdentifier)
    {
        return self::PUBLISHED_CONTENT;
    }

    /**
     * @return string
     */
    public function findWaitingForPublication($courseId)
    {
        return self::WAITING_FOR_PUBLICATION_CONTENT;
    }

    /**
     * @return string
     */
    public function findDraft($courseId)
    {
        return self::DRAFT_CONTENT;
    }

    /**
     * @return string
     */
    public function update($courseId, $content)
    {
        $this->courseId = $courseId;
        $this->content = $content;

        return self::UPDATED_CONTENT;
    }

}
