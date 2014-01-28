<?php

namespace OC\CLAIRE\BusinessRules\Gateways\Course\Part;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class PartContentGatewaySpy implements PartContentGateway
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
     * @var int
     */
    public $partId;

    /**
     * @var string
     */
    public $content;

    /**
     * @return string
     */
    public function findPublished($courseIdentifier, $partIdentifier)
    {
        return self::PUBLISHED_CONTENT;
    }

    /**
     * @return string
     */
    public function findWaitingForPublication($courseId, $partId)
    {
        return self::WAITING_FOR_PUBLICATION_CONTENT;
    }

    /**
     * @return string
     */
    public function findDraft($courseId, $partId)
    {
        return self::DRAFT_CONTENT;
    }

    /**
     * @return string
     */
    public function update($courseId, $partId, $content)
    {
        $this->courseId = $courseId;
        $this->partId = $partId;
        $this->content = $content;

        return self::UPDATED_CONTENT;
    }
}
