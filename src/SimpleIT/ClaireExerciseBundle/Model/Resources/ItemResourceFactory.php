<?php
namespace SimpleIT\ClaireExerciseBundle\Model\Resources;

use JMS\Serializer\Handler\HandlerRegistry;
use JMS\Serializer\SerializerBuilder;
use SimpleIT\ClaireExerciseBundle\Entity\CreatedExercise\Item;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ItemResource;
use SimpleIT\ClaireExerciseBundle\Serializer\Handler\AbstractClassForExerciseHandler;

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
     * @param array $items
     *
     * @return array
     */
    public static function createCollection(array $items)
    {
        $itemResources = array();
        foreach ($items as $item) {
            $itemResources[] = self::create($item, null);
        }

        return $itemResources;
    }

    /**
     * Create an Exercise Resource
     *
     * @param Item $item
     *
     * @return ItemResource
     */
    public static function create(Item $item)
    {
        $itemResource = new ItemResource();

        $itemResource->setItemId($item->getId());
        $itemResource->setType($item->getType());

        $serializer = SerializerBuilder::create()
            ->addDefaultHandlers()
            ->configureHandlers(
                function (HandlerRegistry $registry) {
                    $registry->registerSubscribingHandler(
                        new AbstractClassForExerciseHandler()
                    );
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
