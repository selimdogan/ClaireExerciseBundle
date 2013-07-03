<?php


namespace SimpleIT\ClaireAppBundle\Services\User;

use SimpleIT\ClaireAppBundle\Repository\User\AuthorByCourseRepository;

/**
 * Class AuthorService
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class AuthorService
{
    /**
     * @var  AuthorByCourseRepository
     */
    private $authorByCourseRepository;

    /**
     * Set authorByCourseRepository
     *
     * @param \SimpleIT\ClaireAppBundle\Repository\User\AuthorByCourseRepository $authorByCourseRepository
     */
    public function setAuthorByCourseRepository($authorByCourseRepository)
    {
        $this->authorByCourseRepository = $authorByCourseRepository;
    }

    /**
     * Get all the authors of a course
     *
     * @param integer | string $courseIdentifier Course id | slug
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAllByCourse($courseIdentifier)
    {
        return $this->authorByCourseRepository->findAll($courseIdentifier);
    }
}
