<?php

namespace SimpleIT\ClaireExerciseBundle\Repository\Exercise\DomainKnowledge;

use Doctrine\DBAL\DBALException;
use Doctrine\ORM\QueryBuilder;
use SimpleIT\ClaireExerciseBundle\Entity\User\User;
use SimpleIT\CoreBundle\Exception\NonExistingObjectException;
use SimpleIT\CoreBundle\Model\Paginator;
use SimpleIT\CoreBundle\Repository\BaseRepository;
use SimpleIT\ClaireExerciseBundle\Entity\DomainKnowledge\Knowledge;
use SimpleIT\ClaireExerciseBundle\Exception\EntityAlreadyExistsException;
use SimpleIT\ClaireExerciseBundle\Exception\EntityDeletionException;
use SimpleIT\Utils\Collection\CollectionInformation;
use SimpleIT\Utils\Collection\PaginatorInterface;
use SimpleIT\Utils\Collection\Sort;

/**
 * Knowledge repository
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class KnowledgeRepository extends BaseRepository
{
    /**
     * Find a knowledge by id
     *
     * @param mixed $knowledgeId
     *
     * @return Knowledge
     * @throws NonExistingObjectException
     */
    public function find($knowledgeId)
    {
        $knowledge = parent::find($knowledgeId);
        if ($knowledge === null) {
            throw new NonExistingObjectException();
        }

        return $knowledge;
    }

    /**
     * Return all the knowledges
     *
     * @param CollectionInformation $collectionInformation
     * @param User                  $author
     *
     * @return PaginatorInterface
     */
    public function findAllBy(
        $collectionInformation = null,
        $author = null
    )
    {
        $queryBuilder = $this->createQueryBuilder('dk');

        if (!is_null($author)) {
            $queryBuilder->andWhere(
                $queryBuilder->expr()->eq(
                    'dk.author',
                    $author->getId()
                )
            );
        }

        // Handle Collection Information
        if (!is_null($collectionInformation)) {
            $filters = $collectionInformation->getFilters();
            foreach ($filters as $filter => $value) {
                switch ($filter) {
                    case 'authorId':
                        $queryBuilder->andWhere(
                            $queryBuilder->expr()->eq(
                                'dk.author',
                                $value
                            )
                        );
                        break;
                    case 'id':
                        $queryBuilder->andWhere(
                            $queryBuilder->expr()->eq(
                                'dk.id',
                                $value
                            )
                        );
                        break;
                    case 'type':
                        $queryBuilder->andWhere(
                            $queryBuilder->expr()->eq(
                                'dk.type',
                                $value
                            )
                        );
                        break;
                    default:
                        break;
                }
            }
            $sorts = $collectionInformation->getSorts();

            foreach ($sorts as $sort) {
                /** @var Sort $sort */
                switch ($sort->getProperty()) {
                    case 'authorId':
                        $queryBuilder->addOrderBy('dk.author', $sort->getOrder());
                        break;
                    case 'type':
                        $queryBuilder->addOrderBy('dk.type', $sort->getOrder());
                        break;
                    case 'id':
                        $queryBuilder->addOrderBy('dk.id', $sort->getOrder());
                        break;
                }
            }
            $queryBuilder = $this->setRange($queryBuilder, $collectionInformation);
        } else {
            $queryBuilder->addOrderBy('dk.id');
        }

        return new Paginator($queryBuilder);
    }

    /**
     * Add a required knowledge to a knowledge
     *
     * @param int       $knowledgeId
     * @param Knowledge $requiredKnowledge
     *
     * @throws EntityAlreadyExistsException
     */
    public function addRequiredKnowledge($knowledgeId, Knowledge $requiredKnowledge)
    {
        $sql = 'INSERT INTO claire_exercise_knowledge_knowledge_requirement VALUES (:knowledgeId,:requiredId)';

        $connection = $this->_em->getConnection();
        try {
            $connection->executeQuery(
                $sql,
                array(
                    'knowledgeId' => $knowledgeId,
                    'requiredId'  => $requiredKnowledge->getId(),
                )
            );
        } catch (DBALException $e) {
            throw new EntityAlreadyExistsException("Required knowledge");
        }
    }

    /**
     * Delete a required knowledge
     *
     * @param int       $knowledgeId
     * @param Knowledge $requiredKnowledge
     *
     * @throws EntityDeletionException
     */
    public function deleteRequiredKnowledge($knowledgeId, Knowledge $requiredKnowledge)
    {
        $sql = 'DELETE FROM claire_exercise_knowledge_knowledge_requirement AS rrq WHERE rrq.knowledge_id = :knowledgeId AND rrq.required_id = :requiredId';

        $connection = $this->_em->getConnection();
        $stmt = $connection->executeQuery(
            $sql,
            array(
                'knowledgeId' => $knowledgeId,
                'requiredId'  => $requiredKnowledge->getId(),
            )
        );

        if ($stmt->rowCount() != 1) {
            throw new EntityDeletionException();
        }
    }
}
