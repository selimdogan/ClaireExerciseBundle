<?php

namespace SimpleIT\ClaireExerciseBundle\Service\User;

use Claroline\CoreBundle\Entity\Role;
use Claroline\CoreBundle\Entity\User;
use Claroline\CoreBundle\Repository\UserRepository;
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
     * @param UserRepository $userRepository
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

    /**
     * Get all the users
     *
     * @return array
     */
    public function getAll()
    {
        $exerciseModel = null;

        return $this->userRepository->findAll();
    }

    /**
     *
     *
     * @param User $user
     * @param string $roleName
     *
     * @return bool
     */
    public function hasRole($user, $roleName)
    {
        return array_search($roleName, $user->getRoles()) !== false;
    }
}
