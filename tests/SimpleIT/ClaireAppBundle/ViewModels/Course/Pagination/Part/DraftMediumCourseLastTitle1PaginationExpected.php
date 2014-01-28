<?php

namespace SimpleIT\ClaireAppBundle\ViewModels\Course\Pagination\Part;

use SimpleIT\ClaireAppBundle\ViewModels\Course\Pagination\Pagination;
use SimpleIT\ClaireAppBundle\ViewModels\Course\Toc\PaginationTitleOneStub2;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class DraftMediumCourseLastTitle1PaginationExpected extends Pagination
{

    const PREVIOUS_TITLE = PaginationTitleOneStub2::TITLE;

    const PREVIOUS_URL = '/category-slug/cours/1/11?status=draft';

    public $previousTitle = self::PREVIOUS_TITLE;

    public $previousUrl = self::PREVIOUS_URL;

}
