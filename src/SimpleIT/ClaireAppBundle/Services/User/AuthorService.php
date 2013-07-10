<?php


namespace SimpleIT\ClaireAppBundle\Services\User;

use SimpleIT\ClaireAppBundle\Repository\User\AuthorByCourseRepository;
use SimpleIT\ClaireAppBundle\Repository\User\AuthorByPartRepository;
use SimpleIT\ClaireAppBundle\Repository\User\AuthorRepository;
use SimpleIT\Utils\Collection\CollectionInformation;

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
     * Set authorByCourseRepository
     *
     * @param AuthorByCourseRepository $authorByCourseRepository
     */
    public function setAuthorByCourseRepository($authorByCourseRepository)
    {
        $this->authorByCourseRepository = $authorByCourseRepository;
    }

    /**
     * Set authorByPartRepository
     *
     * @param AuthorByPartRepository $authorByPartRepository
     */
    public function setAuthorByPartRepository($authorByPartRepository)
    {
        $this->authorByPartRepository = $authorByPartRepository;
    }

    /**
     * Set authorRepository
     *
     * @param AuthorRepository $authorRepository
     */
    public function setAuthorRepository($authorRepository)
    {
        $this->authorRepository = $authorRepository;
    }

    /**
     * Get all the authors of a course
     *
     * @param integer | string      $courseIdentifier           Course id | slug
     * @param CollectionInformation $collectionInformation      Collection information
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAllByCourse(
        $courseIdentifier,
        CollectionInformation $collectionInformation = null
    )
    {
        return $this->authorByCourseRepository->findAll($courseIdentifier, $collectionInformation);
    }
}
