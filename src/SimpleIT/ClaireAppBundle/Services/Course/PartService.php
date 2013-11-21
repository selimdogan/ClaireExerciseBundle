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

use SimpleIT\ApiResourcesBundle\Course\CourseResource;
use SimpleIT\ApiResourcesBundle\Course\PartResource;
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
     * Get a part by status
     *
     * @param string | integer $courseIdentifier Course id | slug
     * @param string | integer $partIdentifier   Part id | slug
     * @param string           $status           Status
     *
     * @return PartResource
     */
    public function getByStatus($courseIdentifier, $partIdentifier, $status)
    {
        return $this->partRepository->findByStatus(
            $courseIdentifier,
            $partIdentifier,
            array(CourseResource::STATUS => $status)
        );
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
    public function save($courseIdentifier, $partIdentifier, $part, $status)
    {
        return $this->partRepository->update($courseIdentifier, $partIdentifier, $part, $status);
    }

    /**
     * Save a part content
     *
     * @param integer $courseId Course id
     * @param integer $partId   Part id
     * @param mixed   $content  Content
     * @param string  $status   Status
     *
     * @return mixed
     */
    public function saveContent($courseId, $partId, $content, $status)
    {
        return $this->partContentRepository->update(
            $courseId,
            $partId,
            $content,
            array('status' => $status)
        );
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
     * Get a course introduction
     *
     * @param int | string $courseIdentifier Course id | slug
     * @param integer      $partIdentifier   Part id | slug
     * @param string       $status           Asked status
     *
     * @return mixed
     */
    public function getIntroductionByStatus($courseIdentifier, $partIdentifier, $status)
    {
        return $this->partIntroductionRepository->findByStatus(
            $courseIdentifier,
            $partIdentifier,
            array(CourseResource::STATUS => $status)
        );
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
     * Get a part content
     *
     * @param integer $courseIdentifier Course id | slug
     * @param integer $partIdentifier   Part id | slug
     * @param string  $status           Asked status
     *
     * @return mixed
     * @deprecated use getContentByStatus
     */
    public function getContentToEdit($courseIdentifier, $partIdentifier, $status)
    {
        return $this->partContentRepository->findByStatus(
            $courseIdentifier,
            $partIdentifier,
            array(CourseResource::STATUS => $status)
        );
    }

    /**
     * Get a part content
     *
     * @param integer $courseIdentifier Course id | slug
     * @param integer $partIdentifier   Part id | slug
     * @param string  $status           Asked status
     *
     * @return mixed
     */
    public function getContentByStatus($courseIdentifier, $partIdentifier, $status)
    {
        return $this->partContentRepository->findByStatus(
            $courseIdentifier,
            $partIdentifier,
            array(CourseResource::STATUS => $status)
        );
    }

    /**
     * Get a part toc
     *
     * @param int | string $courseIdentifier Course id | slug
     * @param int | string $partIdentifier   Part id | slug
     *
     * @return mixed
     */
    public function getToc($courseIdentifier, $partIdentifier)
    {
        return $this->partTocRepository->find($courseIdentifier, $partIdentifier);
    }

    /**
     * Get a part toc
     *
     * @param int | string $courseIdentifier Course id | slug
     * @param int | string $partIdentifier   Part id | slug
     * @param string       $status           Asked status
     *
     * @return mixed
     */
    public function getTocByStatus($courseIdentifier, $partIdentifier, $status)
    {
        return $this->partTocRepository->findByStatus(
            $courseIdentifier,
            $partIdentifier,
            array(CourseResource::STATUS => $status)
        );
    }

    /**
     * Get parents part from a part (included itself)
     *
     * @param int | string $courseIdentifier Course id | slug
     * @param int | string $partIdentifier   Part id | slug
     *
     * @return array
     */
    public function getParents($courseIdentifier, $partIdentifier)
    {
        $courseToc = $this->courseTocRepository->find($courseIdentifier);
        $partParents = array();
        $partParents = $this->getParentsFromToc($partParents, $courseToc, $partIdentifier);

        return $partParents;
    }

    /**
     * Scan complete toc and retrieve parents
     *
     * @param array        $partParents    Part parents
     * @param PartResource $parent         Parent element
     * @param int | string $partIdentifier Part id | slug
     *
     * @return bool
     */
    private function getParentsFromToc($partParents, PartResource $parent, $partIdentifier)
    {
        if ($this->matchPart($parent, $partIdentifier)) {
            return true;
        }

        if ($parent->getChildren()) {
            foreach ($parent->getChildren() as $part) {
                /* Limit deep level */
                if (in_array(
                    $part->getSubtype(),
                    array(PartResource::TITLE_1, PartResource::TITLE_2, PartResource::TITLE_3)
                )
                ) {
                    /* recursive deep scan */
                    $found = $this->getParentsFromToc($partParents, $part, $partIdentifier);

                    if ($found === true ||
                        (is_array($found) && count($found) > count($partParents))
                    ) {
                        if (is_array($found)) {
                            $partParents = $found;
                        }
                        /* send parent info up */
                        $partParents[] = $part->getId();

                        return $partParents;
                    } else {
                        $partParents = $found;
                    }
                }
            }
        }

        return $partParents;
    }

    /**
     * Match parts
     *
     * @param PartResource $parent         Parent element
     * @param int | string $partIdentifier Part id | slug
     *
     * @return bool
     */
    private function matchPart(PartResource $parent, $partIdentifier)
    {
        return NumberUtils::isInteger($partIdentifier) && $parent->getId() == $partIdentifier ||
        !NumberUtils::isInteger($partIdentifier) && $parent->getSlug() == $partIdentifier;
    }
}
