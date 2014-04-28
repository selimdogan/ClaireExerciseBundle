<?php

namespace SimpleIT\ClaireExerciseBundle\Service\CreatedExercise;

use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use SimpleIT\ClaireExerciseResourceBundle\Model\Resources\AnswerResource;
use SimpleIT\ClaireExerciseResourceBundle\Model\Resources\ItemResource;
use SimpleIT\CoreBundle\Services\TransactionalService;
use SimpleIT\ClaireExerciseBundle\Entity\AnswerFactory;
use SimpleIT\ClaireExerciseBundle\Entity\CreatedExercise\Answer;
use SimpleIT\ClaireExerciseBundle\Entity\CreatedExercise\Item;
use SimpleIT\ClaireExerciseBundle\Exception\AnswerAlreadyExistsException;
use SimpleIT\ClaireExerciseBundle\Repository\Exercise\CreatedExercise\AnswerRepository;
use SimpleIT\ClaireExerciseBundle\Service\ExerciseCreation\ExerciseService;
use SimpleIT\Utils\Collection\PaginatorInterface;
use SimpleIT\CoreBundle\Annotation\Transactional;

/**
 * Service which manages the stored exercises
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class AnswerService extends TransactionalService implements AnswerServiceInterface
{
    /**
     * @var  ExerciseService
     */
    private $exerciseService;

    /**
     * @var ItemService
     */
    private $itemService;

    /**
     * @var AttemptServiceInterface
     */
    private $attemptService;

    /**
     * @var AnswerRepository
     */
    private $answerRepository;

    /**
     * @var SerializerInterface
     */
    protected $serializer;

    /**
     * Set serializer
     *
     * @param SerializerInterface $serializer
     */
    public function setSerializer($serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * Set exerciseService
     *
     * @param ExerciseService $exerciseService
     */
    public function setExerciseService($exerciseService)
    {
        $this->exerciseService = $exerciseService;
    }

    /**
     * Set attemptService
     *
     * @param AttemptServiceInterface $attemptService
     */
    public function setAttemptService($attemptService)
    {
        $this->attemptService = $attemptService;
    }

    /**
     * Set answerRepository
     *
     * @param AnswerRepository $answerRepository
     */
    public function setAnswerRepository($answerRepository)
    {
        $this->answerRepository = $answerRepository;
    }

    /**
     * Set itemService
     *
     * @param ItemService $itemService
     */
    public function setItemService($itemService)
    {
        $this->itemService = $itemService;
    }

    /**
     * Create an answer to an item
     *
     * @param int            $itemId
     * @param AnswerResource $answerResource
     * @param int            $attemptId
     *
     * @throws AnswerAlreadyExistsException
     * @return Answer
     * @Transactional
     */
    public function add($itemId, AnswerResource $answerResource, $attemptId = null)
    {
        // Get the item and the attempt
        /** @var Item $item */
        if (!is_null($attemptId)) {
            if ($this->getAll($itemId, $attemptId)->count() > 0) {
                throw new AnswerAlreadyExistsException();
            }
            $attempt = $this->attemptService->get($attemptId);
            $item = $this->itemService->getByAttempt($itemId, $attemptId);
        } else {
            $item = $this->itemService->get($itemId);
            $attempt = null;
        }

        $this->exerciseService->validateAnswer($item, $answerResource);

        $context = SerializationContext::create();
        $context->setGroups(array("answer_storage", 'Default'));
        $content = $this->serializer->serialize(
            $answerResource,
            'json',
            $context
        );

        $answer = AnswerFactory::create($content, $item, $attempt);
        // Add the answer to the database
        $this->answerRepository->insert($answer);

        return $answer;
    }

    /**
     * Get all answers for an item
     *
     * @param int $itemId Item id
     * @param int $attemptId
     *
     * @return PaginatorInterface
     */
    public function getAll($itemId = null, $attemptId = null)
    {
        $item = null;
        $attempt = null;

        if (!is_null($itemId)) {
            if (!is_null($attemptId)) {
                $item = $this->itemService->getByAttempt($itemId, $attemptId);
                $attempt = $this->attemptService->get($attemptId);
            } else {
                $item = $this->itemService->get($itemId);
            }
        }

        return $this->answerRepository->findAllBy($item, $attempt);
    }
}
