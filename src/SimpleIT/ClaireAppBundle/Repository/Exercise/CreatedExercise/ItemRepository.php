<?php

namespace SimpleIT\ExerciseBundle\Repository\CreatedExercise;

use Doctrine\ORM\QueryBuilder;
use SimpleIT\ApiBundle\Exception\ApiNotFoundException;
use SimpleIT\ApiResourcesBundle\Exercise\ExerciseResource;
use SimpleIT\ApiResourcesBundle\Exercise\ItemResource;
use SimpleIT\CoreBundle\Exception\NonExistingObjectException;
use SimpleIT\CoreBundle\Model\Paginator;
use SimpleIT\CoreBundle\Repository\BaseRepository;
use SimpleIT\ExerciseBundle\Entity\CreatedExercise\Attempt;
use SimpleIT\ExerciseBundle\Entity\CreatedExercise\Item;
use SimpleIT\ExerciseBundle\Entity\CreatedExercise\StoredExercise;
use SimpleIT\Utils\Collection\CollectionInformation;
use SimpleIT\Utils\Collection\PaginatorInterface;

/**
 * Item repository
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class ItemRepository extends BaseRepository
{
    /**
     * Find a collection with parameters from collection information
     *
     * @param StoredExercise $storedExercise Exercise
     *
     * @throws ApiNotFoundException
     * @return PaginatorInterface
     */
    public function findAllBy($storedExercise = null)
    {
        $queryBuilder = $this->createQueryBuilder('i');

        if (!is_null($storedExercise)) {
            $queryBuilder->where(
                $queryBuilder->expr()->eq(
                    'i.storedExercise',
                    $storedExercise->getId()
                )
            );
        }

        $queryBuilder->add('orderBy', 'i.id', true);

        $pag = new Paginator($queryBuilder);

        if ($pag->count() === 0) {
            throw new ApiNotFoundException(ItemResource::RESOURCE_NAME);
        }

        return $pag;
    }

    /**
     * Get all the items by attempt Id
     *
     * @param Attempt               $attempt
     * @param CollectionInformation $collectionInformation
     *
     * @return Paginator
     */
    public function findAllByAttempt(Attempt $attempt, $collectionInformation = null)
    {
        $queryBuilder = $this->createQueryBuilder('i');
        $queryBuilder->leftJoin('i.storedExercise', 'se');
        $queryBuilder->leftJoin('se.attempts', 'a');

        $queryBuilder->where($queryBuilder->expr()->eq('a.id', $attempt->getId()));
        $queryBuilder->orderBy('i.id');

        return new Paginator($queryBuilder);
    }

    /**
     * Find an item by id
     *
     * @param int $itemId
     *
     * @return Item
     * @throws NonExistingObjectException
     */
    public function find($itemId)
    {
        $item = parent::find($itemId);
        if ($item === null) {
            throw new NonExistingObjectException();
        }

        return $item;
    }

    /**
     * Get an item of an attempt
     *
     * @param int     $itemId
     * @param Attempt $attempt
     *
     * @return Item
     * @throws NonExistingObjectException
     */
    public function getByAttempt($itemId, Attempt $attempt)
    {
        $queryBuilder = $this->createQueryBuilder('i');
        $queryBuilder->leftJoin('i.storedExercise', 'se');
        $queryBuilder->leftJoin('se.attempts', 'a');

        $queryBuilder->where($queryBuilder->expr()->eq('a.id', $attempt->getId()));
        $queryBuilder->andWhere($queryBuilder->expr()->eq('i.id', $itemId));

        $result = $queryBuilder->getQuery()->getResult();

        if (empty($result)) {
            throw new NonExistingObjectException('Unable to find item ' . $itemId .
            ' for attempt ' . $attempt->getId());
        } else {
            return $result[0];
        }
    }
}
