<?php

namespace SimpleIT\ClaireAppBundle\Repository\Security;

use SimpleIT\AppBundle\Repository\AppRepository;
use SimpleIT\Utils\Collection\CollectionInformation;
use SimpleIT\ApiResourcesBundle\Security\GroupResource;

/**
 * Class SecurityGroupRepository
 * 
 * @author Kévin Letord <kevin.letord@simple-it.fr>
 */
class SecurityGroupRepository extends AppRepository
{
    /**
     * @var string
     */
    protected $path = '/security-groups/{groupId}';

    /**
     * @var  string
     */
    protected $resourceClass = 'SimpleIT\ApiResourcesBundle\Security\GroupResource';

    /**
     * Find a security group
     *
     * @param int $groupId Group id
     *
     * @return GroupResource
     */
    public function find($groupId)
    {
        return $this->findResource(
            array('groupId' => $groupId)
        );
    }

    /**
     * Find a list of security group
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
     * Insert a new group
     *
     * @param GroupResource $group
     *
     * @return GroupResource
     */
    public function insert(GroupResource $group)
    {
        return parent::insertResource($group);
    }

    /**
     * Update a security user
     *
     * @param int           $groupId group id
     * @param GroupResource $group   Group
     *
     * @return GroupResource
     */
    public function update($groupId, GroupResource $group)
    {
        return $this->updateResource(
            $group,
            array('groupId' => $groupId)
        );
    }

    /**
     * Delete a group
     *
     * @param int $groupId Group id
     */
    public function delete($groupId)
    {
        parent::deleteResource(array(
                'groupId' => $groupId,
            ));
    }
}
