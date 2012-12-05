<?php
namespace SimpleIT\ClaireAppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use SimpleIT\ClaireAppBundle\Controller\BaseController;
use SimpleIT\ClaireAppBundle\Form\Type\CourseType;

/**
 * Category controller
 */
class CategoryController extends BaseController
{


    /**
     * List categories
     *
     * @param Request $request Request
     *
     * @return Response
     */
    public function listAction(Request $request)
    {
        $categories = $this->getCategoriesApi()->getCategories();

        return $this->render('SimpleITClaireAppBundle:Categories:list.html.twig', array('categories' => $categories));
    }
}
