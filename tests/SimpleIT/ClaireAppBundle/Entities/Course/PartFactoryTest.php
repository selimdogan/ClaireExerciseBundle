<?php

namespace SimpleIT\ClaireAppBundle\Entities\Course;

use SimpleIT\ApiResourcesBundle\Course\PartResource;
use SimpleIT\ClaireAppBundle\Entities\Course\Part\PartFactory;
use SimpleIT\ClaireAppBundle\Entity\Course\Part\PartFactoryImpl;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class PartFactoryTest extends \PHPUnit_Framework_TestCase
{
    const UNSUPPORTED_TYPE = 'UnsupportedType';

    /**
     * @var PartFactory
     */
    private $factory;

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function MakeWithInvalidSubtype_ThrowException()
    {
        $this->factory->make(self::UNSUPPORTED_TYPE);
    }

    /**
     * @test
     */
    public function MakeWithTitle1_ReturnTitle1()
    {
        $part = $this->factory->make(PartResource::TITLE_1);
        $this->assertPart($part, PartResource::TITLE_1);
    }

    private function assertPart(PartResource $part, $subType)
    {
        $this->assertInstanceOf('SimpleIT\ApiResourcesBundle\Course\PartResource', $part);
        $this->assertEquals($subType, $part->getSubtype());
    }

    /**
     * @test
     */
    public function MakeWithTitle2_ReturnTitle2()
    {
        $part = $this->factory->make(PartResource::TITLE_2);
        $this->assertPart($part, PartResource::TITLE_2);
    }

    /**
     * @test
     */
    public function MakeWithTitle3_ReturnTitle3()
    {
        $part = $this->factory->make(PartResource::TITLE_3);
        $this->assertPart($part, PartResource::TITLE_3);
    }

    protected function setup()
    {
        $this->factory = new PartFactoryImpl();
    }
}
