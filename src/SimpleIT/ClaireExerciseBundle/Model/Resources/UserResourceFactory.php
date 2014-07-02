<?php
namespace SimpleIT\ClaireExerciseBundle\Model\Resources;

use Claroline\CoreBundle\Entity\User;

/**
 * Class UserResourceFactory
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
abstract class UserResourceFactory
{

    /**
     * Create a user resource collection
     *
     * @param array $users
     *
     * @return array
     */
    public static function createCollection(array $users)
    {
        $userResources = array();
        foreach ($users as $user) {
            $userResources[] = self::create($user);
        }

        return $userResources;
    }

    /**
     * Create a user resource
     *
     * @param User $user
     *
     * @return UserResource
     */
    public static function create(User $user)
    {
        $userResource = new UserResource();
        $userResource->setId($user->getId());
        $userResource->setFirstName($user->getFirstName());
        $userResource->setLastName($user->getLastName());
        $userResource->setUserName($user->getUsername());

        return $userResource;
    }
}
