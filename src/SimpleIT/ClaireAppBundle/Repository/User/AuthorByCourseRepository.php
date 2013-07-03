<?php

namespace SimpleIT\ClaireAppBundle\Repository\User;

use Doctrine\Common\Collections\Collection;
use SimpleIT\AppBundle\Repository\AppRepository;
use SimpleIT\ApiResourcesBundle\User\AuthorResource;

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
     * @param int | string $courseIdentifier Course id | slug
     *
     * @return Collection
     */
    public function findAll($courseIdentifier)
    {
        return parent::findAllResources(
            array(
                'courseIdentifier' => $courseIdentifier
            )
        );
    }

    /**
     * Associate an author to a course
     *
     * @param int | string   $courseIdentifier Course id | slug
     * @param AuthorResource $author           Author
     *
     * @return AuthorResource
     */
    public function insert($courseIdentifier, AuthorResource $author)
    {
        return parent::insertResource(
            $author,
            array(
                'courseIdentifier' => $courseIdentifier
            )
        );
    }

    /**
     * Desassociate an author from a course
     *
     * @param $courseIdentifier
     * @param $authorId
     */
    public function delete($courseIdentifier, $authorId)
    {
        parent::deleteResource(
            array(
                'courseIdentifier' => $courseIdentifier,
                'authorId'         => $authorId
            )
        );
    }
}
