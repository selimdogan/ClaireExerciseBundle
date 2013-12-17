<?php


namespace SimpleIT\ClaireAppBundle\Services\Exercise;

use JMS\Serializer\SerializerInterface;
use SimpleIT\ApiResourcesBundle\Exercise\Exercise\Common\CommonExercise;
use SimpleIT\ApiResourcesBundle\Exercise\ExerciseResource;
use SimpleIT\ApiResourcesBundle\Exercise\ItemResource;
use SimpleIT\ClaireAppBundle\Repository\Exercise\Exercise\ExerciseByOwnerExerciseModelRepository;
use SimpleIT\ClaireAppBundle\Repository\Exercise\Exercise\ExerciseRepository;
use SimpleIT\ClaireAppBundle\Repository\Exercise\Item\ItemByExerciseRepository;

/**
 * Class ExerciseService
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class ExerciseService implements ExerciseServiceInterface
{
    /**
     * @var  ExerciseRepository
     */
    private $exerciseRepository;

    /**
     * @var ExerciseByOwnerExerciseModelRepository
     */
    private $exerciseByOwnerExerciseModelRepository;

    /**
     * @var ItemByExerciseRepository
     */
    private $itemByExerciseRepository;

    /**
     * @var SerializerInterface
     */
    protected $serializer;

    /**
     * Set serializer
     *
     * @param SerializerInterface $serializer
     */
    public function setSerializer(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * Set exerciseByOwnerExerciseModelRepository
     *
     * @param ExerciseByOwnerExerciseModelRepository $exerciseByOwnerExerciseModelRepository
     */
    public function setExerciseByOwnerExerciseModelRepository(
        $exerciseByOwnerExerciseModelRepository
    )
    {
        $this->exerciseByOwnerExerciseModelRepository = $exerciseByOwnerExerciseModelRepository;
    }

    /**
     * Set itemByExerciseRepository
     *
     * @param \SimpleIT\ClaireAppBundle\Repository\Exercise\Item\ItemByExerciseRepository $itemByExerciseRepository
     */
    public function setItemByExerciseRepository($itemByExerciseRepository)
    {
        $this->itemByExerciseRepository = $itemByExerciseRepository;
    }

    /**
     * Set exerciseRepository
     *
     * @param ExerciseRepository $exerciseRepository
     */
    public function setExerciseRepository(ExerciseRepository $exerciseRepository)
    {
        $this->exerciseRepository = $exerciseRepository;
    }

    /**
     * Get an exercise
     *
     * @param int $exerciseId Exercise Id
     *
     * @return ExerciseResource
     */
    public function get($exerciseId)
    {
        return $this->exerciseRepository->find($exerciseId);
    }

    /**
     * Generate a new instance of exercise with this model
     *
     * @param int $ownerExerciseModelId
     *
     * @return ExerciseResource
     */
    public function generate($ownerExerciseModelId)
    {
        return $this->exerciseByOwnerExerciseModelRepository->generate($ownerExerciseModelId);
    }

    /**
     * Get an exercise object
     *
     * @param int $exerciseId
     *
     * @return CommonExercise
     */
    public function getExerciseObjectFromExercise($exerciseId)
    {
        $exerciseResource = $this->get($exerciseId);

        return $exerciseResource->getContent();
    }

    /**
     * Get the ids of the items of an exercise
     *
     * @param int $exerciseId
     *
     * @return array
     */
    public function getItemIds($exerciseId)
    {
        $items = $this->itemByExerciseRepository->findAll($exerciseId);
        $itemIds = array();
        /** @var ItemResource $item */
        foreach ($items as $item)
        {
            $itemIds[] = $item->getItemId();
        }
        return $itemIds;
    }
}
