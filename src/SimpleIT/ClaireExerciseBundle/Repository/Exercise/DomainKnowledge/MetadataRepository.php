<?php

namespace SimpleIT\ClaireExerciseBundle\Repository\Exercise\DomainKnowledge;

use Doctrine\ORM\QueryBuilder;
use SimpleIT\CoreBundle\Exception\NonExistingObjectException;
use SimpleIT\CoreBundle\Repository\BaseRepository;
use SimpleIT\ClaireExerciseBundle\Entity\DomainKnowledge\Metadata;
use SimpleIT\ClaireExerciseBundle\Entity\DomainKnowledge\OwnerKnowledge;
use SimpleIT\Utils\Collection\CollectionInformation;
use SimpleIT\Utils\Collection\PaginatorInterface;
use SimpleIT\Utils\Collection\Sort;

/**
 * Knowledge Metadata repository
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class MetadataRepository extends BaseRepository
{
    /**
     * Find a model by id
     *
     * @param array $parameters
     *
     * @return Metadata
     * @throws NonExistingObjectException
     */
    public function find($parameters)
    {
        $metadata = parent::find($parameters);
        if ($metadata === null) {
            throw new NonExistingObjectException();
        }

        return $metadata;
    }

    /**
     * Return all the metadata
     *
     * @param CollectionInformation $collectionInformation
     * @param OwnerKnowledge        $ownerKnowledge
     *
     * @return PaginatorInterface
     */
    public function findAllBy(
        CollectionInformation $collectionInformation,
        $ownerKnowledge
    )
    {
        $queryBuilder = $this->createQueryBuilder('m');

        if (!is_null($ownerKnowledge)) {
            $queryBuilder->andWhere(
                $queryBuilder->expr()->eq(
                    'm.ownerKnowledge',
                    $ownerKnowledge->getId()
                )
            );
        }

        // Handle Collection Information
        if (!is_null($collectionInformation)) {
            $sorts = $collectionInformation->getSorts();

            foreach ($sorts as $sort) {
                /** @var Sort $sort */
                switch ($sort->getProperty()) {
                    case 'ownerKnowledgeId':
                        $queryBuilder->addOrderBy('m.ownerKnowledge', $sort->getOrder());
                        break;
                    case 'key':
                        $queryBuilder->addOrderBy('m.key', $sort->getOrder());
                        break;
                }
            }
            $queryBuilder = $this->setRange($queryBuilder, $collectionInformation);
        } else {
            $queryBuilder->addOrderBy('m.ownerKnowledge', 'ASC');
            $queryBuilder->addOrderBy('m.key', 'ASC');
        }

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * Delete all the metadata for an owner knowledge
     *
     * @param int $ownerKnowledgeId
     */
    public function deleteAllByOwnerKnowledge($ownerKnowledgeId)
    {
        $sql = 'DELETE FROM claire_exercise_knowledge_metadata AS rm WHERE rm.owner_knowledge_id = :ownerKnowledgeId';

        $connection = $this->_em->getConnection();
        $connection->executeQuery(
            $sql,
            array('ownerKnowledgeId' => $ownerKnowledgeId)
        );
    }
}
