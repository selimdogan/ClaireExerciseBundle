<?php
/*
 * Copyright 2013 Simple IT
 *
 * This file is part of CLAIRE.
 *
 * CLAIRE is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * CLAIRE is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with CLAIRE. If not, see <http://www.gnu.org/licenses/>
 */

namespace SimpleIT\ClaireAppBundle\Services\Course;

use SimpleIT\ApiResourcesBundle\Course\PartResource;
use SimpleIT\ClaireAppBundle\Model\Course\Part;
use SimpleIT\ClaireAppBundle\Repository\Course\PartContentRepository;
use SimpleIT\ClaireAppBundle\Repository\Course\PartIntroductionRepository;
use SimpleIT\ClaireAppBundle\Repository\Course\PartRepository;
use SimpleIT\ClaireAppBundle\Repository\Course\PartTocRepository;
use SimpleIT\ClaireAppBundle\Repository\Course\CourseTocRepository;
use SimpleIT\Utils\NumberUtils;

/**
 * Class PartService
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class PartService
{

    /**
     * @var  PartRepository
     */
    private $partRepository;

    /**
     * @var  PartIntroductionRepository
     */
    private $partIntroductionRepository;

    /**
     * @var  PartContentRepository
     */
    private $partContentRepository;

    /**
     * @var  PartTocRepository
     */
    private $partTocRepository;

    /**
     * @var  CourseTocRepository
     */
    private $courseTocRepository;

    /**
     * Set partRepository
     *
     * @param \SimpleIT\ClaireAppBundle\Repository\Course\PartRepository $partRepository
     */
    public function setPartRepository($partRepository)
    {
        $this->partRepository = $partRepository;
    }

    /**
     * Set partIntroductionRepository
     *
     * @param \SimpleIT\ClaireAppBundle\Repository\Course\PartIntroductionRepository $partIntroductionRepository
     */
    public function setPartIntroductionRepository($partIntroductionRepository)
    {
        $this->partIntroductionRepository = $partIntroductionRepository;
    }

    /**
     * Set partContentRepository
     *
     * @param \SimpleIT\ClaireAppBundle\Repository\Course\PartContentRepository $partContentRepository
     */
    public function setPartContentRepository($partContentRepository)
    {
        $this->partContentRepository = $partContentRepository;
    }

    /**
     * Set partTocRepository
     *
     * @param \SimpleIT\ClaireAppBundle\Repository\Course\PartTocRepository $partTocRepository
     */
    public function setPartTocRepository($partTocRepository)
    {
        $this->partTocRepository = $partTocRepository;
    }

    /**
     * Set courseTocRepository
     *
     * @param \SimpleIT\ClaireAppBundle\Repository\Course\CourseTocRepository $courseTocRepository
     */
    public function setCourseTocRepository($courseTocRepository)
    {
        $this->courseTocRepository = $courseTocRepository;
    }

    /**
     * Get a part
     *
     * @param string | integer $courseIdentifier Course id | slug
     * @param string | integer $partIdentifier   Part id | slug
     *
     * @return PartResource
     */
    public function get($courseIdentifier, $partIdentifier)
    {
        return $this->partRepository->find($courseIdentifier, $partIdentifier);
    }

    /**
     * Update a part
     *
     * @param string       $courseIdentifier Course id | slug
     * @param string       $partIdentifier   Part id | slug
     * @param PartResource $part             Part
     *
     * @return PartResource
     */
    public function save($courseIdentifier, $partIdentifier, $part)
    {
        return $this->partRepository->update($courseIdentifier, $partIdentifier, $part);
    }

    /**
     * Save a part content
     *
     * @param integer $courseId Course id
     * @param integer $partId   Part id
     * @param mixed   $content  Content
     *
     * @return mixed
     */
    public function saveContent($courseId, $partId, $content)
    {
        return $this->partContentRepository->update($courseId, $partId, $content);
    }

    /**
     * Get a course introduction
     *
     * @param int | string $courseIdentifier Course id | slug
     * @param integer      $partIdentifier   Part id | slug
     *
     * @return mixed
     */
    public function getIntroduction($courseIdentifier, $partIdentifier)
    {
        return $this->partIntroductionRepository->find($courseIdentifier, $partIdentifier);
    }

    /**
     * Get a part content
     *
     * @param integer $courseIdentifier Course id | slug
     * @param integer $partIdentifier   Part id | slug
     *
     * @return mixed
     */
    public function getContent($courseIdentifier, $partIdentifier)
    {
        return $this->partContentRepository->find($courseIdentifier, $partIdentifier);
    }

    /**
     * Get a part toc
     *
     * @param int | string $courseIdentifier Course id | slug
     * @param int | string $partIdentifier Part id | slug
     *
     * @return mixed
     */
    public function getToc($courseIdentifier, $partIdentifier)
    {
        return $this->partTocRepository->find($courseIdentifier, $partIdentifier);
    }

    /**
     * Get parents part from a part (included itself)
     *
     * @param int | string $courseIdentifier Course id | slug
     * @param int | string $partIdentifier Part id | slug
     *
     * @return array
     */
    public function getParents($courseIdentifier, $partIdentifier)
    {
        $courseToc = $this->courseTocRepository->find($courseIdentifier);
        $partParents = array();

        $this->scanToc($partParents, $courseToc, $partIdentifier);

        return $partParents;
    }

    /**
     * Scan complete toc and retrieve parents
     *
     * @param array        $partParents    Part parents
     * @param Part         $parent         Parent element
     * @param int | string $partIdentifier Part id | slug
     *
     * @return bool
     */
    private function scanToc(&$partParents, $parent, $partIdentifier)
    {
        if ($this->matchPart($parent, $partIdentifier)) {
            return true;
        }

        if ($parent->getChildren()) {
            foreach ($parent->getChildren() as $part) {
                /* Limit deep level */
                if (!in_array(
                    $part->getSubtype(),
                    array(Part::TYPE_TITLE_1,Part::TYPE_TITLE_2,Part::TYPE_TITLE_3)
                )) {
                    return false;
                }

                /* recursive deep scan */
                $found = $this->scanToc($partParents, $part, $partIdentifier);

                if ($found === true) {
                    /* send parent info up */
                    $partParents[] = $part->getId();
                    return true;
                }
            }
        }

        return false;
    }


    /**
     * Match parts
     *
     * @param Part         $parent         Parent element
     * @param int | string $partIdentifier Part id | slug
     *
     * @return bool
     */
    private function matchPart($parent, $partIdentifier)
    {
        return NumberUtils::isInteger($partIdentifier) && $parent->getId() == $partIdentifier ||
        !NumberUtils::isInteger($partIdentifier) && $parent->getSlug() == $partIdentifier;
    }
}
