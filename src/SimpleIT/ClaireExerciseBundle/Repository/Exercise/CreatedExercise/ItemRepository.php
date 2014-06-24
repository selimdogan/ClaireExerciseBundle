<?php

namespace SimpleIT\ClaireExerciseBundle\Repository\Exercise\CreatedExercise;

use Doctrine\ORM\QueryBuilder;
use SimpleIT\ClaireExerciseBundle\Exception\Api\ApiNotFoundException;
use SimpleIT\ClaireExerciseBundle\Entity\CreatedExercise\Attempt;
use SimpleIT\ClaireExerciseBundle\Entity\CreatedExercise\Item;
use SimpleIT\ClaireExerciseBundle\Entity\CreatedExercise\StoredExercise;
use SimpleIT\ClaireExerciseBundle\Exception\NonExistingObjectException;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseResource;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ItemResource;
use SimpleIT\ClaireExerciseBundle\Repository\BaseRepository;
use SimpleIT\ClaireExerciseBundle\Model\Collection\CollectionInformation;

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
     * @return array
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

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * Get all the items by attempt Id
     *
     * @param Attempt               $attempt
     * @param CollectionInformation $collectionInformation
     *
     * @return array
     */
    public function findAllByAttempt(Attempt $attempt, $collectionInformation = null)
    {
        $queryBuilder = $this->createQueryBuilder('i');
        $queryBuilder->leftJoin('i.storedExercise', 'se');
        $queryBuilder->leftJoin('se.attempts', 'a');

        $queryBuilder->where($queryBuilder->expr()->eq('a.id', $attempt->getId()));
        $queryBuilder->orderBy('i.id');

        return $queryBuilder->getQuery()->getResult();
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
