<?php
namespace SimpleIT\ClaireExerciseBundle\Model\Resources;

use JMS\Serializer\Handler\HandlerRegistry;
use JMS\Serializer\SerializerBuilder;
use SimpleIT\ClaireExerciseBundle\Serializer\Handler\AbstractClassForExerciseHandler;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ItemResource;
use SimpleIT\ClaireExerciseBundle\Entity\CreatedExercise\Item;
use SimpleIT\Utils\Collection\PaginatorInterface;

/**
 * Class ExerciseModelResourceFactory
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
abstract class ItemResourceFactory
{

    /**
     * Create an ItemResource collection
     *
     * @param PaginatorInterface $items
     *
     * @return array
     */
    public static function createCollection(PaginatorInterface $items)
    {
        $itemResources = array();
        foreach ($items as $item) {
            $itemResources[] = self::create($item);
        }

        return $itemResources;
    }

    /**
     * Create an Exercise Resource
     *
     * @param Item $item
     * @param null $corrected
     *
     * @return ItemResource
     */
    public static function create(Item $item, $corrected = null)
    {
        $itemResource = new ItemResource();

        $itemResource->setItemId($item->getId());
        $itemResource->setType($item->getType());
        $itemResource->setCorrected($corrected);

        $serializer = SerializerBuilder::create()
            ->addDefaultHandlers()
            ->configureHandlers(
                function (HandlerRegistry $registry) {
                    $registry->registerSubscribingHandler(new AbstractClassForExerciseHandler());
                }
            )
            ->build();
        $content = $serializer->deserialize(
            $item->getContent(),
            ItemResource::getClass($item->getType()),
            'json'
        );

        $itemResource->setContent($content);

        return $itemResource;
    }
}
