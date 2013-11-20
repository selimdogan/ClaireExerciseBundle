<?php

namespace SimpleIT\ClaireAppBundle\ViewModels\Course\Toc;

use SimpleIT\ApiResourcesBundle\Course\CourseResource;
use SimpleIT\ApiResourcesBundle\Course\PartResource;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class TocBuilder
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class TocBuilderForEdition
{
    public static $displayableSubtypes = array(
        PartResource::COURSE,
        PartResource::TITLE_1,
        PartResource::TITLE_2
    );

    private static $correspondingItems = array(
        PartResource::COURSE  => TocItemFactory::COURSE,
        PartResource::TITLE_1 => TocItemFactory::PART,
        PartResource::TITLE_2 => TocItemFactory::CHAPTER
    );

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var CourseResource
     */
    private $course;

    /**
     * @var CourseItem
     */
    private $rootItem;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
        $this->tocItemFactory = new TocItemFactoryImpl();
    }

    public function buildTocForEdition(PartResource $toc)
    {
        $this->course = $toc;
        $this->buildRootItem();

        $rootItem = $this->addTocItems($toc);

        return $rootItem;

    }

    private function buildRootItem()
    {
        $this->rootItem = $this->tocItemFactory->make(TocItemFactory::COURSE);
        $this->rootItem->subtype = PartResource::COURSE;
    }

    private function addTocItems(PartResource $part)
    {
        $parent = $this->buildTocItem($part);
        foreach ($part->getChildren() as $child) {
            if ($this->isDisplayable($child)) {
                $tocItem = $this->addTocItems($child);
                $parent->children[] = $tocItem;
            }

        }
        $parent = $this->addCreationItem($parent);

        return $parent;
    }

    private function buildTocItem(PartResource $part)
    {
        /** @var TocItemDisplay $tocItemDisplay */
        $tocItemDisplay = $this->tocItemFactory->make(
            self::$correspondingItems[$part->getSubtype()]
        );
        $tocItemDisplay->id = $part->getId();
        $tocItemDisplay->title = $part->getTitle();
        $tocItemDisplay->url = $this->router->generate(
            'simple_it_claire_component_part_edit',
            array(
                'courseId'       => $this->course->getId(),
                'partIdentifier' => $part->getId(),
                'status'         => $this->course->getStatus()
            )
        );

        return $tocItemDisplay;
    }

    /**
     * @return bool
     */
    private function isDisplayable(PartResource $child)
    {
        return in_array($child->getSubtype(), self::$displayableSubtypes);
    }

    /**
     * @param $parent
     */
    private function addCreationItem($parent)
    {
        if ($this->isParentAPart($parent)) {
            $this->addChapterCreationItem($parent);
        } elseif ($this->isParentACourse($parent)) {
            $this->addPartCreationItem($parent);
        }

        return $parent;
    }

    /**
     * @return bool
     */
    private function isParentAPart($parent)
    {
        return $parent->subtype == PartItem::SUBTYPE;
    }

    /**
     * @param $parent
     */
    private function addChapterCreationItem($parent)
    {
        $chapterCreationItem = $this->tocItemFactory->make(TocItemFactory::CHAPTER_CREATION);
        $chapterCreationItem->title = 'Sans titre';
        $chapterCreationItem->url = $this->router->generate(
            'simple_it_claire_component_course_toc_edit',
            array('courseId' => $this->course->getId(), 'parentId' => $parent->id)
        );
        $parent->children[] = $chapterCreationItem;
    }

    /**
     * @return bool
     */
    private function isParentACourse($parent)
    {
        return $parent->subtype == CourseItem::SUBTYPE;
    }

    /**
     * @param $parent
     */
    private function addPartCreationItem($parent)
    {
        $chapterCreationItem = $this->tocItemFactory->make(TocItemFactory::PART_CREATION);
        $chapterCreationItem->title = 'Sans titre';
        $chapterCreationItem->url = $this->router->generate(
            'simple_it_claire_component_course_toc_edit',
            array('courseId' => $this->course->getId(), 'parentId' => $parent->id)
        );
        $parent->children[] = $chapterCreationItem;
    }
}
