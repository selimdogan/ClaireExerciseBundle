<?php

namespace SimpleIT\ClaireAppBundle\ViewModels\Course\Pagination\Course;

use SimpleIT\ClaireAppBundle\ViewModels\Course\Pagination\Pagination;
use SimpleIT\ClaireAppBundle\ViewModels\Course\Toc\TitleOneStub;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class PublishedMediumCoursePaginationStub extends Pagination
{
    const NEXT_TITLE = TitleOneStub::TITLE;

    const NEXT_URL = '/category-slug/cours/course-1-slug/titleone-title-1';

    public $nextTitle = self::NEXT_TITLE;

    public $nextUrl = self::NEXT_URL;
}
