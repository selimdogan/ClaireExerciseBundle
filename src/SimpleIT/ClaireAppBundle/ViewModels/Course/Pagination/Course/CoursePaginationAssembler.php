<?php

namespace SimpleIT\ClaireAppBundle\ViewModels\Course\Pagination\Course;

use OC\CLAIRE\BusinessRules\Entities\Course\Course\DisplayLevel;
use SimpleIT\ApiResourcesBundle\Course\PartResource;
use SimpleIT\ClaireAppBundle\ViewModels\Course\Pagination\InvalidDisplayLevelException;
use SimpleIT\ClaireAppBundle\ViewModels\Course\Pagination\Pagination;
use SimpleIT\ClaireAppBundle\ViewModels\Course\Pagination\PaginationAssembler;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class CoursePaginationAssembler extends PaginationAssembler
{
    public function createFromToc(
        PartResource $toc,
        $displayLevel,
        $courseIdentifier,
        $categoryIdentifier,
        $status
    )
    {
        $this->displayLevel = $displayLevel;
        $this->courseIdentifier = $courseIdentifier;
        $this->categoryIdentifier = $categoryIdentifier;
        $this->status = $status;

        $this->pagination = new Pagination();

        switch ($displayLevel) {
            case DisplayLevel::SMALL:
                break;
            case DisplayLevel::MEDIUM:
                $this->buildMediumPagination($toc);
                break;
            case DisplayLevel::BIG:
                $this->buildBigPagination($toc);
                break;
            default:
                throw new InvalidDisplayLevelException();
        }

        return $this->pagination;
    }

    private function buildMediumPagination(PartResource $toc)
    {
        foreach ($toc->getChildren() as $child) {
            if ('title-1' === $child->getSubtype()) {
                $this->buildNext($child);
                break;
            }
        }
    }

    private function buildBigPagination(PartResource $toc)
    {
        $find = false;
        foreach ($toc->getChildren() as $child) {
            foreach ($child->getChildren() as $subChild) {
                if ('title-2' === $subChild->getSubtype()) {
                    $this->buildNext($subChild);
                    $find = true;
                    break;
                }
            }
            if ($find) {
                break;
            }
        }
    }
}
