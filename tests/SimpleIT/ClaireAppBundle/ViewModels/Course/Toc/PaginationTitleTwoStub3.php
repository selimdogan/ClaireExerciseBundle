<?php

namespace SimpleIT\ClaireAppBundle\ViewModels\Course\Toc;

use SimpleIT\ApiResourcesBundle\Course\PartResource;

/**
 * Class TitleTwoStub
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class PaginationTitleTwoStub3 extends PartResource
{
    const ID = 102;

    const TITLE = 'Title Two title 3';

    const SLUG = 'title-two-title-3';

    const SUBTYPE = self::TITLE_2;

    protected $id = self::ID;

    protected $title = self::TITLE;

    protected $slug = self::SLUG;

    protected $subtype = self::SUBTYPE;

    public function __construct()
    {
        $this->children = array(new TitleTreeStub(), new TitleTreeStub());
    }
}
