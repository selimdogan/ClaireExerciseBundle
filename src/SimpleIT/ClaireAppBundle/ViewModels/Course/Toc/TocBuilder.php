<?php

namespace SimpleIT\ClaireAppBundle\ViewModels\Course\Toc;

use SimpleIT\ApiResourcesBundle\Course\PartResource;

/**
 * Class TocBuilder
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class TocBuilder
{

    private static $displayableSubtypes1 = array();

    private static $displayableSubtypes2 = array();

    private $tocViewModel = array();

    public function buildTocForEdit($toc, $displayLevel)
    {
        $this->tocViewModel = array();
        $this->buildArray1($toc, $displayLevel);

        /** @var PartResource $child */
        foreach ($tocItem->getChildren() as $child) {
            $tocItemVM = new TocItemViewModel();
            $tocItemVM->id = $child->getId();
            $tocItemVM->subtype = $child->getSubtype();
        }
    }
}
