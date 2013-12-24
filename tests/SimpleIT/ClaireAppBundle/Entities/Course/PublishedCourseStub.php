<?php

namespace SimpleIT\ClaireAppBundle\Entities\Course;

use SimpleIT\ApiResourcesBundle\Course\CourseResource;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class PublishedCourseStub extends CourseResource
{
    const COURSE_CREATED_AT = '2013-01-01T00:00:00Z';

    const COURSE_DISPLAY_LEVEL = 0;

    const COURSE_ID = 1;

    const COURSE_SLUG = 'course-title-1';

    const COURSE_STATUS = CourseResource::STATUS_PUBLISHED;

    const COURSE_TITLE = 'Course title 1';

    const COURSE_UPDATED_AT = '2013-01-20T11:11:11Z';

    public function getCreatedAt()
    {
        return new \DateTime(self::COURSE_CREATED_AT);
    }

    public function getDisplayLevel()
    {
        return self::COURSE_DISPLAY_LEVEL;
    }

    public function getId()
    {
        return self::COURSE_ID;
    }

    public function getSlug()
    {
        return self::COURSE_SLUG;
    }

    public function getStatus()
    {
        return self::COURSE_STATUS;
    }

    public function getTitle()
    {
        return self::COURSE_TITLE;
    }

    public function getUpdatedAt()
    {
        return new \DateTime(self::COURSE_UPDATED_AT);
    }
}
