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
     * Category list
     *
     * @return Symfony\Component\HttpFoundation\Response
     *
     */
    public function listAction()
    {
        $this->getCategoriesApi()->prepareCategories();
        $categories = $this->getCategoriesApi()->getResult();

        return $this->render('TutorialBundle:Category:list.html.twig', array('categories' => $categories['categories']));
    }

    /**
     * Category list
     *
     * @return Symfony\Component\HttpFoundation\Response
     *
     */
    public function viewAction(Request $request)
    {
        $categorySlug = $request->get('slug');
        $parameters = $request->query->all();
        $parameters['category'] = $categorySlug;

        $this->getCategoriesApi()->prepareCategory($categorySlug);
        $this->getCategoriesApi()->prepareTags($categorySlug);
        $this->getCoursesApi()->prepareCourses($parameters);

        $category = $this->getCategoriesApi()->getResult();
        $courses = $this->getCoursesApi()->getResult();

        return $this->render('TutorialBundle:Category:view.html.twig',
            array(
                'category' => $category['category'],
                'tags' => $category['tags']['tags'],
                'courses' => $courses['branches']
            )
        );
    }
}
