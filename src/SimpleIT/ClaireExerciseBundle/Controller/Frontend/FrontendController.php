<?php

namespace SimpleIT\ClaireExerciseBundle\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\InsufficientAuthenticationException;

/**
 * Class FrontendController
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class FrontendController extends Controller
{
    /**
     * Render front application
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\Security\Core\Exception\InsufficientAuthenticationException
     */
    public function indexAction()
    {
        if ($this->get('security.context')->isGranted('ROLE_USER')) {
            return $this->render(
                'SimpleITClaireExerciseBundle:Frontend:default-layout.html.twig',
                array()
            );
        } else {
            throw new InsufficientAuthenticationException();
        }

    }
} 
