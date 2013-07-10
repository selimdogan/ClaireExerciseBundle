<?php

namespace SimpleIT\ClaireAppBundle\Repository\User;

use Doctrine\Common\Collections\Collection;
use SimpleIT\AppBundle\Repository\AppRepository;
use SimpleIT\ApiResourcesBundle\User\AuthorResource;
use SimpleIT\Utils\Collection\CollectionInformation;

/**
 * Class CourseAuthorRepository
 *
 * @author Kévin Letord <kevin.letord@simple-it.fr>
 */
class AuthorByCourseRepository extends AppRepository
{
    /**
     * @var string
     */
    protected $path = 'courses/{courseIdentifier}/authors/{authorId}';

    /**
     * @var  string
     */
    protected $resourceClass = 'SimpleIT\ApiResourcesBundle\User\AuthorResource';

    /**
     * Find all authors of a course
     *
     * @param int | string          $courseIdentifier      Course id | slug
     * @param CollectionInformation $collectionInformation Collection information
     *
     * @return Collection
     */
    public function findAll($courseIdentifier, CollectionInformation $collectionInformation = null)
    {
        return parent::findAllResources(
            array(
                'courseIdentifier' => $courseIdentifier
            ),
            $collectionInformation
        );
    }

    /**
     * Associate an author to a course
     *
     * @param int | string   $courseIdentifier Course id | slug
     * @param AuthorResource $author           Author
     * @param array          $parameters       Parameters
     *
     * @return AuthorResource
     */
    public function insert($courseIdentifier, AuthorResource $author, array $parameters = array())
    {
        return parent::insertResource(
            $author,
            array(
                'courseIdentifier' => $courseIdentifier
            ),
            $parameters
        );
    }

    /**
     * Desassociate an author from a course
     *
     * @param int | string $courseIdentifier Course id | slug
     * @param int          $authorId         Author id
     * @param array        $parameters       Parameters
     */
    public function delete($courseIdentifier, $authorId, array $parameters = array())
    {
        parent::deleteResource(
            array(
                'courseIdentifier' => $courseIdentifier,
                'authorId'         => $authorId
            ),
            $parameters
        );
    }
}
