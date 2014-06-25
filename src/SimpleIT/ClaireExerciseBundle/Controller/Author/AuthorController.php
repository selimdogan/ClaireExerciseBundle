<?php
/**
 * Created by PhpStorm.
 * User: bryan
 * Date: 12/06/14
 * Time: 22:46
 */

namespace SimpleIT\ClaireExerciseBundle\Controller\Author;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class AuthorController extends Controller
{
    public function indexAction()
    {

        return $this->render('SimpleITClaireExerciseBundle:Author:webAuthor.html.twig', array(
        ));

    }
} 