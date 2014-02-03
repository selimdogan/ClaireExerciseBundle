<?php

namespace SimpleIT\ClaireAppBundle\ViewModels\Course\Pagination\Part;

use SimpleIT\ClaireAppBundle\ViewModels\Course\Pagination\Pagination;
use SimpleIT\ClaireAppBundle\ViewModels\Course\Toc\PaginationTitleThreeStub2;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class PublishedBigCourseLastTitle3PaginationExpected extends Pagination
{
    const PREVIOUS_TITLE = PaginationTitleThreeStub2::TITLE;

    const PREVIOUS_URL = '/category-slug/cours/course-1-title/title-three-title-2';

    public $previousTitle = self::PREVIOUS_TITLE;

    public $previousUrl = self::PREVIOUS_URL;
}
