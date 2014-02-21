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

namespace SimpleIT\ClaireAppBundle\Repository\Course;

use SimpleIT\AppBundle\Annotation\Cache;
use SimpleIT\AppBundle\Repository\AppRepository;
use SimpleIT\Utils\FormatUtils;


/**
 * Class CourseContentRepository
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class CourseContentRepository extends AppRepository
{

    /**
     * @var string
     */
    protected $path = 'courses/{courseIdentifier}/content';

    /**
     * @var  string
     */
    protected $resourceClass = '';

    /**
     * Get a course content
     *
     * @param string $courseIdentifier Course id | slug
     * @param array  $parameters       Parameters
     * @param string $format           Format
     *
     * @return mixed
     *
     * @cache (namespacePrefix="claire_app_course_course", namespaceAttribute="courseIdentifier", lifetime=0)
     */
    public function find(
        $courseIdentifier,
        $parameters = array(),
        $format = FormatUtils::HTML
    )
    {
        return parent::findResource(
            array('courseIdentifier' => $courseIdentifier),
            $parameters,
            $format
        );
    }

    /**
     * Get a course content
     *
     * @param string $courseIdentifier Course id | slug
     * @param array  $parameters       Parameters
     * @param string $format           Format
     *
     * @return string
     */
    public function findToEdit(
        $courseIdentifier,
        $parameters = array(),
        $format = FormatUtils::HTML
    )
    {
        return parent::findResource(
            array('courseIdentifier' => $courseIdentifier),
            $parameters,
            $format
        );
    }

    /**
     * Update a course content
     *
     * @param string $courseIdentifier Course id | slug
     * @param string $courseContent    Course content
     * @param array  $parameters       Parameters
     * @param string $format           Format
     *
     * @return string
     */
    public function update(
        $courseIdentifier,
        $courseContent,
        $parameters = array(),
        $format = FormatUtils::HTML
    )
    {
        return parent::updateResource(
            $courseContent,
            array('courseIdentifier' => $courseIdentifier),
            $parameters,
            $format
        );
    }
}
