<?php

namespace SimpleIT\ClaireAppBundle\ViewModels\Course\Toc;

use SimpleIT\ApiResourcesBundle\Course\PartResource;

/**
 * Class TitleTwoStub
 * 
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class TitleTwoStub extends PartResource
{
    const ID = 100;

    const SUBTYPE = self::TITLE_2;

    protected $id = self::ID;

    protected $subtype = self::SUBTYPE;

    public function __construct()
    {
        $this->children = array(new TitleTreeStub(), new TitleTreeStub());
    }
}
