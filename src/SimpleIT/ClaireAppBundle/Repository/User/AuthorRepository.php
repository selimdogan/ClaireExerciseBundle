<?php

namespace SimpleIT\ClaireAppBundle\Repository\User;

use Doctrine\Common\Collections\Collection;
use SimpleIT\ApiResourcesBundle\User\AuthorResource;
use SimpleIT\AppBundle\Repository\AppRepository;
use SimpleIT\Utils\Collection\CollectionInformation;

/**
 * Class AuthorRepository
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class AuthorRepository extends AppRepository
{

    /** URL for authors ressources */
    const URL_AUTHORS = '/authors/';

    /** URL for courses collection in an author ressource */
    const URL_COURSES = '/courses/';

    /**
     * @var string
     */
    protected $path = 'authors/{authorId}';

    /**
     * @var  string
     */
    protected $resourceClass = 'SimpleIT\ApiResourcesBundle\User\AuthorResource';

    /**
     * Find all authors
     *
     * @param CollectionInformation $collectionInformation Collection information
     *
     * @return Collection
     */
    public function findAll(CollectionInformation $collectionInformation = null)
    {
        return parent::findAllResources(array(), $collectionInformation);
    }

    /**
     * Find an author
     *
     * @param int   $authorId   Author id
     * @param array $parameters Parameters
     *
     * @return AuthorResource
     */
    public function find($authorId, array $parameters = array())
    {
        return parent::findResource(
            array('authorId' => $authorId),
            $parameters
        );
    }
}
