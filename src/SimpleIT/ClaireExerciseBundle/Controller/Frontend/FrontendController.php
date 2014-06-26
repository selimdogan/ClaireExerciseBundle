<?php
/**
 * Created by PhpStorm.
 * User: bryan
 * Date: 12/06/14
 * Time: 22:46
 */

namespace SimpleIT\ClaireExerciseBundle\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FrontendController extends Controller
{
    public function indexAction()
    {

        return $this->render('SimpleITClaireExerciseBundle:Frontend:default-layout.html.twig', array(
        ));

    }
} 