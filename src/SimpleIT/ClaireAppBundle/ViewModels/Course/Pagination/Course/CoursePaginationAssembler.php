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
        $categoryIdentifier,
        $status
    )
    {
        $this->displayLevel = $displayLevel;
        $this->categoryIdentifier = $categoryIdentifier;
        $this->status = $status;
        $this->toc = $toc;

        $this->pagination = new Pagination();
        $this->setCourseIdentifier();
        $this->buildPagination();

        return $this->pagination;
    }

    private function buildPagination()
    {
        switch ($this->displayLevel) {
            case DisplayLevel::SMALL:
                break;
            case DisplayLevel::MEDIUM:
                $this->buildMediumPagination();
                break;
            case DisplayLevel::BIG:
                $this->buildBigPagination();
                break;
            default:
                throw new InvalidDisplayLevelException();
        }
    }

    private function buildMediumPagination()
    {
        foreach ($this->toc->getChildren() as $child) {
            if ('title-1' === $child->getSubtype()) {
                $this->buildNext($child);
                break;
            }
        }
    }

    private function buildBigPagination()
    {
        $find = false;
        foreach ($this->toc->getChildren() as $child) {
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
