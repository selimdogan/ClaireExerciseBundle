<?php

namespace SimpleIT\ClaireAppBundle\ViewModels\Course\Toc;

use SimpleIT\ApiResourcesBundle\Course\PartResource;

/**
 * Class TitleOneStub
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class TitleOneWithNullChildrenStub extends PartResource
{
    const ID = 12;

    const TITLE = 'TitleOne title 3';

    const SLUG = 'titleone-title-3';

    const SUBTYPE = self::TITLE_1;

    protected $id = self::ID;

    protected $title = self::TITLE;

    protected $slug = self::SLUG;

    protected $subtype = self::SUBTYPE;

    public function __construct()
    {
        $this->children = null;
    }

}
