<?php

namespace SimpleIT\ClaireExerciseBundle\Service;

use Doctrine\ORM\EntityManager;

/**
 * Abstract class for the service which manage the Entity Manager
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
abstract class TransactionalService
{

    /** @var EntityManager The Entity Manager */
    protected $em;

    /**
     * Getter of the Entity Manager
     *
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->em;
    }

    /**
     * Setter of the Entity Manager
     *
     * @param EntityManager $em the Entity Manager
     */
    public function setEntityManager($em)
    {
        $this->em = $em;
    }
}
