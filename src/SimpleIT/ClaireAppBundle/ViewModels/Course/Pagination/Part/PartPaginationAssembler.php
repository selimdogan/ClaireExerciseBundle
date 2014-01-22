<?php

namespace SimpleIT\ClaireAppBundle\ViewModels\Course\Pagination\Part;

use OC\CLAIRE\BusinessRules\Entities\Course\Course\DisplayLevel;
use OC\CLAIRE\BusinessRules\Entities\Course\Course\Status;
use SimpleIT\ApiResourcesBundle\Course\PartResource;
use SimpleIT\ClaireAppBundle\ViewModels\Course\Pagination\InvalidDisplayLevelException;
use SimpleIT\ClaireAppBundle\ViewModels\Course\Pagination\Pagination;
use SimpleIT\ClaireAppBundle\ViewModels\Course\Pagination\PaginationAssembler;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class PartPaginationAssembler extends PaginationAssembler
{
    /**
     * @var int
     */
    protected $displayLevel;

    /**
     * @var int|string
     */
    protected $courseIdentifier;

    /**
     * @var string
     */
    protected $categoryIdentifier;

    /**
     * @var string
     */
    protected $status;

    /**
     * @var int|string
     */
    protected $partIdentifier;

    /**
     * @var PartResource
     */
    private $child;

    private $currentFind = false;

    private $nextFind = false;

    public function createFromToc(
        PartResource $toc,
        $displayLevel,
        $categoryIdentifier,
        $status,
        $partIdentifier
    )
    {
        $this->displayLevel = $displayLevel;
        $this->categoryIdentifier = $categoryIdentifier;
        $this->status = $status;
        $this->partIdentifier = $partIdentifier;
        $this->toc = $toc;

        $this->pagination = new Pagination();
        $this->setCourseIdentifier();

        $this->pagination->previousTitle = $toc->getTitle();
        $this->generateCourseUrl();

        switch ($displayLevel) {
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

    private function generateCourseUrl()
    {
        if (Status::PUBLISHED === $this->status) {
            $this->pagination->previousUrl = $this->router->generate(
                'simple_it_claire_course_course_view',
                array(
                    'categoryIdentifier' => $this->categoryIdentifier,
                    'courseIdentifier'   => $this->courseIdentifier,
                )
            );
        } elseif (Status::DRAFT === $this->status || Status::WAITING_FOR_PUBLICATION === $this->status) {
            $this->pagination->previousUrl = $this->router->generate(
                'simple_it_claire_course_course_view',
                array(
                    'categoryIdentifier' => $this->categoryIdentifier,
                    'courseIdentifier'   => $this->courseIdentifier,
                    'status'             => $this->status
                )
            );
        }
    }

    private function buildMediumPagination(PartResource $toc)
    {
        foreach ($toc->getChildren() as $this->child) {
            if ($this->isMediumSubTypeAllowed()) {
                if ($this->isCurrent()) {
                    $this->currentFind = true;
                } elseif ($this->isNext()) {
                    $this->buildNext($this->child);
                    $this->nextFind = true;
                } else {
                    $this->buildPrevious($this->child);
                }
                if ($this->nextFind) {
                    break;
                }
            }
        }
    }

    private function buildBigPagination(PartResource $toc)
    {
        foreach ($toc->getChildren() as $partChild) {
            foreach ($partChild->getChildren() as $this->child) {
                if ($this->isBigSubTypeAllowed()) {
                    if ($this->isCurrent()) {
                        $this->currentFind = true;
                    } elseif ($this->isNext()) {
                        $this->buildNext($this->child);
                        $this->nextFind = true;
                    } else {
                        $this->buildPrevious($this->child);
                    }
                    if ($this->nextFind) {
                        break;
                    }
                }
            }
            if ($this->nextFind) {
                break;
            }
        }
    }

    /**
     * @return bool
     */
    private function isMediumSubTypeAllowed()
    {
        return 'title-1' === $this->child->getSubtype();
    }

    /**
     * @return bool
     */
    private function isCurrent()
    {
        return !$this->currentFind
        && ($this->partIdentifier === $this->child->getSlug()
            || $this->partIdentifier === $this->child->getId()
        );
    }

    /**
     * @return bool
     */
    private function isNext()
    {
        return $this->currentFind && !$this->nextFind;
    }

    /**
     * @return bool
     */
    private function isBigSubTypeAllowed()
    {
        return 'title-2' === $this->child->getSubtype();
    }
}
