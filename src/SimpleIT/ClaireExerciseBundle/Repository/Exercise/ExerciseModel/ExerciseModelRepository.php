<?php

namespace SimpleIT\ClaireExerciseBundle\Repository\Exercise\ExerciseModel;

use Doctrine\DBAL\DBALException;
use Doctrine\ORM\QueryBuilder;
use SimpleIT\ClaireExerciseBundle\Entity\DomainKnowledge\Knowledge;
use SimpleIT\ClaireExerciseBundle\Entity\User\User;
use SimpleIT\CoreBundle\Exception\NonExistingObjectException;
use SimpleIT\CoreBundle\Model\Paginator;
use SimpleIT\CoreBundle\Repository\BaseRepository;
use SimpleIT\ClaireExerciseBundle\Entity\ExerciseModel\ExerciseModel;
use SimpleIT\ClaireExerciseBundle\Entity\ExerciseResource\ExerciseResource;
use SimpleIT\ClaireExerciseBundle\Exception\EntityAlreadyExistsException;
use SimpleIT\ClaireExerciseBundle\Exception\EntityDeletionException;
use SimpleIT\ClaireExerciseBundle\Exception\FilterException;
use SimpleIT\Utils\Collection\CollectionInformation;
use SimpleIT\Utils\Collection\Sort;

/**
 * ExerciseModel repository
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class ExerciseModelRepository extends BaseRepository
{
    /**
     * Find a model by id
     *
     * @param mixed $exerciseModelId
     *
     * @return ExerciseModel
     * @throws NonExistingObjectException
     */
    public function find($exerciseModelId)
    {
        $exerciseModel = parent::find($exerciseModelId);
        if ($exerciseModel === null) {
            throw new NonExistingObjectException();
        }

        return $exerciseModel;
    }

    /**
     * Find a list of exercise models according to a type an to metadata contained in a
     * collection information
     *
     * @param CollectionInformation $collectionInformation
     * @param User                  $owner
     * @param User                  $author
     * @param ExerciseModel         $parent
     * @param ExerciseModel         $forkFrom
     * @param boolean               $isRoot
     * @param boolean               $isPointer
     *
     * @throws \SimpleIT\ClaireExerciseBundle\Exception\FilterException
     * @return array
     */
    public function findAll(
        $collectionInformation = null,
        $owner = null,
        $author = null,
        $parent = null,
        $forkFrom = null,
        $isRoot = null,
        $isPointer = null
    )
    {
        $metadata = array();
        $keywords = array();

        $qb = $this->createQueryBuilder('em')
            ->select();

        if (!is_null($owner)) {
            $qb->where(
                $qb->expr()->eq(
                    'em.owner',
                    $owner->getId()
                )
            );
        }

        if (!is_null($author)) {
            $qb->where(
                $qb->expr()->eq(
                    'em.author',
                    $author->getId()
                )
            );
        }

        if (!is_null($parent)) {
            $qb->where(
                $qb->expr()->eq(
                    'em.parent',
                    $parent->getId()
                )
            );
        }

        if (!is_null($forkFrom)) {
            $qb->where(
                $qb->expr()->eq(
                    'em.forkFrom',
                    $forkFrom->getId()
                )
            );
        }

        if ($isPointer === true) {
            $qb->where($qb->expr()->isNotNull('em.parent'));
        } else {
            $qb->where($qb->expr()->isNotNull('em.content'));
        }

        if ($isRoot === true) {
            $qb->where($qb->expr()->isNull('em.forkFrom'));
        }

        if ($collectionInformation !== null) {

            $filters = $collectionInformation->getFilters();
            foreach ($filters as $filter => $value) {
                switch ($filter) {
                    case ('author'):
                        $qb->andWhere($qb->expr()->eq('em.author', "'" . $value . "'"));
                        break;
                    case ('owner'):
                        $qb->andWhere($qb->expr()->eq('em.owner', $value));
                        break;
                    case ('type'):
                        if (is_array($value)) {
                            $qpType = '';
                            foreach ($value as $val) {
                                if ($qpType !== '') {
                                    $qpType = $qb->expr()->orX(
                                        $qpType,
                                        $qb->expr()->eq('em.type', "'" . $val . "'")
                                    );
                                } else {
                                    $qpType = $qb->expr()->eq('em.type', "'" . $val . "'");
                                }
                            }
                            $qb->andWhere($qpType);
                        } else {
                            $qb->andWhere($qb->expr()->eq('em.type', "'" . $value . "'"));
                        }
                        break;
                    case ('metadata'):
                        $metadata = $this->metadataToArray($value);
                        break;
                    case ('keywords'):
                        $keywords = $this->keywordsToArray($value);
                        break;
                    case ('draft'):
                        if ($value !== "true" && $value !== "false") {
                            throw new FilterException('draft filter must be true or false');
                        }
                        $qb->where($qb->expr()->eq('em.draft', "'" . $value . "'"));
                        break;
                    case ('complete'):
                        if ($value !== "true" && $value !== "false") {
                            throw new FilterException('complete filter must be true or false');
                        }
                        $qb->where($qb->expr()->eq('em.complete', "'" . $value . "'"));
                        break;
                    case ('public-except-user'):
                        if (!is_numeric($value)) {
                            throw new FilterException('public-except-user filter must be numeric');
                        }
                        $qb = $this->addPublicExceptUser($qb, $value);
                        break;
                }
            }

            // Metadata
            $i = 0;
            foreach ($metadata as $metaKey => $value) {
                $alias = 'm' . $i;
                $qb->leftJoin('em.metadata', $alias);

                $qb->andWhere(
                    $qb->expr()->andX(
                        $qb->expr()->eq($alias . '.key', "'" . $metaKey . "'"),
                        $qb->expr()->eq($alias . '.value', "'" . $value . "'")
                    )
                );

                $i++;
            }

            // Misc keywords
            foreach ($keywords as $keyword) {
                $alias = 'm' . $i;
                $qb->leftJoin('em.metadata', $alias);

                $qb->andWhere(
                    $qb->expr()->orX(
                        $qb->expr()->eq($alias . '.key', "'" . $keyword . "'"),
                        $qb->expr()->like($alias . '.value', "'%" . $keyword . "%'")
                    )
                );

                $i++;
            }

            $sorts = $collectionInformation->getSorts();

            if (!empty($sorts)) {
                foreach ($sorts as $sort) {
                    /** @var Sort $sort */
                    switch ($sort->getProperty()) {
                        case 'title':
                            $qb->addOrderBy('em.title', $sort->getOrder());
                            break;
                        case 'id':
                            $qb->addOrderBy('em.id', $sort->getOrder());
                            break;
                        case 'type':
                            $qb->addOrderBy('em.type', $sort->getOrder());
                            break;
                        case 'author':
                            $qb->addOrderBy('em.author', $sort->getOrder());
                            break;
                    }
                }
                $qb = $this->setRange($qb, $collectionInformation);
            } else {
                $qb->addOrderBy('em.id');
            }
        }

        return new Paginator($qb);
    }

    /**
     * Add a required resource to an exercise model
     *
     * @param int              $exerciseModelId
     * @param ExerciseResource $requiredResource
     *
     * @throws EntityAlreadyExistsException
     */
    public function addRequiredResource($exerciseModelId, ExerciseResource $requiredResource)
    {
        $sql = 'INSERT INTO claire_exercise_model_resource_requirement VALUES (:exerciseModelId,:requiredId)';

        $connection = $this->_em->getConnection();
        try {
            $connection->executeQuery(
                $sql,
                array(
                    'exerciseModelId' => $exerciseModelId,
                    'requiredId'      => $requiredResource->getId(),
                )
            );
        } catch (DBALException $e) {
            throw new EntityAlreadyExistsException("Required resource");
        }
    }

    /**
     * Delete a requires resource
     *
     * @param int              $exerciseModelId
     * @param ExerciseResource $requiredResource
     *
     * @throws EntityDeletionException
     */
    public function deleteRequiredResource($exerciseModelId, ExerciseResource $requiredResource)
    {
        $sql = 'DELETE FROM claire_exercise_model_resource_requirement AS emrq WHERE emrq.model_id = :exerciseModelId AND emrq.required_resource_id = :requiredId';

        $connection = $this->_em->getConnection();
        $stmt = $connection->executeQuery(
            $sql,
            array(
                'exerciseModelId' => $exerciseModelId,
                'requiredId'      => $requiredResource->getId(),
            )
        );

        if ($stmt->rowCount() != 1) {
            throw new EntityDeletionException();
        }
    }

    /**
     * Add a required knowledge to an exercise model
     *
     * @param int              $exerciseModelId
     * @param Knowledge $requiredKnowledge
     *
     * @throws EntityAlreadyExistsException
     */
    public function addRequiredKnowledge($exerciseModelId, Knowledge $requiredKnowledge)
    {
        $sql = 'INSERT INTO claire_exercise_model_knowledge_requirement VALUES (:exerciseModelId,:requiredId)';

        $connection = $this->_em->getConnection();
        try {
            $connection->executeQuery(
                $sql,
                array(
                    'exerciseModelId' => $exerciseModelId,
                    'requiredId'      => $requiredKnowledge->getId(),
                )
            );
        } catch (DBALException $e) {
            throw new EntityAlreadyExistsException("Required resource");
        }
    }

    /**
     * Delete a requires resource
     *
     * @param int              $exerciseModelId
     * @param Knowledge $requiredKnowledge
     *
     * @throws EntityDeletionException
     */
    public function deleteRequiredKnowledge($exerciseModelId, Knowledge $requiredKnowledge)
    {
        $sql = 'DELETE FROM claire_exercise_model_knowledge_requirement AS emrq WHERE emrq.model_id = :exerciseModelId AND emrq.required_knowledge_id = :requiredId';

        $connection = $this->_em->getConnection();
        $stmt = $connection->executeQuery(
            $sql,
            array(
                'exerciseModelId' => $exerciseModelId,
                'requiredId'      => $requiredKnowledge->getId(),
            )
        );

        if ($stmt->rowCount() != 1) {
            throw new EntityDeletionException();
        }
    }

    /**
     * Convert the content of keywords filter into an array
     *
     * @param string|array $keywords
     *
     * @return array
     */
    private function keywordsToArray($keywords)
    {
        if (is_array($keywords)) {
            return $keywords;
        } else {
            return array($keywords);
        }
    }

    /**
     * Converts the content of the metadata filter into a key => value array
     *
     * @param string|array $metadata
     *
     * @return array
     */
    private function metadataToArray($metadata)
    {
        $metadataArray = array();
        if (is_array($metadata)) {
            foreach ($metadata as $md) {
                $explode = explode(':', $md);
                $metadataArray[$explode[0]] = $explode[1];
            }
        } else {
            $explode = explode(':', $metadata);
            $metadataArray[$explode[0]] = $explode[1];
        }

        return $metadataArray;
    }

    /**
     * Add the join and the constraints to the query builder to exclude resources already covered
     * by owner resources
     *
     * @param QueryBuilder $qb
     * @param string       $userId
     *
     * @return QueryBuilder
     */
    private function addPublicExceptUser(QueryBuilder $qb, $userId)
    {
        $qb->andWhere(
            $qb->expr()->eq(
                'em.public',
                'true'
            )
        );

        $notIn = $this->getEntityManager()->createQueryBuilder()
            ->select('exMod.id')
            ->from(
                'SimpleIT\ClaireExerciseBundle\Entity\ExerciseModel\ExerciseModel',
                'exMod'
            )
            ->andWhere(
                $qb->expr()->eq(
                    'exMod.owner',
                    $userId
                )
            )
            ->getQuery()
            ->getDQL();

        $qb->andWhere($qb->expr()->notIn('em.id', $notIn));

        return $qb;
    }
}
