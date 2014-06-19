<?php
namespace SimpleIT\ClaireExerciseBundle\Controller\Api;

/**
 * API Controller
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
abstract class ApiController extends \SimpleIT\ApiBundle\Controller\ApiController
{
    /**
     * Get the current user's id
     *
     * @return int
     */
    protected function getUserId()
    {
        return $this->get('security.context')->getToken()->getUser()->getId();
    }

    /**
     * Get the current user's id or null if she/he is a creator
     *
     * @return int
     */
    protected function getUserIdIfNoCreator()
    {
        if (!$this->get('security.context')->getToken()->getUser()->hasRole('ROLE_WS_CREATOR')) {
            return $this->getUserId();
        } else {
            return null;
        }
    }
}
