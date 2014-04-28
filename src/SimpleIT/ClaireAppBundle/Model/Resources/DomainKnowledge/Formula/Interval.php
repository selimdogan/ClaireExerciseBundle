<?php

namespace SimpleIT\ApiResourcesBundle\Exercise\DomainKnowledge\Formula;

use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Interval
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class Interval
{
    /**
     * @var string
     * @Serializer\Type("string")
     * @Serializer\Groups({"details", "knowledge_storage", "resource_storage", "exercise_model_storage"})
     * @Assert\NotBlank(groups={"create"})
     */
    private $min;

    /**
     * @var string
     * @Serializer\Type("string")
     * @Serializer\Groups({"details", "knowledge_storage", "resource_storage", "exercise_model_storage"})
     * @Assert\NotBlank(groups={"create"})
     */
    private $max;

    /**
     * @var string
     * @Serializer\Type("string")
     * @Serializer\Groups({"details", "knowledge_storage", "resource_storage", "exercise_model_storage"})
     */
    private $step;

    /**
     * Set max
     *
     * @param mixed $max
     */
    public function setMax($max)
    {
        $this->max = $max;
    }

    /**
     * Get max
     *
     * @return mixed
     */
    public function getMax()
    {
        return $this->max;
    }

    /**
     * Set min
     *
     * @param mixed $min
     */
    public function setMin($min)
    {
        $this->min = $min;
    }

    /**
     * Get min
     *
     * @return mixed
     */
    public function getMin()
    {
        return $this->min;
    }

    /**
     * Set step
     *
     * @param mixed $step
     */
    public function setStep($step)
    {
        $this->step = $step;
    }

    /**
     * Get step
     *
     * @return mixed
     */
    public function getStep()
    {
        return $this->step;
    }
}
