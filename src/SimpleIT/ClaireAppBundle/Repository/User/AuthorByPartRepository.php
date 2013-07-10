<?php

namespace SimpleIT\ClaireAppBundle\Repository\User;

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
     * Find a list of authors
     *
     * @param int | string          $courseIdentifier Course id | slug
     * @param int | string          $partIdentifier   Part id | slug
     * @param CollectionInformation $collectionInformation
     *
     * @return mixed
     */
    public function findAll(
        $courseIdentifier,
        $partIdentifier,
        CollectionInformation $collectionInformation
    )
    {
        return parent::findAllResources(
            array(
                'courseIdentifier' => $courseIdentifier,
                'partIdentifier'   => $partIdentifier
            ),
            $collectionInformation
        );
    }

    /**
     * Associate an author to a course
     *
     * @param int | string   $courseIdentifier Course id | slug
     * @param int | string   $partIdentifier   Part id | slug
     * @param AuthorResource $author           Author resource
     * @param array          $parameters       Parameters
     *
     * @return AuthorResource
     */
    public function insert(
        $courseIdentifier,
        $partIdentifier,
        AuthorResource $author,
        array $parameters = array()
    )
    {
        return parent::insertResource(
            $author,
            array(
                'courseIdentifier' => $courseIdentifier,
                'partIdentifier'   => $partIdentifier,
            ),
            $parameters
        );
    }

    /**
     * Disassociate an author from a course
     *
     * @param int | string $courseIdentifier Course id | slug
     * @param int | string $partIdentifier   Part id | slug
     * @param int          $authorId         Author id
     * @param array        $parameters       Parameters
     */
    public function delete(
        $courseIdentifier,
        $partIdentifier,
        $authorId,
        array $parameters = array()
    )
    {
        parent::deleteResource(
            array(
                'courseIdentifier' => $courseIdentifier,
                'partIdentifier'   => $partIdentifier,
                'authorId'         => $authorId
            ),
            $parameters
        );
    }
}
