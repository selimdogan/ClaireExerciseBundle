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
use SimpleIT\ClaireAppBundle\Repository\Course\PartContentRepository;
use SimpleIT\ClaireAppBundle\Repository\Course\PartRepository;

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
     * @var  PartContentRepository
     */
    private $partContentRepository;

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
     * Set partContentRepository
     *
     * @param \SimpleIT\ClaireAppBundle\Repository\Course\PartContentRepository $partContentRepository
     */
    public function setPartContentRepository($partContentRepository)
    {
        $this->partContentRepository = $partContentRepository;
    }

    /**
     * Get a part
     *
     * @param string | integer $courseIdentifier Course id | slug
     * @param string | integer $partIdentifier   Part id | slug
     *
     * @return \SimpleIT\ClaireAppBundle\Model\Course\Part
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
}
