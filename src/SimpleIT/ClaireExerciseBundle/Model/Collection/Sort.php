<?php

namespace SimpleIT\ClaireExerciseBundle\Model\Collection;

/**
 * Class Sort
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class Sort
{
    /**
     * const ASC = 'asc'
     */
    const ASC = 'asc';

    /**
     * @const DESC = 'desc';
     */
    const DESC = 'desc';

    /**
     * @var  string
     */
    private $property;

    /**
     * @var string asc | desc
     */
    private $order;

    /**
     * Constructor
     *
     * @param string $property Property
     * @param string $order    Order (asc | desc)
     *
     * @throws \InvalidArgumentException
     */
    public function __construct($property, $order = self::ASC)
    {
        $this->property = $property;
        $this->setOrder($order);
    }

    /**
     * Get order
     *
     * @return string
     * @codeCoverageIgnore
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set order
     *
     * @param string $order
     *
     * @throws \InvalidArgumentException
     */
    public function setOrder($order = self::ASC)
    {
        if (is_null($order)) {
            $order = self::ASC;
        }
        $order = strtolower($order);

        if (!in_array($order, array(self::ASC, self::DESC))) {
            throw new \InvalidArgumentException('Order must be asc or desc');
        }

        $this->order = $order;
    }

    /**
     * Get property
     *
     * @return string
     * @codeCoverageIgnore
     */
    public function getProperty()
    {
        return $this->property;
    }

    /**
     * Set property
     *
     * @param string $property
     *
     * @codeCoverageIgnore
     */
    public function setProperty($property)
    {
        $this->property = $property;
    }

}
