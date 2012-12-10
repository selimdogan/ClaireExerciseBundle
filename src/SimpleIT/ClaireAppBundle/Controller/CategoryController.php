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

        $requests['category'] = $this->getCategoriesApi()->getCategory($categorySlug);
        $requests['tags'] = $this->getCategoriesApi()->getTags($categorySlug);
        $requests['courses'] = $this->getCoursesApi()->getCourses($parameters);

        $results = $this->getApiService()->getResults($requests);


        $this->view = 'SimpleITClaireAppBundle:Category:view.html.twig';
        $this->viewParameters = array(
                'category' => $results['category']['content'],
                'tags' => $results['tags']['content'],
                'courses' => $results['courses']['content'],
                'appPager' => $results['courses']['pager'],
            );

        return $this->generateView($this->view, $this->viewParameters);
    }



    public function viewTagAction(Request $request)
    {
        $categorySlug = $request->get('categorySlug');
        $tagSlug = $request->get('slug');

        $this->getCategoriesApi()->prepareCategory($categorySlug);
        $this->getCategoriesApi()->prepareTag($categorySlug, $tagSlug);
        $this->getCategoriesApi()->prepareAssociatedTags($categorySlug, $tagSlug);

        $tag = $this->getCategoriesApi()->getResult();

        $this->view = 'TutorialBundle:Category:viewTag.html.twig';
        $this->viewParameters = array(
            'tag' => $tag['tag'],
            'category' => $tag['category'],
            'associatedTags' => $tag['tags']
        );


        return $this->generateView($this->view, $this->viewParameters);
    }

    protected function generateView($view, $viewParameters)
    {
        return $this->render($view, $viewParameters);
    }
}
