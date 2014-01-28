<?php

namespace SimpleIT\ClaireAppBundle\ViewModels\Course\Pagination;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Routing\Router;
use Symfony\Component\Routing\RouterInterface;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
abstract class PaginationAssemblerTest extends \PHPUnit_Framework_TestCase
{
    const INVALID_DISPLAY_LEVEL = 3;

    const INVALID_STATUS = 'Invalid status';

    const COURSE_1_CATEGORY_SLUG = 'category-slug';

    const ROUTING_FILE = 'route.yml';

    /**
     * @var PaginationAssembler
     */
    protected $paginationAssembler;

    /**
     * @var RouterInterface
     */
    protected $router;

    /**
     * @var Pagination
     */
    protected $pagination;

    protected function setUp()
    {
        $this->router = new Router(
            new YamlFileLoader(new FileLocator(array(__DIR__))),
            self::ROUTING_FILE
        );

        $this->paginationAssembler->setRouter($this->router);
    }

    protected function assertPagination(Pagination $expected)
    {
        $this->assertEquals($expected->previousTitle, $this->pagination->previousTitle);
        $this->assertEquals($expected->previousUrl, $this->pagination->previousUrl);
        $this->assertEquals($expected->nextTitle, $this->pagination->nextTitle);
        $this->assertEquals($expected->nextUrl, $this->pagination->nextUrl);
    }
}
