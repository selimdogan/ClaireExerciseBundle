<?php

namespace SimpleIT\ClaireExerciseBundle\Service\User;

use Claroline\CoreBundle\Entity\User;

/**
 * Interface for service which manages the users
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
interface UserServiceInterface
{
    /**
     * Find a user by its id
     *
     * @param int $userId
     *
     * @return User
     */
    public function get($userId);
}
