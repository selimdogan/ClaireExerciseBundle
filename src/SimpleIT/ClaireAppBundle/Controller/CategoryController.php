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
     * View a category
     *
     * @param Request $request Request
     *
     * @return Response
     */
    public function viewAction(Request $request)
    {
        $categorySlug = $request->get('slug');

        $this->getCategoriesApi()->getCategory($categorySlug);
        $this->getCategoriesApi()->getTags($categorySlug);
        $category = $this->getCategoriesApi()->getData();

        return $this->render('TutorialBundle:Category:view.html.twig',
            array(
                'category' => $category
            )
        );
    }

    /**
     * List categories
     *
     * @param Request $request Request
     *
     * @return Response
     */
    public function listAction(Request $request)
    {
        $this->getCategoriesApi()->getCategories();
        $categories = $this->getCategoriesApi()->getData();

        return $this->render('TutorialBundle:Category:list.html.twig',
            array(
                'categories' => $categories
            )
        );
    }
}
