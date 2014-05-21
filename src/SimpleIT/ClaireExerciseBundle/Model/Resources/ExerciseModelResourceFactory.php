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
abstract class ExerciseModelResourceFactory
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
        $exerciseModelResource->setId($exerciseModel->getId());
        $exerciseModelResource->setTitle($exerciseModel->getTitle());
        $exerciseModelResource->setType($exerciseModel->getType());
        $exerciseModelResource->setAuthor($exerciseModel->getAuthor()->getId());
        $exerciseModelResource->setDraft($exerciseModel->getDraft());
        $exerciseModelResource->setComplete($exerciseModel->getComplete());
        $exerciseModelResource->setPublic($exerciseModel->getPublic());
        $exerciseModelResource->setArchived($exerciseModel->getArchived());
        $exerciseModelResource->setOwner($exerciseModel->getOwner()->getId());

        // Parent and fork from
        if (!is_null($exerciseModel->getParent())) {
            $exerciseModelResource->setParent($exerciseModel->getParent()->getId());
        }
        if (!is_null($exerciseModel->getForkFrom())) {
            $exerciseModelResource->setForkFrom($exerciseModel->getForkFrom()->getId());
        }

        // metadata
        $metadataArray = array();
        foreach ($exerciseModel->getMetadata() as $md) {
            /** @var Metadata $md */
            $metadataArray[$md->getKey()] = $md->getValue();
        }
        $exerciseModelResource->setMetadata($metadataArray);

        // content
        $serializer = SerializerBuilder::create()
            ->addDefaultHandlers()
            ->configureHandlers(
                function (HandlerRegistry $registry) {
                    $registry->registerSubscribingHandler(new AbstractClassForExerciseHandler());
                }
            )
            ->build();
        $content = $serializer->deserialize(
            $exerciseModel->getContent(),
            $exerciseModelResource::getClass($exerciseModel->getType()),
            'json'
        );
        $exerciseModelResource->setContent($content);

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
