<?php

namespace SimpleIT\ClaireExerciseBundle\Service\User;

use SimpleIT\ClaireExerciseBundle\Entity\User\User;
use SimpleIT\ClaireExerciseBundle\Repository\User\UserRepository;
use SimpleIT\ClaireExerciseBundle\Service\TransactionalService;

/**
 * Service which manages the users
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class UserService extends TransactionalService implements UserServiceInterface
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * Set userRepository
     *
     * @param \SimpleIT\ClaireExerciseBundle\Repository\User\UserRepository $userRepository
     */
    public function setUserRepository($userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Find a user by its id
     *
     * @param int $userId
     *
     * @return User
     */
    public function get($userId)
    {
        return $this->userRepository->find($userId);
    }
}
