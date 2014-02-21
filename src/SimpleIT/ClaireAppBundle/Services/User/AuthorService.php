<?php


namespace SimpleIT\ClaireAppBundle\Services\User;

use SimpleIT\ApiResourcesBundle\Course\CourseResource;
use SimpleIT\ClaireAppBundle\Repository\User\AuthorByCourseRepository;
use SimpleIT\ClaireAppBundle\Repository\User\AuthorByPartRepository;
use SimpleIT\ClaireAppBundle\Repository\User\AuthorRepository;
use SimpleIT\ClaireAppBundle\Repository\User\CourseByAuthorRepository;
use SimpleIT\Utils\Collection\CollectionInformation;
use SimpleIT\Utils\Collection\PaginatedCollection;

/**
 * Class AuthorService
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class AuthorService
{

    /**
     * @var  AuthorRepository
     */
    private $authorRepository;

    /**
     * @var  AuthorByCourseRepository
     */
    private $authorByCourseRepository;

    /**
     * @var  AuthorByPartRepository
     */
    private $authorByPartRepository;

    /**
     * @var  CourseByAuthorRepository
     */
    private $courseByAuthorRepository;

    /**
     * Set authorByCourseRepository
     *
     * @param AuthorByCourseRepository $authorByCourseRepository
     */
    public function setAuthorByCourseRepository(AuthorByCourseRepository $authorByCourseRepository)
    {
        $this->authorByCourseRepository = $authorByCourseRepository;
    }

    /**
     * Set authorByPartRepository
     *
     * @param AuthorByPartRepository $authorByPartRepository
     */
    public function setAuthorByPartRepository(AuthorByPartRepository $authorByPartRepository)
    {
        $this->authorByPartRepository = $authorByPartRepository;
    }

    /**
     * Set authorRepository
     *
     * @param AuthorRepository $authorRepository
     */
    public function setAuthorRepository(AuthorRepository $authorRepository)
    {
        $this->authorRepository = $authorRepository;
    }

    /**
     * Set courseByAuthorRepository
     *
     * @param CourseByAuthorRepository $courseByAuthorRepository
     */
    public function setCourseByAuthorRepository(CourseByAuthorRepository $courseByAuthorRepository)
    {
        $this->courseByAuthorRepository = $courseByAuthorRepository;
    }

    /**
     * Get a list of authors
     *
     * @param CollectionInformation $collectionInformation Collection information
     *
     * @return PaginatedCollection
     */
    public function getAll(CollectionInformation $collectionInformation = null)
    {
        return $this->authorRepository->findAll($collectionInformation);
    }

    /**
     * Get all the authors of a course
     *
     * @param integer | string      $courseIdentifier      Course id | slug
     * @param CollectionInformation $collectionInformation Collection information
     *
     * @return PaginatedCollection
     */
    public function getAllByCourse(
        $courseIdentifier,
        CollectionInformation $collectionInformation = null
    )
    {
        return $this->authorByCourseRepository->findAll($courseIdentifier, $collectionInformation);
    }

    /**
     * Get all the authors of a course to edit
     *
     * @param int                   $courseId              Course id
     * @param string                $status                Status
     * @param CollectionInformation $collectionInformation Collection information
     *
     * @return PaginatedCollection
     */
    public function getAllByCourseToEdit(
        $courseId,
        $status,
        CollectionInformation $collectionInformation
    )
    {
        $collectionInformation->addFilter(CourseResource::STATUS, $status);

        return $this->authorByCourseRepository->findAllToEdit($courseId, $collectionInformation);
    }

    /**
     * Get all the courses of an author
     *
     * @param int | string          $authorIdentifier      Author id | slug
     * @param CollectionInformation $collectionInformation Collection information
     *
     * @return PaginatedCollection
     */
    public function getAllCourses(
        $authorIdentifier,
        CollectionInformation $collectionInformation = null
    )
    {
        return $this->courseByAuthorRepository->findAll($authorIdentifier, $collectionInformation);
    }
}
