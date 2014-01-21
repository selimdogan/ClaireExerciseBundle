<?php

namespace SimpleIT\ClaireAppBundle\ViewModels\Course\Pagination;

use SimpleIT\ClaireAppBundle\ViewModels\Course\Toc\TitleTwoStub;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class PublishedBigCoursePaginationStub extends Pagination
{
    const NEXT_TITLE = TitleTwoStub::TITLE;

    const NEXT_URL = '/category-slug/cours/course-1-slug/titletwo-title-1';

    public $nextTitle = self::NEXT_TITLE;

    public $nextUrl = self::NEXT_URL;
}
