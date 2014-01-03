<?php

namespace SimpleIT\ClaireAppBundle\Repository\User;

use Doctrine\Common\Collections\Collection;
use SimpleIT\AppBundle\Repository\AppRepository;
use SimpleIT\Utils\Collection\CollectionInformation;

/**
 * Class CourseByAuthorRepository
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class CourseByAuthorRepository extends AppRepository
{

    /**
     * @var string
     */
    protected $path = 'authors/{authorIdentifier}/courses/';

    /**
     * @var  string
     */
    protected $resourceClass = 'SimpleIT\ApiResourcesBundle\Course\CourseResource';

    /**
     * Find a list of courses of an author
     *
     * @param int                   $authorId              Author id
     * @param CollectionInformation $collectionInformation Collection information
     *
     * @return Collection
     */
    public function findAll($authorId, CollectionInformation $collectionInformation = null)
    {
        return parent::findAllResources(array('authorIdentifier' => $authorId), $collectionInformation);
    }
}
