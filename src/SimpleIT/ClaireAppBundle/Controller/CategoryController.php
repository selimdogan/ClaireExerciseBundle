<?php
namespace SimpleIT\ClaireAppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use SimpleIT\ClaireAppBundle\Controller\BaseController;
use SimpleIT\ClaireAppBundle\Form\Type\CourseType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Category controller
 */
class CategoryController extends BaseController
{
    /**
     * View the Categories list
     *
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function listAction()
    {
        /* Get the categories */
        $categoryRequest = $this->getCategoryRouteService()->getCategory();
        $categories = $this->getApiService()->getResult($categoryRequest);

        /* Prepare view and parameters */
        $this->view = 'SimpleITClaireAppBundle:Category:list.html.twig';
        $this->viewParameters = array(
            'categories' => $categories->getContent()
        );

        return $this->generateView($this->view, $this->viewParameters);
    }

    /**
     * View a single category
     *
     * @param Request $request The request
     *
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function viewAction(Request $request)
    {
        $categorySlug = $request->get('slug');
        $parameters = $request->query->all();
        $parameters['category'] = $categorySlug;

        /* Get the category */
        $categoryRequest = $this->getCategoryRouteService()->getCategory($categorySlug);
        $category = $this->getApiService()->getResult($categoryRequest);

        /* Throw 404 if object not found */
        $this->checkObjectFound($category);

        /* get related Tags and courses */
        $requests['tags'] = $this->getCategoryRouteService()->getTags($categorySlug);
        $requests['courses'] = $this->getCourseRouteService()->getCourses($parameters);
        $results = $this->getApiService()->getResult($requests);

        /* Prepare view and parameters */
        $this->view = 'SimpleITClaireAppBundle:Category:view.html.twig';
        $this->viewParameters = array(
                'category' => $category->getContent(),
                'tags' => $results['tags']->getContent(),
                'courses' => $results['courses']->getContent(),
                'appPager' => $results['courses']->getPager(),
            );

        return $this->generateView($this->view, $this->viewParameters);
    }


    /**
     * View single Tag
     *
     * @param Request $request The request
     *
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function viewTagAction(Request $request)
    {
        $categorySlug = $request->get('categorySlug');
        $tagSlug = $request->get('slug');
        $parameters = $request->query->all();

        /* get Tag and associated tags */
        $tagRequest = $this->getCategoryRouteService()->getTag($categorySlug, $tagSlug);
        $tag = $this->getApiService()->getResult($tagRequest);

        /* Throw 404 if object not found */
        $this->checkObjectFound($tag);

        $requests['category'] = $this->getCategoryRouteService()->getCategory($categorySlug);
        $requests['courses'] = $this->getCategoryRouteService()->getTagCourses($tagSlug, $parameters);

        $requests['associated-tags'] = $this->getCategoryRouteService()->getAssociatedTags(
            $categorySlug,
            $tagSlug
        );
        $results = $this->getApiService()->getResult($requests);

        /* Prepare view and parameters */
        $this->view = 'TutorialBundle:Category:viewTag.html.twig';
        $this->viewParameters = array(
            'tag' => $tag->getContent(),
            'category' =>  $results['category']->getContent(),
            'associatedTags' => $results['associated-tags']->getContent(),
            'courses' => $results['courses']->getContent(),
            'appPager' => $results['courses']->getPager(),
        );

        return $this->generateView($this->view, $this->viewParameters);
    }

    /**
     * Generate the rendered response
     *
     * @param string $view           The view name
     * @param array  $viewParameters Associated view parameters
     *
     * @return Response A Response instance
     */
    protected function generateView($view, $viewParameters)
    {
        return $this->render($view, $viewParameters);
    }
}
