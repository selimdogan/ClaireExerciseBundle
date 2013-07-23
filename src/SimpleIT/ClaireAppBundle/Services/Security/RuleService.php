<?php

namespace SimpleIT\ClaireAppBundle\Services\Security;

use SimpleIT\ApiResourcesBundle\Security\RuleResource;

use SimpleIT\ClaireAppBundle\Repository\Security\SecurityRuleRepository;

use SimpleIT\Utils\Collection\CollectionInformation;

/**
 * Class RuleService
 * 
 * @author Kévin Letord <kevin.letord@simple-it.fr>
 */
class RuleService
{
    /**
     * @var  SecurityRuleRepository
     */
    private $ruleResource;

    /**
     * Get a rule
     *
     * @param $ruleId
     *
     * @return \SimpleIT\ClaireAppBundle\Repository\Security\RuleResource
     */
    public function get($ruleId)
    {
        return $this->ruleResource->find($ruleId);
    }

    /**
     * Get all rules
     *
     * @param CollectionInformation $collectionInformation
     *
     * @return mixed
     */
    public function getAll(CollectionInformation $collectionInformation)
    {
        return $this->ruleResource->findAll($collectionInformation);
    }

    /*
     *  Dependency Setters
     */

    /**
     * Set ruleResource
     *
     * @param SecurityRuleRepository $ruleResource
     */
    public function setRuleResource(SecurityRuleRepository $ruleResource)
    {
        $this->ruleResource = $ruleResource;
    }
}
