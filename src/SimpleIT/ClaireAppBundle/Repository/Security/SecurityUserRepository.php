<?php

namespace SimpleIT\ClaireAppBundle\Repository\Security;

use SimpleIT\ApiResourcesBundle\Security\UserResource;
use SimpleIT\AppBundle\Repository\AppRepository;
use SimpleIT\Utils\Collection\CollectionInformation;

/**
 * Class SecurityUserRepository
 * 
 * @author Kévin Letord <kevin.letord@simple-it.fr>
 */
class SecurityUserRepository extends AppRepository
{
    /**
     * @var string
     */
    protected $path = '/security-users/{userId}';

    /**
     * @var  string
     */
    protected $resourceClass = 'SimpleIT\ApiResourcesBundle\Security\UserResource';

    /**
     * Find a security user
     *
     * @param int $userId User id
     *
     * @return UserResource
     */
    public function find($userId)
    {
        return $this->findResource(
            array('userId' => $userId)
        );
    }

    /**
     * Find a list of security user
     *
     * @param CollectionInformation $collectionInformation
     *
     * @return mixed
     */
    public function findAll(CollectionInformation $collectionInformation)
    {
        return parent::findAllResource(array(
                $collectionInformation
            ));
    }

    /**
     * Insert a new user
     *
     * @param UserResource $user
     *
     * @return mixed
     */
    public function insert(UserResource $user)
    {
        return parent::insertResource($user);
    }

    /**
     * Update a security user
     *
     * @param int          $userId User id
     * @param UserResource $user   User
     *
     * @return UserResource
     */
    public function update($userId, UserResource $user)
    {
        return $this->updateResource(
            $user,
            array('userId' => $userId)
        );
    }

    /**
     * Delete a user
     *
     * @param int $userId User id
     */
    public function delete($userId)
    {
        parent::deleteResource(array(
                'userId' => $userId,
            ));
    }
}
