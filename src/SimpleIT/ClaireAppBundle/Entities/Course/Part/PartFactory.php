<?php

namespace SimpleIT\ClaireAppBundle\Entities\Course\Part;

/**
 * Interface PartFactory
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
interface PartFactory
{
    const TITLE_1 = 'title-1';

    const TITLE_2 = 'title-2';

    const TITLE_3 = 'title-3';

    /**
     * @return Part
     */
    public function make($subtype);
} 
