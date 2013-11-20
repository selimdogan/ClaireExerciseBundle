<?php

namespace SimpleIT\ClaireAppBundle\ViewModels\Course\Toc;

use SimpleIT\ApiResourcesBundle\Course\CourseResource;
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
     * @var CourseResource
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

    private function flattenToc($parent)
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
    public function tocItemsShouldHaveCorrectId()
    {

    }

}
