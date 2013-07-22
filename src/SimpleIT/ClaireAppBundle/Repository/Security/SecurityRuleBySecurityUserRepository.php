<?php

namespace SimpleIT\ClaireAppBundle\Repository\Security;

use SimpleIT\AppBundle\Repository\AppRepository;
use SimpleIT\Utils\Collection\CollectionInformation;
use SimpleIT\ApiResourcesBundle\Security\RuleResource;

/**
 * Class SecurityRuleBySecurityUserRepository
 * 
 * @author Kévin Letord <kevin.letord@simple-it.fr>
 */
class SecurityRuleBySecurityUserRepository extends AppRepository
{
    /**
     * @var string
     */
    protected $path = '/security-users/{userId}/security-rules/{ruleId}';

    /**
     * @var  string
     */
    protected $resourceClass = 'SimpleIT\ApiResourcesBundle\Security\RuleResource';

    /**
     * Find a list of security rule
     *
     * @param int                   $userId
     * @param CollectionInformation $collectionInformation
     *
     * @return mixed
     */
    public function findAll($userId, CollectionInformation $collectionInformation)
    {
        return parent::findAllResource(array(
                'userId' => $userId,
                $collectionInformation
            ));
    }

    /**
     * Associate a rule to an user
     *
     * @param              $userId
     * @param RuleResource $rule
     *
     * @return RuleResource
     */
    public function insert($userId, RuleResource $rule)
    {
        return parent::insertResource($rule, array(
                'userId' => $userId,
            ));
    }

    /**
     * Disassociate an rule from an user
     *
     * @param int $userId User id
     * @param int $ruleId Rule id
     */
    public function delete($userId, $ruleId)
    {
        parent::deleteResource(array(
                'userId' => $userId,
                'ruleId' => $ruleId,
            ));
    }

    /**
     * Disassociate all rules from an user
     *
     * @param int $userId User id
     */
    public function deleteAll($userId)
    {
        parent::deleteResource(array(
                'userId' => $userId,
            ));
    }
}
