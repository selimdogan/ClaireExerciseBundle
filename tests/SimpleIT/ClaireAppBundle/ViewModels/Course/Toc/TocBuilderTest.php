<?php

namespace SimpleIT\ClaireAppBundle\ViewModels\Course\Toc;

use SimpleIT\ApiResourcesBundle\Course\PartResource;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Routing\Router;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class TocBuilder
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class TocBuilderTest extends \PHPUnit_Framework_TestCase
{
    const TOC_VM_EXPECTED_COUNT = 7;

    const DISPLAY_LEVEL_1 = 1;

    const ROUTING_FILE = 'routing.yml';

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var CourseItem
     */
    private $tocVM;

    /**
     * @var TocBuilder
     */
    private $tocBuilder;

    /**
     * @var PartResource
     */
    private $toc;

    /**
     * @var array
     */
    private $tocItems = array();

    /**
     * @test
     */
    public function tocShouldOnlyHaveItemsOfTheCorrectTypeInDisplayLevelOne()
    {
        $this->initializeToc();
        $this->tocVM = $this->tocBuilder->buildTocForEdition($this->toc);

        /** @var TocItem $tocItem */
        foreach ($this->tocVM->children as $tocItem) {
            $this->assertTrue(in_array($tocItem->subtype, TocBuilder::$displayableSubtypes));
        }
    }

    private function initializeToc()
    {
        $this->initializeRouter();
        $this->tocBuilder = new TocBuilder($this->router);
        $this->toc = new TocStub1();
    }

    private function initializeRouter()
    {
        $this->router = new Router(
            new YamlFileLoader(new FileLocator(array(__DIR__))),
            self::ROUTING_FILE
        );
    }

    /**
     * @test
     */
    public function tocShouldHaveTheRightNumberOfElements()
    {
        $this->initializeToc();
        $this->tocVM = $this->tocBuilder->buildTocForEdition($this->toc);

        $this->tocItems = array();
        $this->flattenToc($this->tocVM);

        $this->assertCount(self::TOC_VM_EXPECTED_COUNT, $this->tocItems);
    }

    private function flattenToc(TocItem $parent)
    {
        $this->tocItems[] = $parent;
        /** @var TocItem $tocItem */
        foreach ($parent->children as $child) {
            $this->flattenToc($child);
        }
    }

    /**
     * @test
     */
    public function tocHierarchyIsCorrect()
    {
        $this->initializeToc();
        $this->tocVM = $this->tocBuilder->buildTocForEdition($this->toc);
        $this->assertParentSubtype($this->tocVM);
    }

    private function assertParentSubtype(TocItem $parent)
    {
        foreach ($parent->children as $child) {
            if ($this->isPart($child)) {
                $this->assertTrue($parent instanceof CourseItem);
            } elseif ($this->isChapter($child)) {
                $this->assertTrue($parent instanceof PartItem);
            } else {
                $this->fail();
            }
            $this->assertParentSubtype($child);
        }
    }

    /**
     * @param $child
     *
     * @return bool
     */
    private function isPart($child)
    {
        return $child instanceof PartItem;
    }

    /**
     * @param $child
     *
     * @return bool
     */
    private function isChapter($child)
    {
        return $child instanceof ChapterItem;
    }
}
