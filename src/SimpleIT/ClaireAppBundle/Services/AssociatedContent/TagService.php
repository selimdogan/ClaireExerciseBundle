<?php

namespace SimpleIT\ClaireAppBundle\Services\AssociatedContent;

use SimpleIT\ClaireAppBundle\Repository\AssociatedContent\TagByPartRepository;

/**
 * Class TagService
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class TagService
{
    /**
     * @var  TagByPartRepository
     */
    private $tagByPartRepository;

    /**
     * Set tagByPartRepository
     *
     * @param \SimpleIT\ClaireAppBundle\Repository\AssociatedContent\TagByPartRepository $tagByPartRepository
     */
    public function setTagByPartRepository($tagByPartRepository)
    {
        $this->tagByPartRepository = $tagByPartRepository;
    }

    /**
     * Get all the tags of a part
     *
     * @param integer | string $courseIdentifier Course id | slug
     * @param integer | string $partIdentifier   Part id | slug
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAllByPart($courseIdentifier, $partIdentifier)
    {
        return $this->tagByPartRepository->findAll($courseIdentifier, $partIdentifier);
    }
}
