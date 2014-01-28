<?php

namespace OC\CLAIRE\BusinessRules\Entities\AssociatedContent\Tag;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class TagStub1 extends Tag
{
    const ID = 1;

    const IMAGE = 'http://example.com/tag1.png';

    const NAME = 'Tag 1';

    const SLUG = 'tag-1';

    /**
     * @var int
     */
    protected $id = self::ID;

    /**
     * @var string
     */
    protected $slug = self::SLUG;

    /**
     * @var string
     */
    protected $name = self::NAME;

    /**
     * @var string
     */
    protected $image = self::IMAGE;

}
