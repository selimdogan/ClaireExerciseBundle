<?php

namespace SimpleIT\ClaireAppBundle\ViewModels\Course\Toc;

/**
 * Class TocItemFactory
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
interface TocItemFactory
{
    const COURSE = 'course';

    const CHAPTER = 'chapter';

    const CHAPTER_CREATION = 'chapter-creation';

    const PART = 'part';

    const PART_CREATION = 'part-creation';

    public function make($tocItemSubtype);

}
