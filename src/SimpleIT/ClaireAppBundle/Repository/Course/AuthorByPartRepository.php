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
class AuthorByPartRepository extends AppRepository
{
    /**
     * @var string
     */
    protected $path = 'courses/{courseIdentifier}/parts/{partIdentifier}/authors/{authorId}';

    /**
     * @var  string
     */
    protected $resourceClass = 'SimpleIT\ApiResourcesBundle\User\AuthorResource';

    /**
     * Find an author
     *
     * @param $courseIdentifier
     * @param $partIdentifier
     * @param $authorId
     *
     * @return AuthorResource
     */
    public function find($courseIdentifier, $partIdentifier, $authorId)
    {
        return parent::findResource(array(
                'courseIdentifier' => $courseIdentifier,
                'partIdentifier' => $partIdentifier,
                'authorId' => $authorId
            ));
    }

    /**
     * Find a list of authors
     *
     * @param                       $courseIdentifier
     * @param                       $partIdentifier
     * @param CollectionInformation $collectionInformation
     *
     * @return mixed
     */
    public function findAll($courseIdentifier, $partIdentifier, CollectionInformation $collectionInformation)
    {
        return parent::findAllResource(array(
                'courseIdentifier' => $courseIdentifier,
                'partIdentifier' => $partIdentifier,
                $collectionInformation
            ));
    }

    /**
     * Associate an author to a course
     *
     * @param                $courseIdentifier
     * @param                $partIdentifier
     * @param AuthorResource $author
     *
     * @return AuthorResource
     */
    public function insert($courseIdentifier, $partIdentifier, AuthorResource $author)
    {
        return parent::insertResource($author, array(
                'courseIdentifier' => $courseIdentifier,
                'partIdentifier' => $partIdentifier,
            ));
    }

    /**
     * Disassociate an author from a course
     *
     * @param $courseIdentifier Course identifier
     * @param $partIdentifier   Part identifier
     * @param $authorId         Id of author to unlink
     */
    public function delete($courseIdentifier, $partIdentifier, $authorId)
    {
        parent::deleteResource(array(
                'courseIdentifier' => $courseIdentifier,
                'partIdentifier' => $partIdentifier,
                'authorId' => $authorId
            ));
    }
}
