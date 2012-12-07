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

        $this->view = 'SimpleITClaireAppBundle:Category:list.html.twig';
        $this->viewParameters = array(
            'categories' => $categories['categories']
        );

        return $this->generateView($this->view, $this->viewParameters);
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

        $this->view = 'SimpleITClaireAppBundle:Category:view.html.twig';
        $this->viewParameters = array(
                'category' => $category['category'],
                'tags' => $category['tags']['tags'],
                'branches' => $courses['branches']['branches']
            );

        return $this->generateView($this->view, $this->viewParameters);
    }

    private function generateView($view, $viewParameters)
    {
        return $this->render($view, $viewParameters);
    }

    public function viewTagsAction(Request $request)
    {
        $categorySlug = $request->get('categorySlug');
        $tagSlug = $request->get('slug');

        $this->getCategoriesApi()->prepareCategory($categorySlug);
        $this->getCategoriesApi()->prepareTag($categorySlug, $tagSlug);
        //$this->getCategoriesApi()->prepareAssociatedTags($categorySlug, $tagSlug);

        $tag = $this->getCategoriesApi()->getResult();

        $this->view = 'TutorialBundle:Category:viewTag.html.twig';
        $this->viewParameters = array(
            'tag' => $tag['tags'],
            'category' => $tag['category']
        );


        return $this->generateView($this->view, $this->viewParameters);
    }
}
