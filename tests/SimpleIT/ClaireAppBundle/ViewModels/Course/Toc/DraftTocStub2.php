<?php

namespace SimpleIT\ClaireAppBundle\ViewModels\Course\Toc;

use OC\CLAIRE\BusinessRules\Entities\Course\Course\DisplayLevel;
use OC\CLAIRE\BusinessRules\Entities\Course\Course\Status;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class DraftTocStub2 extends TocStub1
{
    const STATUS = Status::DRAFT;

    const DISPLAY_LEVEL = DisplayLevel::BIG;

    protected $status = self::STATUS;

    protected $displayLevel = self::DISPLAY_LEVEL;
}
