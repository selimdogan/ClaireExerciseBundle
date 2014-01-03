<?php

namespace SimpleIT\ClaireAppBundle\ViewModels\Course\Toc;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class TocItemFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @expectedException SimpleIT\ClaireAppBundle\ViewModels\Course\Toc\UnsupportedSubtypeException
     */
    public function Make_WithUnsupportedType_ThrowException()
    {
        $factory = new TocItemFactoryImpl();
        $factory->make('unsupportedSubType');
    }
}
