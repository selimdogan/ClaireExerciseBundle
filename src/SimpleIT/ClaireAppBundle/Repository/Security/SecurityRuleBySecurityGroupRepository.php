<?php

namespace SimpleIT\ClaireAppBundle\Repository\Security;

use SimpleIT\AppBundle\Repository\AppRepository;
use SimpleIT\Utils\Collection\CollectionInformation;
use SimpleIT\ApiResourcesBundle\Security\RuleResource;
use SimpleIT\AppBundle\Annotation\Cache;

/**
 * Class SecurityRuleBySecurityGroupRepository
 *
 * @author Kévin Letord <kevin.letord@simple-it.fr>
 */
class SecurityRuleBySecurityGroupRepository extends AppRepository
{
    /**
     * @var string
     */
    protected $path = '/security-groups/{groupId}/security-rules/{roleId}';

    /**
     * @var  string
     */
    protected $resourceClass = 'SimpleIT\ApiResourcesBundle\Security\RuleResource';

    /**
     * Find a list of security rule
     *
     * @param integer               $groupId
     * @param CollectionInformation $collectionInformation
     *
     * @Cache
     *
     * @return mixed
     */
    public function findAll($groupId, CollectionInformation $collectionInformation = null)
    {
        return parent::findAllResources(array(
                'groupId' => $groupId,
                $collectionInformation
            ));
    }

    /**
     * Associate a rule to a group
     *
     * @param int          $groupId
     * @param RuleResource $rule
     *
     * @return RuleResource
     */
    public function insert($groupId, RuleResource $rule)
    {
        return parent::insertResource($rule, array(
                'groupId' => $groupId,
            ));
    }

    /**
     * Disassociate an rule from a group
     *
     * @param int $groupId Group id
     * @param int $ruleId  Rule id
     */
    public function delete($groupId, $ruleId)
    {
        parent::deleteResource(array(
                'groupId' => $groupId,
                'ruleId' => $ruleId,
            ));
    }
}
