<?php

/**
 * Author: Sylvain Mauduit <sylvain.mauduit@simple-it.fr>
 */
namespace SimpleIT\ClaireAppBundle\Services\Profile;

use SimpleIT\ClaireAppBundle\Api\ClaireApi;
use SimpleIT\ClaireAppBundle\Repository\User\AuthorRepository;

/**
 * Class AuthorService
 * @package SimpleIT\ClaireAppBundle\Services\Profile
 */
class AuthorService
{
    /**
     * @var ClaireApi
     */
    private $claireApi;

    /**
     * @var AuthorRepository
     */
    private $authorRepository;

    /**
     * Setter for $claireApi
     *
     * @param ClaireApi $claireApi
     */
    public function setClaireApi(ClaireApi $claireApi)
    {
        $this->claireApi = $claireApi;
    }

    /**
     * Set
     * @param \SimpleIT\ClaireAppBundle\Repository\User\AuthorRepository $authorRepository
     */
    public function setAuthorRepository($authorRepository)
    {
        $this->authorRepository = $authorRepository;
    }


    /**
     * @param integer|string $authorIdentifier Author identifier
     *
     * @return array
     */
    public function getCourses($authorIdentifier)
    {
        return $this->authorRepository->findCourses($authorIdentifier);
    }

    /**
     * @param integer|string $courseIdentifier Course identifier
     *
     * @return array
     */
    public function getAuthorsByCourse($courseIdentifier)
    {
        return $this->authorRepository->findByCourse($courseIdentifier);
    }
}
