<?php

namespace OC\CLAIRE\BusinessRules\Entities\Course\Part;

use OC\CLAIRE\BusinessRules\Entities\Course\Course\Difficulty;
use SimpleIT\ApiResourcesBundle\Course\PartResource;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class PartStub extends PartResource
{
    const CREATED_AT = '2013-01-01T00:00:00Z';

    const DESCRIPTION = 'Part 1 description';

    const DIFFICULTY = Difficulty::EASY;

    const DURATION = 'P1D';

    const ID = 10;

    const SLUG = 'part-1-title';

    const SUBTYPE = self::TITLE_2;

    const TITLE = 'Part 1 title';

    const UPDATED_AT = '2013-01-02T00:00:00Z';

    protected $description = self::DESCRIPTION;

    protected $difficulty = self::DIFFICULTY;

    protected $duration = self::DURATION;

    protected $id = self::ID;

    protected $slug = self::SLUG;

    protected $subtype = self::SUBTYPE;

    protected $title = self::TITLE;

    public function __construct()
    {
        $this->createdAt = new \DateTime(self::CREATED_AT);
        $this->updatedAt = new \DateTime(self::UPDATED_AT);
    }
}
