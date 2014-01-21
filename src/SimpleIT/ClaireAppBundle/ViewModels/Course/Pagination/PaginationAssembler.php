<?php

namespace SimpleIT\ClaireAppBundle\ViewModels\Course\Pagination;

use OC\CLAIRE\BusinessRules\Entities\Course\Course\Status;
use SimpleIT\ApiResourcesBundle\Course\PartResource;
use Symfony\Component\Routing\RouterInterface;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
abstract class PaginationAssembler
{
    const VIEW_PART_ROUTE = 'simple_it_claire_course_part_view';

    /**
     * @var RouterInterface
     */
    protected $router;

    /**
     * @var Pagination
     */
    protected $pagination;

    /**
     * @var int
     */
    protected $displayLevel;

    /**
     * @var string
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

    public function setRouter(RouterInterface $router)
    {
        $this->router = $router;
    }

    protected function buildNext(PartResource $child)
    {
        $this->pagination->nextTitle = $child->getTitle();
        $this->pagination->nextUrl = $this->generatePartUrl(
            $this->courseIdentifier,
            $child,
            $this->status,
            $this->categoryIdentifier
        );
    }

    private function generatePartUrl(
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

    protected function buildPrevious(PartResource $child)
    {
        $this->pagination->previousTitle = $child->getTitle();
        $this->pagination->previousUrl = $this->generatePartUrl(
            $this->courseIdentifier,
            $child,
            $this->status,
            $this->categoryIdentifier
        );
    }
}
