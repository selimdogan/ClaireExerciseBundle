<?php

namespace OC\CLAIRE\BusinessRules\Gateways\Course\Part;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
interface PartContentGateway
{
    /**
     * @return string
     */
    public function findPublished($courseIdentifier, $partIdentifier);

    /**
     * @return string
     */
    public function findWaitingForPublication($courseId, $partId);

    /**
     * @return string
     */
    public function findDraft($courseId, $partId);

    /**
     * @return string
     */
    public function update($courseId, $partId, $content);
}
