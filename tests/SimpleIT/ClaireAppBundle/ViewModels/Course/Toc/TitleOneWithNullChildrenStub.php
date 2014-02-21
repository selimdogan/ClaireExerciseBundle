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
    const ID = 10;

    const SUBTYPE = self::TITLE_1;

    protected $id = self::ID;

    protected $subtype = self::SUBTYPE;

    public function __construct()
    {
        $this->children = null;
    }

}
