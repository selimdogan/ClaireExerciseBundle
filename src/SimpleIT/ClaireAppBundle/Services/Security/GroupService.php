<?php

namespace SimpleIT\ClaireAppBundle\Services\Security;

use SimpleIT\ApiResourcesBundle\Security\GroupResource;
use SimpleIT\ApiResourcesBundle\Security\RuleResource;

use SimpleIT\ClaireAppBundle\Repository\Security\SecurityRuleBySecurityGroupRepository;
use SimpleIT\ClaireAppBundle\Repository\Security\SecurityGroupRepository;

use SimpleIT\Utils\Collection\CollectionInformation;
use SimpleIT\Utils\Collection\PaginatedCollection;

/**
 * Class GroupService
 *
 * @author Kévin Letord <kevin.letord@simple-it.fr>
 */
class GroupService
{
    /**
     * @var  SecurityGroupRepository
     */
    private $groupRepository;

    /**
     * @var  SecurityRuleBySecurityGroupRepository
     */
    private $ruleByGroupRepository;

    /**
     * Get a group
     *
     * @param int $groupId
     *
     * @return GroupResource
     */
    public function get($groupId)
    {
        return $this->groupRepository->find($groupId);
    }

    /**
     * Get all groups that matching collection information
     *
     * @param CollectionInformation $collectionInformation Collection information
     *
     * @return \SimpleIT\Utils\Collection\PaginatedCollection
     */
    public function getAll(CollectionInformation $collectionInformation = null)
    {
        return $this->groupRepository->findAll($collectionInformation);
    }

    /**
     * Get all rules associates with a group
     *
     * @param int                   $groupId               Group id
     * @param CollectionInformation $collectionInformation Collection information
     *
     * @return \SimpleIT\Utils\Collection\PaginatedCollection
     */
    public function getRules($groupId, CollectionInformation $collectionInformation = null)
    {
        return $this->ruleByGroupRepository->findAll($groupId, $collectionInformation);
    }

    /**
     * Add a group
     *
     * @param GroupResource $group
     *
     * @return GroupResource
     */
    public function add(GroupResource $group)
    {
        return $this->groupRepository->insert($group);
    }

    /**
     * Link a security rule to a security group
     *
     * @param int          $groupId Group id
     * @param RuleResource $rule    Rule
     */
    public function addRule($groupId, RuleResource $rule)
    {
        $this->ruleByGroupRepository->insert($groupId, $rule);
    }

    /**
     * Save a group
     *
     * @param int           $groupId Group id
     * @param GroupResource $group   Group
     *
     * @return GroupResource
     */
    public function save($groupId, GroupResource $group)
    {
        return $this->groupRepository->update($groupId, $group);
    }

    /**
     * Remove a group
     *
     * @param int $groupId Group id
     */
    public function remove($groupId)
    {
        $this->groupRepository->delete($groupId);
    }

    /**
     * Unlink a security rule from a security group
     *
     * @param int $groupId Group id
     * @param int $ruleId  Rule id
     */
    public function removeRule($groupId, $ruleId)
    {
        $this->ruleByGroupRepository->delete($groupId, $ruleId);
    }

    /*
     *  Dependency setter
     */

    /**
     * Set ruleByGroupRepository
     *
     * @param SecurityRuleBySecurityGroupRepository $ruleByGroupRepository
     */
    public function setRuleByGroupRepository(
        SecurityRuleBySecurityGroupRepository $ruleByGroupRepository
    )
    {
        $this->ruleByGroupRepository = $ruleByGroupRepository;
    }

    /**
     * Set groupRepository
     *
     * @param SecurityGroupRepository $groupRepository
     */
    public function setGroupRepository(SecurityGroupRepository $groupRepository)
    {
        $this->groupRepository = $groupRepository;
    }

}
