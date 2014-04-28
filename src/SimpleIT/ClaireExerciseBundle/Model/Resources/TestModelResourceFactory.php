<?php
namespace SimpleIT\ClaireExerciseBundle\Model\Resources;

use SimpleIT\ClaireExerciseResourceBundle\Model\Resources\ExerciseObject;
use SimpleIT\ClaireExerciseResourceBundle\Model\Resources\ExerciseResource;
use SimpleIT\ClaireExerciseResourceBundle\Model\Resources\ItemResource;
use SimpleIT\ClaireExerciseResourceBundle\Model\Resources\TestModelResource;
use SimpleIT\ClaireExerciseBundle\Entity\Test\TestModel;
use SimpleIT\ClaireExerciseBundle\Entity\Test\TestModelPosition;
use SimpleIT\Utils\Collection\PaginatorInterface;

/**
 * Class TestModelResourceFactory
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
abstract class TestModelResourceFactory
{

    /**
     * Create a TestModelResource collection
     *
     * @param PaginatorInterface $testModels
     *
     * @return array
     */
    public static function createCollection(PaginatorInterface $testModels)
    {
        $testModelResources = array();
        foreach ($testModels as $testModel) {
            $testModelResources[] = self::create($testModel);
        }

        return $testModelResources;
    }

    /**
     * Create a TestModelResource
     *
     * @param TestModel $testModel
     *
     * @return TestModelResource
     */
    public static function create(TestModel $testModel)
    {
        $testModelResource = new TestModelResource();
        $testModelResource->setId($testModel->getId());
        $testModelResource->setTitle($testModel->getTitle());

        $exerciseModels = array();
        foreach ($testModel->getTestModelPositions() as $position) {
            /** @var TestModelPosition $position */
            $exerciseModels[$position->getPosition()] = $position->getOwnerExerciseModel()->getId();
        }

        // order the model ids in a sequential array
        $em = array();
        for ($i = 0; $i < count($exerciseModels); $i++) {
            $em[] = $exerciseModels[$i];
        }
        $testModelResource->setOwnerExerciseModels($em);

        return $testModelResource;
    }
}
