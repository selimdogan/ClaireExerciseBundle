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
    const COURSE_1_CATEGORY_SLUG = 'category-slug';

    const COURSE_1_ID = 1;

    const COURSE_1_SLUG = 'course-1-slug';

    const ROUTING_FILE = 'route.yml';

    /**
     * @var PaginationAssembler
     */
    protected $paginationAssembler;

    /**
     * @var RouterInterface
     */
    protected $router;

    protected function setUp()
    {
        $this->router = new Router(
            new YamlFileLoader(new FileLocator(array(__DIR__))),
            self::ROUTING_FILE
        );

        $this->paginationAssembler->setRouter($this->router);
    }
}
