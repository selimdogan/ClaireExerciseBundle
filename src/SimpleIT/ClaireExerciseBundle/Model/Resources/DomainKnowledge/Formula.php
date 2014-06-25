<?php

namespace SimpleIT\ClaireExerciseBundle\Model\Resources\DomainKnowledge;

use JMS\Serializer\Annotation as Serializer;
use SimpleIT\ApiResourcesBundle\Exception\InvalidKnowledgeException;
use SimpleIT\ClaireExerciseBundle\Model\Resources\DomainKnowledge\Formula\Unknown;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Formula
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class Formula extends CommonKnowledge
{
    /**
     * @var string $equation
     * @Serializer\Type("string")
     * @Serializer\Groups({"details", "knowledge_storage"})
     * @Assert\NotBlank(groups={"create"})
     */
    private $equation;

    /**
     * @var array $variables
     * @Serializer\Type("array<SimpleIT\ClaireExerciseBundle\Model\Resources\DomainKnowledge\Formula\Variable>")
     * @Serializer\Groups({"details", "knowledge_storage"})
     * @Assert\NotBlank(groups={"create"})
     * @Assert\Valid
     */
    private $variables = array();

    /**
     * @var Unknown $unknown
     * @Serializer\Type("SimpleIT\ClaireExerciseBundle\Model\Resources\DomainKnowledge\Formula\Unknown")
     * @Serializer\Groups({"details", "knowledge_storage"})
     * @Assert\NotBlank(groups={"create"})
     * @Assert\Valid
     */
    private $unknown;

    /**
     * Set equation
     *
     * @param string $equation
     */
    public function setEquation($equation)
    {
        $this->equation = $equation;
    }

    /**
     * Get equation
     *
     * @return string
     */
    public function getEquation()
    {
        return $this->equation;
    }

    /**
     * Set variables
     *
     * @param array $variables
     */
    public function setVariables($variables)
    {
        $this->variables = $variables;
    }

    /**
     * Get variables
     *
     * @return array
     */
    public function getVariables()
    {
        return $this->variables;
    }

    /**
     * Set unknown
     *
     * @param \SimpleIT\ClaireExerciseBundle\Model\Resources\DomainKnowledge\Formula\Unknown $unknown
     */
    public function setUnknown($unknown)
    {
        $this->unknown = $unknown;
    }

    /**
     * Get unknown
     *
     * @return \SimpleIT\ClaireExerciseBundle\Model\Resources\DomainKnowledge\Formula\Unknown
     */
    public function getUnknown()
    {
        return $this->unknown;
    }

    /**
     * Validate formula
     *
     * @throws InvalidKnowledgeException
     */
    public function  validate($param = null)
    {
        if (strpos($this->equation, '$') === false || strpos($this->equation, '$') === false) {
            throw new InvalidKnowledgeException('Invalid Formula, no = or $ found');
        }
        if (mb_substr_count($this->equation, '$') > count($this->variables) + 1) {
            throw new InvalidKnowledgeException('To much variables in expression');
        }
    }
}
