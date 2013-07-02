<?php

namespace SimpleIT\ClaireAppBundle\Repository\Course;

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
     * Find an author
     *
     * @param $courseIdentifier
     * @param $authorId
     *
     * @return AuthorResource
     */
    public function find($courseIdentifier, $authorId)
    {
        return parent::findResource(array(
                'courseIdentifier' => $courseIdentifier,
                'authorId' => $authorId
            ));
    }

    /**
     * Find an author
     *
     * @param $courseIdentifier
     * @param $authorId
     *
     * @return ArrayCollection
     */
    public function findAll($courseIdentifier, CollectionInformation $collectionInformation)
    {
        return parent::findAllResource(array(
                'courseIdentifier' => $courseIdentifier,
                $collectionInformation
            ));
    }

    /**
     * Associate an author to a course
     *
     * @param int|string     $courseIdentifier
     * @param AuthorResource $author
     *
     * @return AuthorResource
     */
    public function insert($courseIdentifier, AuthorResource $author)
    {
        return parent::insertResource($author, array(
                'courseIdentifier' => $courseIdentifier
            ));
    }

    /**
     * Desassociate an author from a course
     *
     * @param $courseIdentifier
     * @param $authorId
     */
    public function delete($courseIdentifier, $authorId)
    {
        parent::deleteResource(array(
                'courseIdentifier' => $courseIdentifier,
                'authorId' => $authorId
            ));
    }
}
