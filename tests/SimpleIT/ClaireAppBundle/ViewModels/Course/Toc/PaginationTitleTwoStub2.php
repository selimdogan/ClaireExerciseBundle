<?php

namespace SimpleIT\ClaireAppBundle\ViewModels\Course\Toc;

use SimpleIT\ApiResourcesBundle\Course\PartResource;

/**
 * Class TitleTwoStub
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class PaginationTitleTwoStub2 extends PartResource
{
    const ID = 101;

    const TITLE = 'Title Two title 2';

    const SLUG = 'title-two-title-2';

    const SUBTYPE = self::TITLE_2;

    protected $id = self::ID;

    protected $title = self::TITLE;

    protected $slug = self::SLUG;

    protected $subtype = self::SUBTYPE;

    public function __construct()
    {
        $this->children = array(new PaginationTitleThreeStub1(), new PaginationTitleThreeStub2());
    }
}
