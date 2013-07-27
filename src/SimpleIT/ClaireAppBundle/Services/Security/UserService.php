<?php

namespace SimpleIT\ClaireAppBundle\Services\Security;

use SimpleIT\ApiResourcesBundle\Security\GroupResource;
use SimpleIT\ApiResourcesBundle\Security\RuleResource;
use SimpleIT\ApiResourcesBundle\Security\UserResource;

use SimpleIT\ClaireAppBundle\Repository\Security\SecurityGroupBySecurityUserRepository;
use SimpleIT\ClaireAppBundle\Repository\Security\SecurityRuleBySecurityUserRepository;
use SimpleIT\ClaireAppBundle\Repository\Security\SecurityUserRepository;

use SimpleIT\Utils\Collection\CollectionInformation;

/**
 * Class UserService
 *
 * @author Kévin Letord <kevin.letord@simple-it.fr>
 */
class UserService
{
    /**
     * @var SecurityGroupBySecurityUserRepository
     */
    private $groupByUserRepository;

    /**
     * @var SecurityRuleBySecurityUserRepository
     */
    private $ruleByUserRepository;

    /**
     * @var SecurityUserRepository
     */
    private $userRepository;

    /**
     * Get a user
     *
     * @param int $userId User id
     *
     * @return UserResource
     */
    public function get($userId)
    {
        return $this->userRepository->find($userId);
    }

    /**
     * Get all users matching collection information
     *
     * @param CollectionInformation $collectionInformation Collection information
     *
     * @return \SimpleIT\Utils\Collection\PaginatedCollection
     */
    public function getAll(CollectionInformation $collectionInformation = null)
    {
        return $this->userRepository->findAll($collectionInformation);
    }

    /**
     * Get user's security groups
     *
     * @param int                   $userId                User id
     * @param CollectionInformation $collectionInformation Collection Information
     *
     * @return \SimpleIT\Utils\Collection\PaginatedCollection
     */
    public function getGroups($userId, CollectionInformation $collectionInformation = null)
    {
        return $this->groupByUserRepository->findAll($userId, $collectionInformation);
    }

    /**
     * Get user's security rules
     *
     * @param int                   $userId                User id
     * @param CollectionInformation $collectionInformation Collection Information
     *
     * @return \SimpleIT\Utils\Collection\PaginatedCollection
     */
    public function getRules($userId, CollectionInformation $collectionInformation = null)
    {
        return $this->ruleByUserRepository->findAll($userId, $collectionInformation);
    }

    /**
     * Add a new user
     *
     * @param UserResource $user
     *
     * @return UserResource
     */
    public function add(UserResource $user)
    {
        return $this->userRepository->insert($user);
    }

    /**
     * Save a user
     *
     * @param int          $userId User id
     * @param UserResource $user   User
     *
     * @return UserResource
     */
    public function save($userId, UserResource $user)
    {
        return $this->userRepository->update($userId, $user);
    }

    /**
     * Add a security group to an user
     *
     * @param int           $userId User id
     * @param GroupResource $group  Group
     *
     * @return GroupResource
     */
    public function addGroup($userId, GroupResource $group)
    {
        return $this->groupByUserRepository->insert($userId, $group);
    }

    /**
     * Add a security rule to an user
     *
     * @param int          $userId User id
     * @param RuleResource $rule   Rule
     *
     * @return RuleResource
     */
    public function addRule($userId, RuleResource $rule)
    {
        return $this->ruleByUserRepository->insert($userId, $rule);
    }

    /**
     * Remove a user
     *
     * @param int $userId User id
     */
    public function remove($userId)
    {
        $this->userRepository->delete($userId);
    }

    /**
     * Remove a user's security group
     *
     * @param int $userId  User id
     * @param int $groupId Group id
     */
    public function removeGroup($userId, $groupId)
    {
        $this->groupByUserRepository->delete($userId, $groupId);
    }

    /**
     * Remove a user's security rule
     *
     * @param int $userId
     * @param int $ruleId
     */
    public function removeRule($userId, $ruleId)
    {
        $this->ruleByUserRepository->delete($userId, $ruleId);
    }

    /**
     * Clear all user's security groups
     *
     * @param int $userId
     */
    public function clearGroups($userId)
    {
        $this->groupByUserRepository->deleteAll($userId);
    }

    /**
     * Clear all user's security rules
     *
     * @param int $userId
     */
    public function clearRules($userId)
    {
        $this->ruleByUserRepository->deleteAll($userId);
    }

    /*
     *  Dependency setter
     */

    /**
     * Set groupByUserRepository
     *
     * @param SecurityGroupBySecurityUserRepository $groupByUserRepository
     */
    public function setGroupByUserRepository(
        SecurityGroupBySecurityUserRepository $groupByUserRepository
    )
    {
        $this->groupByUserRepository = $groupByUserRepository;
    }

    /**
     * Set ruleByUserRepository
     *
     * @param SecurityRuleBySecurityUserRepository $ruleByUserRepository
     */
    public function setRuleByUserRepository(
        SecurityRuleBySecurityUserRepository $ruleByUserRepository
    )
    {
        $this->ruleByUserRepository = $ruleByUserRepository;
    }

    /**
     * Set userRepository
     *
     * @param mixed $userRepository
     */
    public function setUserRepository(SecurityUserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

}
