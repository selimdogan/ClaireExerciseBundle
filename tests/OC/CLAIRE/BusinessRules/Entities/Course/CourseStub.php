<?php

namespace OC\CLAIRE\BusinessRules\Entities\Course;

use OC\CLAIRE\BusinessRules\Entities\Course\Course\Difficulty;
use OC\CLAIRE\BusinessRules\Entities\Course\Course\Duration;
use SimpleIT\ApiResourcesBundle\Course\CourseResource;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
abstract class CourseStub extends CourseResource
{
    const COURSE_CREATED_AT = '2013-01-01T00:00:00Z';

    const COURSE_DESCRIPTION = 'Course 1 description';

    const COURSE_DIFFICULTY = Difficulty::EASY;

    const COURSE_DISPLAY_LEVEL = 0;

    const COURSE_DURATION = Duration::P1D;

    const COURSE_ID = 1;

    const COURSE_IMAGE = 'http://example.com/image.png';

    const COURSE_LICENSE = 'CC-BY-NC-SA';

    const COURSE_SLUG = 'course-title-1';

    const COURSE_TITLE = 'Course title 1';

    const COURSE_UPDATED_AT = '2013-01-20T11:11:11Z';

    protected $description = self::COURSE_DESCRIPTION;

    protected $difficulty = self::COURSE_DIFFICULTY;

    protected $displayLevel = self::COURSE_DISPLAY_LEVEL;

    protected $duration = self::COURSE_DURATION;

    protected $id = self::COURSE_ID;

    protected $image = self::COURSE_IMAGE;

    protected $license = self::COURSE_LICENSE;

    protected $slug = self::COURSE_SLUG;

    protected $title = self::COURSE_TITLE;

    public function getCreatedAt()
    {
        return new \DateTime(self::COURSE_CREATED_AT);
    }

    public function getUpdatedAt()
    {
        return new \DateTime(self::COURSE_UPDATED_AT);
    }
}
