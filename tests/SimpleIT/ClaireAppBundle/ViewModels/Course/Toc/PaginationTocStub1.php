<?php

namespace SimpleIT\ClaireAppBundle\ViewModels\Course\Toc;

use SimpleIT\ApiResourcesBundle\Course\PartResource;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class PaginationTocStub1 extends PartResource
{
    const ID = 1;

    const SUBTYPE = 'course';

    const TITLE = 'Course 1 title';

    const SLUG = 'course-1-title';

    protected $id = self::ID;

    protected $title = self::TITLE;

    protected $slug = self::SLUG;

    protected $subtype = self::SUBTYPE;

    protected $children = array();

    public function __construct()
    {
        $this->children = array(new TitleOneStub(), new TitleOneStub2(), new TitleOneStub3());
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
