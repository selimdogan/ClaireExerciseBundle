<?php

namespace SimpleIT\ClaireAppBundle\ViewModels\Course\Toc;

use SimpleIT\ApiResourcesBundle\Course\PartResource;

/**
 * Class TocStub1
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class TocStub1 extends PartResource
{
    const ID = 1;

    const SUBTYPE = 'course';

    const TITLE = 'Course 1 title';

    protected $id = self::ID;

    protected $title = self::TITLE;

    protected $subtype = self::SUBTYPE;

    protected $children = array();

    public function __construct()
    {
        $this->children = array(new TitleOneStub1(), new TitleOneStub2(), new TitleOneWithNullChildrenStub());
    }

    /**
     * @return array
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getSubtype()
    {
        return $this->subtype;
    }

}
