<?php
namespace SimpleIT\ClaireExerciseBundle\Model\Resources;

use JMS\Serializer\Handler\HandlerRegistry;
use JMS\Serializer\SerializerBuilder;
use SimpleIT\ClaireExerciseBundle\Entity\DomainKnowledge\Knowledge;
use SimpleIT\ClaireExerciseBundle\Entity\ExerciseModel\Metadata;
use SimpleIT\ClaireExerciseBundle\Serializer\Handler\AbstractClassForExerciseHandler;
use SimpleIT\ClaireExerciseBundle\Entity\ExerciseModel\ExerciseModel;
use SimpleIT\ClaireExerciseBundle\Entity\ExerciseResource\ExerciseResource;
use SimpleIT\Utils\Collection\PaginatorInterface;

/**
 * Class ExerciseModelResourceFactory
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
abstract class ExerciseModelResourceFactory extends SharedResourceFactory
{

    /**
     * Create an ExerciseModel Resource collection
     *
     * @param PaginatorInterface $exerciseModels
     *
     * @return array
     */
    public static function createCollection(PaginatorInterface $exerciseModels)
    {
        $exerciseModelResources = array();
        foreach ($exerciseModels as $exerciseModel) {
            $exerciseModelResources[] = self::create($exerciseModel);
        }

        return $exerciseModelResources;
    }

    /**
     * Create an ExerciseModel Resource
     *
     * @param ExerciseModel $exerciseModel
     *
     * @return ExerciseModelResource
     */
    public static function create(ExerciseModel $exerciseModel)
    {
        $exerciseModelResource = new ExerciseModelResource();
        parent::fill($exerciseModelResource, $exerciseModel);
        $exerciseModelResource->setDraft($exerciseModel->getDraft());
        $exerciseModelResource->setComplete($exerciseModel->getComplete());

        // required resources
        $rr = array();
        foreach ($exerciseModel->getRequiredExerciseResources() as $req) {
            /** @var ExerciseResource $req */
            $rr[] = $req->getId();
        }
        $exerciseModelResource->setRequiredExerciseResources($rr);

        // required knowledges
        $rn = array();
        foreach ($exerciseModel->getRequiredKnowledges() as $req) {
            /** @var Knowledge $req */
            $rn[] = $req->getId();
        }
        $exerciseModelResource->setRequiredKnowledges($rn);

        return $exerciseModelResource;
    }
}
