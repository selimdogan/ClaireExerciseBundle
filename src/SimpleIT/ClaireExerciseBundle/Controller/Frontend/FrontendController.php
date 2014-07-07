<?php

namespace SimpleIT\ClaireExerciseBundle\Controller\Frontend;

use SimpleIT\ClaireExerciseBundle\Controller\BaseController;
use Symfony\Component\Security\Core\Exception\InsufficientAuthenticationException;

/**
 * Class FrontendController
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class FrontendController extends BaseController
{
    /**
     * Render front application
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\Security\Core\Exception\InsufficientAuthenticationException
     */
    public function indexAction()
    {
        $userId = $this->getUserId();

        return $this->render(
            'SimpleITClaireExerciseBundle:Frontend:default-layout.html.twig',
            array('currentUserId' => $userId)
        );
    }
} 
