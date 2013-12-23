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

use SimpleIT\ApiResourcesBundle\Course\CourseResource;
use SimpleIT\AppBundle\Repository\AppRepository;
use SimpleIT\ClaireAppBundle\Gateways\Course\Course\CourseContentGateway;
use SimpleIT\Utils\FormatUtils;

/**
 * Class CourseContentRepository
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class CourseContentRepository extends AppRepository implements CourseContentGateway
{
    const FORMAT_HTML = 'HTML';

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
     * @return string
     */
    public function update($courseId, $content)
    {
        return parent::updateResource(
            $content,
            array('courseIdentifier' => $courseId),
            array(CourseResource::STATUS => CourseResource::STATUS_DRAFT),
            FormatUtils::HTML
        );
    }

    /**
     * @return string
     *
     * @cache (namespacePrefix="claire_app_course_course", namespaceAttribute="courseIdentifier", lifetime=0)
     */
    public function findPublished($courseIdentifier)
    {
        return parent::findResource(
            array('courseIdentifier' => $courseIdentifier),
            array(),
            self::FORMAT_HTML
        );
    }

    /**
     * @return string
     */
    public function findWaitingForPublication($courseId)
    {
        return parent::findResource(
            array('courseIdentifier' => $courseId),
            array(CourseResource::STATUS => CourseResource::STATUS_WAITING_FOR_PUBLICATION),
            self::FORMAT_HTML
        );
    }

    /**
     * @return string
     */
    public function findDraft($courseId)
    {
        return parent::findResource(
            array('courseIdentifier' => $courseId),
            array(CourseResource::STATUS => CourseResource::STATUS_DRAFT),
            self::FORMAT_HTML
        );
    }
}
