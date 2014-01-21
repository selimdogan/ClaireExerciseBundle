<?php

namespace SimpleIT\ClaireAppBundle\ViewModels\Course\Pagination;

use OC\CLAIRE\BusinessRules\Entities\Course\Course\DisplayLevel;
use OC\CLAIRE\BusinessRules\Entities\Course\Course\Status;
use SimpleIT\ApiResourcesBundle\Course\PartResource;
use Symfony\Component\Routing\RouterInterface;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class CoursePaginationAssembler
{
    const VIEW_PART_ROUTE = 'simple_it_claire_course_part_view';

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var Pagination
     */
    private $pagination;

    /**
     * @var int
     */
    private $displayLevel;

    /**
     * @var string
     */
    private $courseIdentifier;

    /**
     * @var string
     */
    private $categoryIdentifier;

    /**
     * @var string
     */
    private $status;

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

    private function buildNext(PartResource $child)
    {
        $this->pagination->nextTitle = $child->getTitle();
        $this->pagination->nextUrl = $this->generateUrl(
            $this->courseIdentifier,
            $child,
            $this->status,
            $this->categoryIdentifier
        );
    }

    private function generateUrl(
        $courseIdentifier,
        PartResource $part,
        $status,
        $categoryIdentifier
    )
    {
        if (Status::PUBLISHED === $status) {
            $url = $this->router->generate(
                self::VIEW_PART_ROUTE,
                array(
                    'categoryIdentifier' => $categoryIdentifier,
                    'courseIdentifier'   => $courseIdentifier,
                    'partIdentifier'     => $part->getSlug(),
                )
            );
        } elseif (Status::DRAFT === $status || Status::WAITING_FOR_PUBLICATION) {
            $url = $this->router->generate(
                self::VIEW_PART_ROUTE,
                array(
                    'categoryIdentifier' => $categoryIdentifier,
                    'courseIdentifier'   => $courseIdentifier,
                    'partIdentifier'     => $part->getId(),
                    'status'             => $status
                )
            );
        } else {
            throw new \InvalidArgumentException();
        }

        return $url;
    }

    public function setRouter(RouterInterface $router)
    {
        $this->router = $router;
    }
}
